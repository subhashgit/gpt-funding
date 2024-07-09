<?php

namespace App\Command;

use App\Entity\Embedding\FundingEmbedding;
use App\Entity\Funding;
use App\Repository\Embedding\GrantEmbeddingRepository;
use App\Repository\Embedding\UserCompanyEmbeddingRepository;
use App\Repository\FundingRepository;
use App\Repository\UserRepository;
use App\Service\EmbeddingService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'embeddings:funding',
)]
class GenerateEmbeddingsFundingCommand extends Command
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly UserRepository $userRepository,
        private readonly GrantEmbeddingRepository $grantEmbeddingRepository,
        private readonly UserCompanyEmbeddingRepository $userCompanyEmbeddingRepository,
        private readonly FundingRepository $fundingRepository,
        private readonly EmbeddingService $embeddingService,
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $fundings = $this->fundingRepository->getByDate(new \DateTime());

        $io->progressStart(count($fundings));
        /** @var Funding $funding */
        foreach ($fundings as $funding) {
            dump($funding->getId());
            $io->progressAdvance();

            if (!$funding->indexable()) {
                continue;
            }

            if ($funding->getEmbedding()) {
                continue;
            }

            $embeding = $funding->getEmbedding() ?: new FundingEmbedding();
            $embeding->setEntityId($funding->getId());
            $vector = $this->embeddingService->generateEmbedding($funding->getDescription());

            $embeding->setEntity($funding);
            $embeding->setVector(json_encode($vector->embeddings[0]->embedding));

            $this->entityManager->persist($embeding);
            $this->entityManager->flush();
        }

        $io->progressFinish();
        $io->success('You have a new command! Now make it your own! Pass --help to see your options.');

        return Command::SUCCESS;
    }

    public function generate() {}
}
