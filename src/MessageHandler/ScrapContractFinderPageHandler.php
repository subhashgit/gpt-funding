<?php

namespace App\MessageHandler;

use App\Message\ScrapContractFinderPage;
use App\Repository\FundingRepository;
use App\Service\FundingService;
use App\Service\ProxyscrapeService;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Messenger\Handler\Acknowledger;
use Symfony\Component\Messenger\Handler\BatchHandlerInterface;
use Symfony\Component\Messenger\Handler\BatchHandlerTrait;
use Symfony\Component\Serializer\SerializerInterface;

#[AsMessageHandler]
final class ScrapContractFinderPageHandler implements BatchHandlerInterface
{
    use BatchHandlerTrait;

    public function __construct(
        private readonly FundingRepository $fundingRepository,
        private readonly SerializerInterface $serializer,
        private readonly FundingService $fundingService,
        private readonly ProxyscrapeService $proxyscrapeService,
    ) {}

    public function __invoke(ScrapContractFinderPage $message, ?Acknowledger $ack = null)
    {
        return $this->handle($message, $ack);
    }

    private function process(array $jobs): void
    {
        foreach ($jobs as [$message, $ack]) {
            try {
                $listItem = $message->listItem;
                $funding = $this->fundingService->createFundingAPI(
                    $listItem, $this->fundingRepository->findOneBy(['external_id' => $listItem->id]), 'contractsfinder'
                );

                $ack->ack();
            } catch (\Throwable $e) {
                $ack->nack($e);
            }
        }
    }

    // Optionally, you can either redefine the `shouldFlush()` method
    // of the trait to define your own batch size...
    private function shouldFlush(): bool
    {
        return 100 <= \count($this->jobs);
    }

    // ... or redefine the `getBatchSize()` method if the default
    // flush behavior suits your needs
    private function getBatchSize(): int
    {
        return 100;
    }
}
