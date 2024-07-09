<?php

namespace App\Command;

use App\Message\ScrapFindTenderPage;
use App\Model\API\FindTender;
use App\Repository\FundingRepository;
use App\Service\FundingService;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

#[AsCommand(
    name: 'api:find-tender',
)]
class ApiFindTenderCommand extends Command
{
    public function __construct(
        private readonly MessageBusInterface $bus,
        private readonly HttpClientInterface $client,
        private readonly SerializerInterface $serializer,
        private readonly FundingRepository $fundingRepository,
        private readonly FundingService $fundingService
    ) {
        parent::__construct();
    }

    protected function configure(): void {}

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $params = [
            'limit' => 100,
            'cursor' => null,
            'updatedFrom' => null,
            'updatedTo' => null,
            //            'stages' => 'tender',
        ];

        $url = 'https://www.find-tender.service.gov.uk/api/1.0/ocdsReleasePackages?'.http_build_query(array_filter($params));

        $io->progressStart();

        do {
            $io->text('Fetching '.$url);
            $response = $this->client->request('GET', $url);
            /** @var FindTender $data */
            $data = $this->serializer->denormalize($response->toArray(), FindTender::class);
            foreach ($data->releases as $item) {
                $this->bus->dispatch(new ScrapFindTenderPage($item));

                $io->progressAdvance();
            }

            $url = $data->links->next;
        } while ($url);

        $io->progressFinish();

        return Command::SUCCESS;
    }
}
