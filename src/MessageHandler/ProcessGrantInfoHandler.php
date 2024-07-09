<?php

namespace App\MessageHandler;

use App\Entity\Grant;
use App\Entity\GrantLocation;
use App\Entity\GrantOpenTo;
use App\Message\ProcessGrantInfo;
use App\Repository\GrantCategoryRepository;
use App\Repository\GrantLocationRepository;
use App\Repository\GrantOpenToRepository;
use App\Repository\GrantRepository;
use App\Service\FundingService;
use Doctrine\ORM\EntityManagerInterface;
use Nette\Utils\DateTime;
use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final class ProcessGrantInfoHandler
{
    public function __construct(
        private GrantCategoryRepository $categoryRepository,
        private GrantLocationRepository $grantLocationRepository,
        private FundingService $fundingService,
        private EntityManagerInterface $em,
        private GrantRepository $grantRepository,
        private GrantOpenToRepository $grantOpenToRepository,
        private readonly LoggerInterface $logger,
    ) {}

    public function __invoke(ProcessGrantInfo $message): void
    {
        $item = $message->grantInfo;
        $isNew = false;

        try {
            if (!$grant = $this->grantRepository->findOneBy(['title' => $item->getTitle()])) {
                $isNew = true;
                $grant = new Grant();

                $this->logger->info('New grant: '.$item->getTitle());
            } else {
                $this->logger->info('Existing grant: '.$item->getTitle());
            }

            if ($isNew) {
                $grant->setTitle($item->getTitle());
                $grant->setFunder($item->getFunder());
                $grant->setDescription($item->getDescription());
                $grant->setMaxAmount((int) $this->fundingService->parseMoney($item->getMaxAmount()));
                $grant->setFundingDetails($item->getFundingDetails());
                $grant->setWhoCanApply($item->getWhoCanApply());
                $grant->setHowToApply($item->getHowToApply());

                try {
                    $closing_date = $item->getDeadline() ? DateTime::from($item->getDeadline()) : null;
                } catch (\Exception $e) {
                    $closing_date = null;
                }
                $grant->setClosingDate($closing_date);
                $grant->setPublishedDate($item->getDateAdded());
            }

            $this->setGrantCategories($grant, $item->getCategory());
            $this->setGrantLocations($grant, $item->getRegion());
            $this->setGrantOpenTo($grant, $item->getOpenTo());

            $grant->setFindOutMore($item->getFindOutMore());

            $this->em->persist($grant);
            $this->em->flush();
        } catch (\Exception $e) {
            dump($item, $e);
            throw $e;
        }
    }

    private function setGrantOpenTo(Grant $grant, ?string $openToString): void
    {
        //        $result = \OpenAI::client($this->yourApiKey)->chat()->create([
        //            'model' => 'gpt-3.5-turbo',
        //            'messages' => [
        //                [
        //                    'role' => 'user',
        //                    'content' => "Create coma separated list: $openToString",
        //                ],
        //            ],
        //
        //            'temperature' => 0.9,
        //        ]);

        foreach ($grant->getOpenTo() as $openTo) {
            $grant->removeOpenTo($openTo);
        }

        foreach (explode(',', $openToString) as $openTo) {
            $openTo = trim($openTo);

            if (!$openTo) {
                continue;
            }

            $DBOpenTo = $this->grantOpenToRepository->findOneBy(['open_to' => $openTo]);
            if (!$DBOpenTo) {
                $DBOpenTo = new GrantOpenTo();
                $DBOpenTo->setOpenTo($openTo);

                $this->em->persist($DBOpenTo);
            }

            $grant->addOpenTo($DBOpenTo);
        }
    }

    private function setGrantCategories(Grant $grant, ?string $categoriesString): void
    {
        foreach ($grant->getCategories() as $grantCategory) {
            $grant->removeCategory($grantCategory);
        }

        foreach (explode(',', $categoriesString) as $category) {
            $category = trim($category);

            if (!$category or '-' === $category) {
                continue;
            }

            $DBcategory = $this->categoryRepository->findOneBy(['category' => $category]);
            if (!$DBcategory) {
                $DBcategory = new \App\Entity\GrantCategory();
                $DBcategory->setCategory($category);

                $this->em->persist($DBcategory);
            }

            $grant->addCategory($DBcategory);
        }
    }

    private function setGrantLocations(Grant $grant, ?string $locationsString): void
    {
        $locations = explode(',', $locationsString);

        foreach ($grant->getLocations() as $grantLocation) {
            $grant->removeLocation($grantLocation);
        }

        $hasRegion = false;
        //        foreach ($this->regions as $predefinedRegion) {
        foreach ($locations as $location) {
            $location = trim($location);

            if (!$location) {
                continue;
            }
            //                if (str_contains($predefinedRegion, $location)) {
            $DBlocation = $this->grantLocationRepository->findOneBy(['location' => $location]);
            if (!$DBlocation) {
                $DBlocation = new GrantLocation();
                $DBlocation->setLocation($location);

                $this->em->persist($DBlocation);
            }

            $grant->addLocation($DBlocation);
            $hasRegion = true;
            //                }
            //            }
        }

        if ($locations and !$hasRegion) {
            $fr = $this->grantLocationRepository->findOneBy(['location' => 'International']);
            if (!$fr) {
                $fr = new GrantLocation();
                $fr->setLocation('International');
                $this->em->persist($fr);
            }
            $grant->addLocation($fr);
        }
    }
}
