<?php

namespace App\Entity\Embedding;

use App\Entity\UserProject;
use App\Repository\Embedding\UserProjectEmbeddingRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UserProjectEmbeddingRepository::class)]
class UserProjectEmbedding extends Embedding
{
    #[ORM\OneToOne(inversedBy: 'embedding', cascade: ['persist', 'remove'], fetch: 'EXTRA_LAZY')]
    #[ORM\JoinColumn(nullable: true)]
    private ?UserProject $entity = null;

    public function getEntity(): ?UserProject
    {
        return $this->entity;
    }

    public function setEntity(UserProject $entity): static
    {
        $this->entity = $entity;

        return $this;
    }
}
