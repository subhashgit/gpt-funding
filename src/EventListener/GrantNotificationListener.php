<?php

namespace App\EventListener;

use App\Entity\Grant;
use App\Entity\GrantCategory;
use App\Entity\GrantLocation;
use App\Entity\GrantOpenTo;
use App\Entity\Notification\UserGrantNotification;
use App\Entity\UserFilter;
use App\Repository\UserRepository;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsDoctrineListener;
use Doctrine\ORM\Event\PostPersistEventArgs;
use Doctrine\ORM\Events;

#[AsDoctrineListener(event: Events::postPersist, priority: 500, connection: 'default')]
class GrantNotificationListener
{
    public function __construct(
        private readonly UserRepository $userRepository,
    ) {}

    public function __invoke(PostPersistEventArgs $event): void
    {
        if ($event->getObject() instanceof Grant) {
            /** @var Grant $grant */
            $grant = $event->getObject();

            $openToIds = array_map(fn (GrantOpenTo $item) => $item->getId(), $grant->getOpenTo()?->toArray());
            $categoryIds = array_map(fn (GrantCategory $item) => $item->getId(), $grant->getCategories()?->toArray());
            $locationIds = array_map(fn (GrantLocation $item) => $item->getId(), $grant->getLocations()?->toArray());

            $users = $this->userRepository->findAll();
            foreach ($users as $user) {
                $filters = $user->getUserFilters()->toArray();
                if (!$filters) {
                    continue;
                }

                $notified = [];

                $userOpenToFilters = $this->filterByType($filters, 'openTo');
                $userCategoryFilters = $this->filterByType($filters, 'category');
                $userLocationFilters = $this->filterByType($filters, 'location');
                $userKeywordFilters = $this->filterByType($filters, 'keyword');

                if (
                    $this->arrayIntersection($userOpenToFilters, $openToIds)
                    && $this->arrayIntersection($userCategoryFilters, $categoryIds)
                    && $this->arrayIntersection($userLocationFilters, $locationIds)
                    && $this->keywordContains($grant->getTitle(), $userKeywordFilters)
                ) {
                    $userNotification = new UserGrantNotification();
                    $userNotification->setUser($user);
                    $userNotification->setGrant($grant);
                    $userNotification->setType('all');
                    $userNotification->setPriority(20);

                    $event->getObjectManager()->persist($userNotification);
                }
            }

            $event->getObjectManager()->flush();
        }
    }

    public function filterByType(array $filters, string $type): array
    {
        $entityArray = array_filter($filters, static fn (UserFilter $item) => $type === $item->getType());

        return array_map(static fn (UserFilter $item) => $item->getTypeId(), $entityArray);
    }

    public function arrayIntersection(?array $userFilters, ?array $grantFilters): bool
    {
        if (!$userFilters) {
            return true;
        }

        if (!$grantFilters) {
            return true;
        }

        return count(array_intersect($userFilters, $grantFilters)) > 0;
    }

    public function keywordContains(string $title, array $keywords): bool
    {
        if (!$keywords) {
            return true;
        }

        foreach ($keywords as $keyword) {
            if (str_contains($title, $keyword)) {
                return true;
            }
        }

        return false;
    }
}
