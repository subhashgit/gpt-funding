<?php

namespace App\Command;

use App\Entity\ScoresRanks;
use App\Model\DeprivationData;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Serializer\SerializerInterface;

#[AsCommand(
    name: 'app:import:denominators',
    description: 'Add a short description for your command',
)]
class ImportDenominatorsCommand extends Command
{
    public function __construct(private SerializerInterface $serializer,
        private EntityManagerInterface $em)
    {
        parent::__construct();
    }

    protected function configure(): void {}

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $csvContent = file_get_contents('./public/File_7_-_All_IoD2019_Scores__Ranks__Deciles_and_Population_Denominators_3.csv');

        /* @var DeprivationData[] $csvData */
        $csvData = $this->serializer->deserialize($csvContent, DeprivationData::class.'[]', 'csv');

        foreach ($csvData as $csvDatum) {
            $den = new ScoresRanks();
            foreach (array_keys((array) $csvDatum) as $array_key) {
                $den->{'set'.ucfirst($array_key)}($csvDatum->{$array_key});
            }
            $this->em->persist($den);
        }

        $this->em->flush();

        $io->success('You have a new command! Now make it your own! Pass --help to see your options.');

        return Command::SUCCESS;
    }
}
