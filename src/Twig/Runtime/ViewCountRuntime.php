<?php

namespace App\Twig\Runtime;

use App\Entity\Funding;
use App\Entity\Grant;
use App\Repository\UserViewsRepository;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Twig\Extension\RuntimeExtensionInterface;

class ViewCountRuntime implements RuntimeExtensionInterface
{
    public function __construct(
        private readonly TokenStorageInterface $tokenStorage,
        private readonly UserViewsRepository $userViewsRepository,
    ) {}

    public function getViewsCount(): int
    {
        return $this->userViewsRepository->countToday($this->tokenStorage->getToken()->getUser()->getId());
    }

    public function getViewsCountThisMonths(): int
    {
        return $this->userViewsRepository->countThisMonth($this->tokenStorage->getToken()->getUser()->getId());
    }

    public function isViewsExceeded(): bool
    {
        return $this->getViewsCount() >= 3;
    }

    public function isViewsThisMonthExceeded(): bool
    {
        return $this->getViewsCountThisMonths() >= 10;
    }

    public function getViewsLeft(): int
    {
        return 3 - $this->getViewsCount();
    }

    public function getViewsLeftThisMonth(): int
    {
        return 10 - $this->getViewsCount();
    }

    public function isViewedToday(Funding|Grant $entity): bool
    {
        $user = $this->tokenStorage->getToken()->getUser();

        $count = $this
            ->userViewsRepository
            ->countTodayByEntity($user->getId(), $entity->getId(), $entity instanceof Grant ? 'grant' : 'funding')
        ;

        return $count > 0;
    }

    public function isViewedThisMont(Funding|Grant $entity): bool
    {
        $user = $this->tokenStorage->getToken()->getUser();

        $count = $this
            ->userViewsRepository
            ->countThisMonthByEntity($user->getId(), $entity->getId(), $entity instanceof Grant ? 'grant' : 'funding')
        ;

        return $count > 0;
    }
}
