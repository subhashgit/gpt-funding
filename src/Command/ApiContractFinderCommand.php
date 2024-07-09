<?php

namespace App\Command;

use App\Message\ScrapContractFinderPage;
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
    name: 'api:contract-finder',
)]
class ApiContractFinderCommand extends Command
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
            'publishedFrom' => null,
            'publishedTo' => null,
            'stages' => 'award',
        ];

        $url = 'https://www.contractsfinder.service.gov.uk/Published/Notices/OCDS/Search?'.http_build_query(array_filter($params));

        $io->progressStart();

        do {
            $io->text('Fetching '.$url);
            $response = $this->client->request('GET', $url);
            /** @var FindTender $data */
            $data = $this->serializer->denormalize($response->toArray(), FindTender::class);
            foreach ($data->releases as $item) {
                $this->bus->dispatch(new ScrapContractFinderPage($item));

                $io->progressAdvance();
            }

            $url = $data->links->next;
        } while ($url);

        $io->progressFinish();

        $io->success('You have a new command! Now make it your own! Pass --help to see your options.');

        return Command::SUCCESS;
    }
}
