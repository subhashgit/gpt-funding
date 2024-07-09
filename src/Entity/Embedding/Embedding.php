<?php

namespace App\Entity\Embedding;

use App\Repository\Embedding\EmbeddingRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EmbeddingRepository::class)]
#[ORM\InheritanceType('SINGLE_TABLE')]
#[ORM\DiscriminatorColumn(name: 'type', type: 'string')]
#[ORM\DiscriminatorMap([
    'grant' => GrantEmbedding::class,
    'funding' => FundingEmbedding::class,
    'user_company' => UserCompanyEmbedding::class,
    'user_project' => UserProjectEmbedding::class,
])]
class Embedding
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: 'vector', nullable: true)]
    private $vector;

    #[ORM\Column]
    private ?int $entity_id = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getVector()
    {
        return $this->vector;
    }

    public function setVector($vector): static
    {
        $this->vector = $vector;

        return $this;
    }

    public function getEntityId(): ?int
    {
        return $this->entity_id;
    }

    public function setEntityId(int $entity_id): static
    {
        $this->entity_id = $entity_id;

        return $this;
    }
}
