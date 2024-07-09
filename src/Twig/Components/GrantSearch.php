<?php

namespace App\Twig\Components;

use App\Entity\Grant;
use App\Entity\User;
use App\Repository\Embedding\GrantEmbeddingRepository;
use App\Repository\GrantRepository;
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
final class GrantSearch extends AbstractController
{
    use DefaultActionTrait;

    #[LiveProp(writable: true)]
    public ?string $query = '';

    #[LiveProp(writable: true)]
    public ?int $page = 1;

    #[LiveProp(writable: true)]
    public ?array $filters = [];

    #[LiveProp(writable: true)]
    public ?string $order = 'published_date:desc';

    #[LiveProp(writable: true)]
    public ?array $selectedFilters = [
        'location' => null,
        'category' => null,
        'open_to' => null,
        'watching' => null,
        'status' => [],
        'ai' => [],
        'ai_project' => [],
    ];

    #[LiveProp(writable: true)]
    public array $likedIds = [];

    public array $ids = [];

    private ?array $AIfilter = null;

    public function __construct(
        private readonly GrantRepository $grantRepository,
        private readonly PaginatorInterface $paginator,
        private readonly SearchService $searchService,
        private readonly EntityManagerInterface $entityManager,
        private readonly UserLikesRepository $userLikesRepository,
        private readonly GrantEmbeddingRepository $grantEmbeddingRepository,
    ) {}

    public function getGrants(): array
    {
        /** @var User $user */
        $user = $this->getUser();

        $filtersLocation = $this->grantRepository->getFilters('location');
        $filtersCategory = $this->grantRepository->getFilters('category');
        $filtersOpenTo = $this->grantRepository->getFilters('open_to');

        sort($filtersLocation);
        sort($filtersCategory);
        sort($filtersOpenTo);

        $this->filters = [
            'location' => $filtersLocation,
            'category' => $filtersCategory,
            'open_to' => $filtersOpenTo,
        ];

        $hits = $this->searchService
            ->search($this->entityManager, Grant::class, $this->query, [
                'filter' => $this->getFilterString(true),
                'sort' => [$this->order],
                'page' => $this->getIsAi() ? 1 : $this->page,
                'hitsPerPage' => 40,
            ])
        ;

        if ($this->getIsAi()) {
            $embeddings = $this->getGrantEmbeddings(
                $this->isAiCompany() ?
                    $user->getCompanies()->first()->getEmbedding()->getVector() :
                    $user->getProjects()->first()->getEmbedding()->getVector()
            );

            $embeddingIds = array_column($embeddings, 'id');
            usort($hits, static function (Grant $a, Grant $b) use ($embeddingIds) {
                return array_search($a->getId(), $embeddingIds, true) <=> array_search($b->getId(), $embeddingIds, true);
            });
        }

        $this->ids = array_map(
            static fn ($item) => $item->getId(),
            $hits
        );
        $this->likedIds = $this->userLikesRepository->getLikedIds($this->getUser(), $this->ids, 'grant');

        $count = $this->searchService
            ->count(Grant::class, $this->query, [
                'filter' => $this->getFilterString(),
                'hitsPerPage' => 40,
            ])
        ;

        $hasPrevPage = $this->page > 1;
        $hasNextPage = $this->page < ceil($count / 40);

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
        if ($this->selectedFilters && array_key_exists($filter, $this->selectedFilters) && in_array($value, $this->selectedFilters[$filter] ?? [], true)) {
            $this->selectedFilters[$filter] = array_diff($this->selectedFilters[$filter], [$value]);
        } else {
            $this->selectedFilters[$filter][] = $value;
        }
        $this->page = 1;
    }

    public function getFilterString(bool $notForCount = false): string
    {
        $filterString = [];
        $filterOrString = [];
        /** @var User $user */
        $user = $this->getUser();

        if ($this->selectedFilters['watching']) {
            $watched = implode(',', array_map(fn ($i) => "'{$i}'", $this->userLikesRepository->getLikedIds($this->getUser(), null, 'grant')));
            $filterString[] = "id IN [{$watched}]";
        }

        if ($this->selectedFilters['location']) {
            $filtersLocationString = implode(',', array_map(fn ($item) => "'{$item}'", $this->selectedFilters['location']));
            $filterString[] = "grantLocations IN [{$filtersLocationString}]";
        }

        if ($this->selectedFilters['category']) {
            $filterSectorsString = implode(',', array_map(fn ($item) => "'{$item}'", $this->selectedFilters['category']));

            $filterString[] = "grantCategories IN [{$filterSectorsString}]";
        }

        if ($this->selectedFilters['open_to']) {
            $filterSectorsString = implode(',', array_map(fn ($item) => "'{$item}'", $this->selectedFilters['open_to']));

            $filterString[] = "grantOpenTo IN [{$filterSectorsString}]";
        }

        //        if (in_array('open', $this->selectedFilters['status'], true)) {
        $closing_start_date = DateTime::from(time())->getTimestamp();
        $filterOrString[] = "closing_date >= '{$closing_start_date}'";
        $filterOrString[] = 'closing_date IS NULL';
        //        }

        //        if (in_array('closed', $this->selectedFilters['status'], true)) {
        //            $closing_end_date = DateTime::from(time())->getTimestamp();
        //            $filterOrString[] = "closing_date <= '{$closing_end_date}'";
        //        }

        if ($this->getIsAi()) {
            $firstCategory = !empty($this->selectedFilters['ai']) ? $user->getCompanies()->first() : $user->getProjects()->first();

            if ($firstCategory->getLocations()) {
                $filterSectorsString = implode(',',
                    array_map(
                        static fn ($item) => "'{$item}'",
                        $firstCategory->getLocations()
                    )
                );
                $filterString[] = "grantLocations IN [{$filterSectorsString}]";

                $this->selectedFilters['location'] = $firstCategory->getLocations();
            }

            if ($firstCategory->getOpenTo()) {
                $filterSectorsString = implode(
                    ',', array_map(
                        static fn ($item) => "'{$item}'",
                        $firstCategory->getOpenTo()
                    )
                );
                $filterString[] = "grantOpenTo IN [{$filterSectorsString}]";

                $this->selectedFilters['open_to'] = $firstCategory->getOpenTo();
            }
            $a = $this->getGrantEmbeddings($this->isAiCompany() ? $user->getCompanies()->first()->getEmbedding()->getVector() :
                $user->getProjects()->first()->getEmbedding()->getVector());
            if ($notForCount) {
                $a = array_slice($a, $this->page * 40 - 40, 40);
            }

            $ids = implode(
                ',', array_map(
                    static fn ($item) => $item['id'],
                    $a
                ));

            $filterString[] = "id IN [{$ids}]";
        }

        if ($filterOrString) {
            $filterString[] = '('.implode(' OR ', $filterOrString).')';
        }

        return implode(' AND ', $filterString);
    }

    #[LiveAction]
    public function like(#[LiveArg] int $entity): void
    {
        $grant = $this->grantRepository->find($entity);

        /** @var User $user */
        $user = $this->getUser();

        $this->userLikesRepository->likeGrant($user, $grant);

        $this->likedIds = $this->userLikesRepository->getLikedIds($user, $this->ids, 'grant');
    }

    public function getGrantEmbeddings(string $vector): array
    {
        return $this->grantEmbeddingRepository->vectorFind(
            $vector,
            100,
            distance: $this->isAiCompany() ? 0.75 : 0.65
        );
    }

    /**
     * @return mixed
     */
    public function getIsAi(): bool
    {
        return !empty($this->selectedFilters['ai']) || !empty($this->selectedFilters['ai_project']);
    }

    public function isAiProject(): bool
    {
        return !empty($this->selectedFilters['ai_project']);
    }

    public function isAiCompany(): bool
    {
        return !empty($this->selectedFilters['ai']);
    }
}
