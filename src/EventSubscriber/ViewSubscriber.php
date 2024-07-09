<?php

namespace App\EventSubscriber;

use App\Entity\Grant;
use App\Entity\UserViews;
use App\Event\View;
use App\Repository\UserViewsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\SwitchUserToken;
use Symfony\Component\Security\Core\User\UserInterface;

class ViewSubscriber implements EventSubscriberInterface
{
    private const MAX_VIEWS_PER_DAY = 10;

    public function __construct(
        private readonly TokenStorageInterface $tokenStorage,
        private readonly UserViewsRepository $userViewsRepository,
        private readonly EntityManagerInterface $em
    ) {}

    public function onView(View $event): bool
    {
        $token = $this->tokenStorage->getToken();
        if ($token instanceof SwitchUserToken) {
            return true;
        }
        $user = $token->getUser();

        if ($user->isSubscriptionActive()) {
            return true;
        }

        $userId = $user->getId();
        $count = $this->userViewsRepository->countToday($userId);

        if ($count >= self::MAX_VIEWS_PER_DAY) {
            return false;
        }

        $viewEntity = $event->entity;
        $type = $viewEntity instanceof Grant ? 'grant' : 'funding';
        if ($this->userViewsRepository->countTodayByEntity($userId, $viewEntity->getId(), $type)) {
            return true;
        }

        $this->saveView($user, $event, $type);

        return true;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            View::class => 'onView',
        ];
    }

    public function saveView(?UserInterface $user, View $event, string $type): void
    {
        $view = new UserViews();
        $view->setUser($user);
        $view->setEntityId($event->entity->getId());
        $view->setType($type);

        $this->em->persist($view);
        $this->em->flush();
    }
}
