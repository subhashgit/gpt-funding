<?php

namespace App\MessageHandler;

use App\Enum\Status;
use App\Message\UpdateStatus;
use App\Repository\FundingRepository;
use App\Repository\GrantRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final class UpdateStatusHandler
{
    public function __construct(
        private FundingRepository $fundingRepository,
        private GrantRepository $grantRepository,
        private EntityManagerInterface $entityManager
    ) {}

    public function __invoke(UpdateStatus $message): void
    {
        foreach ($this->fundingRepository->getEnded() as $funding) {
            $funding->setStatus(Status::CLOSED);
        }

        foreach ($this->grantRepository->getEnded() as $funding) {
            $funding->setStatus(Status::CLOSED);
        }

        $this->entityManager->flush();
    }
}
