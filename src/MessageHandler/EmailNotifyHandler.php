<?php

namespace App\MessageHandler;

use App\Message\EmailNotify;
use App\Message\UserEmailNotify;
use App\Repository\Notification\UserFundingNotificationRepository;
use App\Repository\Notification\UserGrantNotificationRepository;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Messenger\MessageBusInterface;

#[AsMessageHandler]
final class EmailNotifyHandler
{
    public function __construct(
        private readonly UserFundingNotificationRepository $userFundingNotificationRepository,
        private readonly UserGrantNotificationRepository $userGrantNotificationRepository,
        private readonly MessageBusInterface $bus,
    ) {}

    public function __invoke(EmailNotify $message): void
    {
        foreach ($this->userGrantNotificationRepository->getNotNotifiedUsers() as $user) {
            $this->bus->dispatch(new UserEmailNotify($user['id'], 'grant'));
        }

        foreach ($this->userFundingNotificationRepository->getNotNotifiedUsers() as $user) {
            $this->bus->dispatch(new UserEmailNotify($user['id'], 'funding'));
        }
    }
}
