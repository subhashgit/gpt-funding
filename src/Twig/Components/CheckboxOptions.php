<?php

namespace App\Twig\Components;

use App\Entity\UserFilter;
use App\Repository\CPVCodeRepository;
use App\Repository\FundingRegionRepository;
use App\Repository\GrantCategoryRepository;
use App\Repository\GrantLocationRepository;
use App\Repository\GrantOpenToRepository;
use App\Repository\UserFilterRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveAction;
use Symfony\UX\LiveComponent\Attribute\LiveArg;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\DefaultActionTrait;

#[AsLiveComponent()]
final class CheckboxOptions extends AbstractController
{
    use DefaultActionTrait;

    #[LiveProp(writable: true)]
    public array $options = [];

    #[LiveProp(writable: true)]
    public string $name = '';

    #[LiveProp(writable: true)]
    public $selectedFilters = [];

    public function __construct(
        private readonly GrantLocationRepository $grantLocationRepository,
        private readonly GrantCategoryRepository $grantCategoryRepository,
        private readonly GrantOpenToRepository $grantOpenToRepository,
        private readonly FundingRegionRepository $fundingRegionRepository,
        private readonly CPVCodeRepository $codeRepository,
        private readonly UserFilterRepository $userFilterRepository,
    ) {}

    #[LiveAction]
    public function check(#[LiveArg] string $name, #[LiveArg] string $propertyName): void
    {
        $name = urldecode($name);

        switch ($propertyName) {
            case 'location':
                $notificationType = 'grant';
                $filter = $this->grantLocationRepository->findOneBy(['location' => $name]);

                break;

            case 'category':
                $notificationType = 'grant';
                $filter = $this->grantCategoryRepository->findOneBy(['category' => $name]);

                break;

            case 'openTo':
                $notificationType = 'grant';
                $filter = $this->grantOpenToRepository->findOneBy(['open_to' => $name]);

                break;

            case 'sector':
                $notificationType = 'funding';
                $filter = $this->codeRepository->findOneBy(['description' => $name]);

                break;

            case 'region':
                $notificationType = 'funding';
                $filter = $this->fundingRegionRepository->findOneBy(['region' => $name]);

                break;
        }

        $userFilter = $this->userFilterRepository->findOneBy([
            'user' => $this->getUser(),
            'type' => $propertyName,
            'type_id' => $filter->getId(),
        ]);

        if ($userFilter) {
            $this->userFilterRepository->remove($userFilter, true);
        } else {
            $userFilter = new UserFilter();
            $userFilter->setUser($this->getUser());
            $userFilter->setType($propertyName);
            $userFilter->setTypeId($filter->getId());
            $userFilter->setNotificationType($notificationType);

            $this->userFilterRepository->save($userFilter, true);
        }
    }
}
