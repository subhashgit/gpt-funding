<?php

namespace App\Command;

use App\Entity\Embedding\GrantEmbedding;
use App\Repository\Embedding\GrantEmbeddingRepository;
use App\Repository\Embedding\UserCompanyEmbeddingRepository;
use App\Repository\GrantRepository;
use App\Repository\UserRepository;
use App\Service\EmbeddingService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'embeddings:grant',
)]
class GenerateEmbeddingsGrantCommand extends Command
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly UserRepository $userRepository,
        private readonly GrantEmbeddingRepository $grantEmbeddingRepository,
        private readonly UserCompanyEmbeddingRepository $userCompanyEmbeddingRepository,
        private readonly GrantRepository $grantRepository,
        private readonly EmbeddingService $embeddingService,
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $grants = $this->grantRepository->findBy([], ['id' => 'ASC']);

        $io->progressStart(count($grants));
        foreach ($grants as $grant) {
            $io->progressAdvance();

            $embeding = $grant->getEmbedding() ?: new GrantEmbedding();
            $embeding->setEntityId($grant->getId());
            $vector = $this->embeddingService->generateEmbedding($grant->getDescription());

            $embeding->setEntity($grant);
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
