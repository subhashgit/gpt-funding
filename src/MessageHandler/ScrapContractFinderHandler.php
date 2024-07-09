<?php

namespace App\MessageHandler;

use App\Message\ScrapContractFinder;
use App\Message\ScrapContractFinderPage;
use App\Model\API\FindTender;
use App\Service\ProxyscrapeService;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

#[AsMessageHandler]
final class ScrapContractFinderHandler
{
    public function __construct(
        private SerializerInterface $serializer,
        private MessageBusInterface $bus,
        private ProxyscrapeService $proxyscrapeService,
        private readonly HttpClientInterface $client,
    ) {}

    public function __invoke(ScrapContractFinder $message): void
    {
        $params = [
            'limit' => 100,
            'cursor' => null,
            'publishedFrom' => null,
            'publishedTo' => null,
            //            'stages' => 'tender',
        ];

        $url = 'https://www.contractsfinder.service.gov.uk/Published/Notices/OCDS/Search?'.http_build_query(array_filter($params));

        do {
            $response = $this->client->request('GET', $url);
            /** @var FindTender $data */
            $data = $this->serializer->denormalize($response->toArray(), FindTender::class);
            foreach ($data->releases as $item) {
                $this->bus->dispatch(new ScrapContractFinderPage($item));
            }
            $url = $data->links->next;
        } while ($url);
    }
}
