<?php

namespace App\EventListener;

use App\Entity\Embedding\GrantEmbedding;
use App\Entity\Grant;
use App\Service\EmbeddingService;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsDoctrineListener;
use Doctrine\ORM\Event\PostPersistEventArgs;
use Doctrine\ORM\Event\PostUpdateEventArgs;
use Doctrine\ORM\Events;
use Psr\Log\LoggerInterface;

#[AsDoctrineListener(event: Events::postPersist, priority: 100, connection: 'default')]
#[AsDoctrineListener(event: Events::postUpdate, priority: 100, connection: 'default')]
class GrantEmbeddingListener
{
    public function __construct(
        private readonly EmbeddingService $embeddingService,
        private readonly LoggerInterface $logger,
    ) {}

    public function __invoke(PostPersistEventArgs|PostUpdateEventArgs $event): void
    {
        if ($event->getObject() instanceof Grant) {
            /** @var Grant $grant */
            $grant = $event->getObject();

            $entityChangeSet = $event->getObjectManager()->getUnitOfWork()->getEntityChangeSet($grant);

            if (!$grant->getEmbedding() || ($entityChangeSet && array_key_exists('description', $entityChangeSet))) {
                $this->logger->info('Generating embedding for grant: '.$grant->getTitle());

                $vector = $this->embeddingService->generateEmbedding($grant->getDescription());

                $embedding = $grant->getEmbedding() ?: new GrantEmbedding();
                $embedding->setEntity($grant);
                $embedding->setEntityId($grant->getId());
                $embedding->setVector(json_encode($vector->embeddings[0]->embedding));
                $event->getObjectManager()->persist($embedding);

                $event->getObjectManager()->flush();
            }
        }
    }
}
