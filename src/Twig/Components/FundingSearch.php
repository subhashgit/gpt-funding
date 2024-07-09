<?php

namespace App\Twig\Components;

use App\Entity\Funding;
use App\Entity\User;
use App\Enum\Tag;
use App\Repository\CPVCodeRepository;
use App\Repository\Embedding\FundingEmbeddingRepository;
use App\Repository\FundingRegionRepository;
use App\Repository\FundingRepository;
use App\Repository\UserLikesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Meilisearch\Bundle\SearchService;
use Nette\Utils\DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveAction;
use Symfony\UX\LiveComponent\Attribute\LiveArg;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\DefaultActionTrait;

#[AsLiveComponent()]
final class FundingSearch extends AbstractController
{
    use DefaultActionTrait;

    #[LiveProp(writable: true)]
    public ?string $query = '';

    #[LiveProp(writable: true)]
    public ?int $page = 1;

    #[LiveProp(writable: true)]
    public ?string $order = 'published_date:desc';

    #[LiveProp(writable: true)]
    public ?array $filters = [];

    #[LiveProp(writable: true)]
    public ?string $publication_start_date = null;

    #[LiveProp(writable: true)]
    public ?string $publication_end_date = null;

    #[LiveProp(writable: true)]
    public ?string $closing_start_date = null;

    #[LiveProp(writable: true)]
    public ?string $closing_end_date = null;

    #[LiveProp(writable: true)]
    public ?array $selectedFilters = [
        'region' => null,
        'sector' => null,
        'watching' => null,
        'tags' => null,
        'ai' => [],
    ];

    #[LiveProp(writable: true)]
    public array $likedIds = [];

    public array $ids = [];

    #[LiveProp(writable: true)]
    public array $tags = [
        'tender' => Tag::TENDER->value,
        'contract' => Tag::CONTRACT->value,
        //        'award' => Tag::AWARD->value,
        'planing' => Tag::PLANNING->value,
    ];

    public function __construct(
        private FundingRepository $fundingRepository,
        private PaginatorInterface $paginator,
        private FundingRegionRepository $fundingRegionRepository,
        private CPVCodeRepository $codeRepository,
        private SearchService $searchService,
        private readonly EntityManagerInterface $entityManager,
        private readonly UserLikesRepository $userLikesRepository,
        private readonly FundingEmbeddingRepository $fundingEmbeddingRepository,
    ) {}

    public function getFundings(): array
    {
        $filtersRegion = $this->fundingRepository->getFilters('region');
        $filtersSector = $this->fundingRepository->getFilters('sector');

        $sectors = array_unique(
            array_map(
                static fn ($item) => "{$item[0]}{$item[1]}000000",
                array_keys($filtersSector)
            )
        );

        $this->filters = [
            'region' => $filtersRegion,
            'sector' => $this->codeRepository->findMainCategory($sectors),
        ];

        $filterString = $this->getFilterString();

        $hits = $this->searchService
            ->search($this->entityManager, Funding::class, $this->query, [
                'filter' => $filterString,
                'sort' => [$this->order],
                'page' => $this->page,
                'hitsPerPage' => 20,
            ])
        ;

        $this->ids = array_map(
            static fn ($item) => $item->getId(),
            $hits
        );
        $this->likedIds = $this->userLikesRepository->getLikedIds($this->getUser(), $this->ids);

        $count = $this->searchService
            ->count(Funding::class, $this->query, [
                'filter' => $filterString,
                'hitsPerPage' => 20,
            ])
        ;

        $hasPrevPage = $this->page > 1;
        $hasNextPage = $this->page < ceil($count / 20);

        return [
            'items' => $hits,
            'count' => $count,
            'hasPrevPage' => $hasPrevPage,
            'hasNextPage' => $hasNextPage,
        ];
    }

    #[LiveAction]
    public function search(): void
    {
        $this->page = 1;
    }

    #[LiveAction]
    public function nextPage(): void
    {
        ++$this->page;
    }

    #[LiveAction]
    public function prevPage(): void
    {
        --$this->page;
    }

    #[LiveAction]
    public function filter(#[LiveArg] string $filter, #[LiveArg] string $value): void
    {
        if (in_array($filter, ['value_low', 'value_high'])) {
            if ($this->selectedFilters && array_key_exists($filter, $this->selectedFilters) && $this->selectedFilters[$filter] === $value) {
                $this->selectedFilters[$filter] = null;
            } else {
                $this->selectedFilters[$filter] = $value;
            }
        } else {
            if ($this->selectedFilters && array_key_exists($filter, $this->selectedFilters) && in_array($value, $this->selectedFilters[$filter] ?? [], true)) {
                $this->selectedFilters[$filter] = array_diff($this->selectedFilters[$filter], [$value]);
            } else {
                $this->selectedFilters[$filter][] = $value;
            }
        }
        $this->page = 1;
    }

    public function getFilterString(): string
    {
        $filterString[] = '';
        /** @var User $user */
        $user = $this->getUser();
        if ($this->selectedFilters['ai']) {
            //            $firstCategory = $user->getCompanies()->first();

            //            if ($firstCategory->getLocations()) {
            //                $filterSectorsString = implode(',',
            //                    array_map(
            //                        static fn ($item) => "'{$item}'",
            //                        $firstCategory->getLocations()
            //                    )
            //                );
            //                $filterString[] = "grantLocations IN [{$filterSectorsString}]";
            //            }
            //
            //            if ($firstCategory->getOpenTo()) {
            //                $filterSectorsString = implode(
            //                    ',', array_map(
            //                        static fn ($item) => "'{$item}'",
            //                        $firstCategory->getOpenTo()
            //                    )
            //                );
            //                $filterString[] = "grantOpenTo IN [{$filterSectorsString}]";
            //            }

            $a = $this->fundingEmbeddingRepository->vectorFind($user->getCompanies()->first()->getEmbedding()->getVector(), 100);

            $ids = implode(
                ',', array_map(
                    static fn ($item) => $item['id'],
                    $a
                ));
            $filterString[] = "id IN [{$ids}]";

            $closing_end_date = DateTime::from(time())->getTimestamp();
            //            $filterString[] = "(closing_date IS NULL OR closing_date > '{$closing_end_date}')";

            return implode(' AND ', array_filter($filterString));
        }

        if ($this->selectedFilters['watching']) {
            $watched = implode(',', array_map(fn ($i) => "'{$i}'", $this->userLikesRepository->getLikedIds($this->getUser())));
            $filterString[] = "id IN [{$watched}]";
        }

        if ($this->selectedFilters['region']) {
            $filtersRegionString = implode(',', array_map(fn ($item) => "'{$item}'", $this->selectedFilters['region']));
            $filterString[] = "fundingRegions IN [{$filtersRegionString}]";
        }

        if ($this->selectedFilters['sector']) {
            $filterSectorsString = implode(',', array_map(fn ($item) => "'{$item[0]}{$item[1]}'", $this->selectedFilters['sector']));

            $filterString[] = "mainSectors IN [{$filterSectorsString}]";
        }

        if ($this->publication_start_date) {
            $publication_start_date = DateTime::from($this->publication_start_date)->getTimestamp();
            $filterString[] = "published_date >= '{$publication_start_date}'";
        }

        if ($this->publication_end_date) {
            $publication_end_date = DateTime::from($this->publication_end_date)->getTimestamp();
            $filterString[] = "published_date <= '{$publication_end_date}'";
        }

        $closing_start_date = $this->closing_start_date ? DateTime::from($this->closing_start_date) : DateTime::from('now');
        $filterString[] = "(closing_date IS NULL OR closing_date >= '{$closing_start_date->getTimestamp()}')";
        $filterString[] = 'hasParent = FALSE';

        if ($this->closing_end_date) {
            $closing_end_date = DateTime::from($this->closing_end_date)->getTimestamp();
            $filterString[] = "closing_date <= '{$closing_end_date}'";
        }

        $tags = !empty($this->selectedFilters['tags']) ? $this->selectedFilters['tags'] : $this->tags;
        $filterTagsString = implode(',', array_map(fn ($item) => "'{$item}'", $tags));
        $filterString[] = "tags IN [{$filterTagsString}]";

        return implode(' AND ', array_filter($filterString));
    }

    #[LiveAction]
    public function like(#[LiveArg] int $entity): void
    {
        $funding = $this->fundingRepository->find($entity);

        /** @var User $user */
        $user = $this->getUser();

        $this->userLikesRepository->likeFunding($user, $funding);

        $this->likedIds = $this->userLikesRepository->getLikedIds($user, $this->ids);
    }
}
