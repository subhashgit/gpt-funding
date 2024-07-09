<?php

namespace App\MessageHandler;

use App\Message\ScrapFindTender;
use App\Message\ScrapFindTenderPage;
use App\Model\API\FindTender;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

#[AsMessageHandler]
final class ScrapFindTenderHandler
{
    public function __construct(
        private SerializerInterface $serializer,
        private MessageBusInterface $bus,
        private readonly HttpClientInterface $client,
    ) {}

    public function __invoke(ScrapFindTender $message): void
    {
        $params = [
            'limit' => 100,
            'cursor' => null,
            'publishedFrom' => null,
            'publishedTo' => null,
            'stages' => 'tender',
        ];

        $url = 'https://www.find-tender.service.gov.uk/api/1.0/ocdsReleasePackages?'.http_build_query(array_filter($params));

        do {
            $response = $this->client->request('GET', $url);
            /** @var FindTender $data */
            $data = $this->serializer->denormalize($response->toArray(), FindTender::class);
            foreach ($data->releases as $item) {
                $this->bus->dispatch(new ScrapFindTenderPage($item));
            }
            $url = $data->links->next;
        } while ($url);
    }
}
