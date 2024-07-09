<?php

namespace App\MessageHandler;

use App\Message\GetGrantInfo;
use App\Message\ProcessGrantInfo;
use App\Model\Grant;
use App\Repository\GrantRepository;
use Google\Client;
use Google\Service\Sheets as GoogleSheets;
use Revolution\Google\Sheets\Sheets;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Serializer\SerializerInterface;

#[AsMessageHandler]
final class GetGrantInfoHandler
{
    public function __construct(
        private Client $client,
        private GrantRepository $grantRepository,
        private MessageBusInterface $bus,
        private SerializerInterface $serializer
    ) {}

    public function __invoke(GetGrantInfo $message): void
    {
        $this->client->setScopes([GoogleSheets::DRIVE, GoogleSheets::SPREADSHEETS]);

        $service = new GoogleSheets($this->client);

        $sheets = new Sheets();
        $sheets->setService($service);

        $values = $sheets
            ->spreadsheet('1-Ee0CuRrwOgm5mPJcj8oVFrS2zu1lPXDYUd-esWd3-8')
            ->sheet('Sheet1')
            ->all()
        ;
        $keys = array_shift($values);

        $values = array_map(static function ($row) use ($keys) {
            $rowCount = count($row);
            $keysCount = count($keys);
            if ($rowCount === $keysCount) {
                return array_combine($keys, $row);
            }
            $itemsToAdd = $keysCount - $rowCount;

            if ($itemsToAdd < 0) {
                return array_combine($keys, array_slice($row, 0, $rowCount - $itemsToAdd * -1));
            }

            return array_combine($keys, $row + array_fill($rowCount, $itemsToAdd, null));
        }, $values);

        foreach ($values as $item) {
            try {
                /** @var Grant $grant */
                $grant = $this->serializer->denormalize($item, Grant::class);
            } catch (\Exception $e) {
                dump($item, $e);
                continue;
            }

            if (!$grant->getTitle()) {
                continue;
            }
            $this->bus->dispatch(new ProcessGrantInfo($grant));
        }
    }
}
