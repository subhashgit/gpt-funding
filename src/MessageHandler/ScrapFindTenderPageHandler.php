<?php

namespace App\MessageHandler;

use App\Message\ScrapFindTenderPage;
use App\Repository\FundingRepository;
use App\Service\FundingService;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Messenger\Handler\Acknowledger;
use Symfony\Component\Messenger\Handler\BatchHandlerInterface;
use Symfony\Component\Messenger\Handler\BatchHandlerTrait;

#[AsMessageHandler]
final class ScrapFindTenderPageHandler implements BatchHandlerInterface
{
    use BatchHandlerTrait;

    public function __construct(
        private FundingRepository $fundingRepository,
        private FundingService $fundingService,
    ) {}

    public function __invoke(ScrapFindTenderPage $message, ?Acknowledger $ack = null)
    {
        return $this->handle($message, $ack);
    }

    private function process(array $jobs): void
    {
        foreach ($jobs as [$message, $ack]) {
            try {
                $listItem = $message->listItem;
                $funding = $this->fundingService->createFundingAPI(
                    $listItem,
                    $this->fundingRepository->findOneBy(
                        ['external_id' => $listItem->id]
                    ),
                    'find-tender'
                );

                $ack->ack();
            } catch (\Throwable $e) {
                dump($e);

                $ack->nack($e);
            }
        }
    }

    // Optionally, you can either redefine the `shouldFlush()` method
    // of the trait to define your own batch size...
    private function shouldFlush(): bool
    {
        return 10 <= \count($this->jobs);
    }

    // ... or redefine the `getBatchSize()` method if the default
    // flush behavior suits your needs
    private function getBatchSize(): int
    {
        return 10;
    }
}
