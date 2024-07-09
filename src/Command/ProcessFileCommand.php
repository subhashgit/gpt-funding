<?php

namespace App\Command;

use App\Message\ProcessFunding;
use App\Model\Funding;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Serializer\SerializerInterface;

#[AsCommand(
    name: 'process-file',
)]
class ProcessFileCommand extends Command
{
    public function __construct(private SerializerInterface $serializer, private MessageBusInterface $bus)
    {
        parent::__construct();
    }

    protected function configure(): void {}

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $io->title('Process File');

        $file = file_get_contents('./public/notices.csv');

        /** @var Funding[] $fundings */
        $fundings = $this->serializer->deserialize($file, Funding::class.'[]', 'csv');

        $io->title('Processing funding');

        foreach ($fundings as $funding) {
            $io->text($funding->getNoticeIdentifier());

            $this->bus->dispatch(new ProcessFunding($funding));
        }

        $io->success('You have a new command! Now make it your own! Pass --help to see your options.');

        return Command::SUCCESS;
    }
}
