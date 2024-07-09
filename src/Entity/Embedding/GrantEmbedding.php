<?php

namespace App\Entity\Embedding;

use App\Entity\Grant;
use App\Repository\Embedding\GrantEmbeddingRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: GrantEmbeddingRepository::class)]
class GrantEmbedding extends Embedding
{
    #[ORM\OneToOne(inversedBy: 'embedding', cascade: ['persist', 'remove'], fetch: 'EXTRA_LAZY')]
    #[ORM\JoinColumn(nullable: true)]
    private ?Grant $entity = null;

    public function getEntity(): ?Grant
    {
        return $this->entity;
    }

    public function setEntity(Grant $entity): static
    {
        $this->entity = $entity;

        return $this;
    }
}
