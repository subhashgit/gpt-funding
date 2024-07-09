<?php

namespace App\Entity\Embedding;

use App\Entity\Funding;
use App\Repository\Embedding\FundingEmbeddingRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FundingEmbeddingRepository::class)]
class FundingEmbedding extends Embedding
{
    #[ORM\OneToOne(inversedBy: 'embedding', cascade: ['persist', 'remove'], fetch: 'EXTRA_LAZY')]
    #[ORM\JoinColumn(nullable: true)]
    private ?Funding $entity = null;

    public function getEntity(): ?Funding
    {
        return $this->entity;
    }

    public function setEntity(Funding $entity): static
    {
        $this->entity = $entity;

        return $this;
    }
}
