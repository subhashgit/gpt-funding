<?php

namespace App\EventListener;

use App\Entity\Embedding\FundingEmbedding;
use App\Entity\Funding;
use App\Service\EmbeddingService;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsDoctrineListener;
use Doctrine\ORM\Event\PostPersistEventArgs;
use Doctrine\ORM\Events;

#[AsDoctrineListener(event: Events::postPersist, priority: 500, connection: 'default')]
class TenderEmbeddingListener
{
    public function __construct(
        private readonly EmbeddingService $embeddingService,
    ) {}

    public function __invoke(PostPersistEventArgs $event): void
    {
        if ($event->getObject() instanceof Funding) {
            /** @var Funding $funding */
            $funding = $event->getObject();

            $vector = $this->embeddingService->generateEmbedding($funding->getDescription());

            $embedding = new FundingEmbedding();
            $embedding->setEntity($funding);
            $embedding->setEntityId($funding->getId());
            $embedding->setVector(json_encode($vector->embeddings[0]->embedding));

            $event->getObjectManager()->persist($embedding);

            $event->getObjectManager()->flush();
        }
    }
}
