<?php

namespace App\Entity\Embedding;

use App\Entity\UserCompany;
use App\Repository\Embedding\UserCompanyEmbeddingRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UserCompanyEmbeddingRepository::class)]
class UserCompanyEmbedding extends Embedding
{
    #[ORM\OneToOne(inversedBy: 'embedding', cascade: ['persist', 'remove'], fetch: 'EXTRA_LAZY')]
    #[ORM\JoinColumn(nullable: true)]
    private ?UserCompany $entity = null;

    public function getEntity(): ?UserCompany
    {
        return $this->entity;
    }

    public function setEntity(UserCompany $entity): static
    {
        $this->entity = $entity;

        return $this;
    }
}
