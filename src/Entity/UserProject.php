<?php

namespace App\Entity;

use App\Entity\Embedding\UserProjectEmbedding;
use App\Repository\UserProjectRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UserProjectRepository::class)]
class UserProject
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'projects')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(nullable: true)]
    private ?array $locations = null;

    #[ORM\OneToOne(mappedBy: 'entity', cascade: ['persist', 'remove'])]
    private ?UserProjectEmbedding $embedding = null;

    #[ORM\Column(nullable: true)]
    private ?array $openTo = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $projectExamples = null;

    public function __construct() {}

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getLocations(): ?array
    {
        return $this->locations;
    }

    public function setLocations(?array $locations): static
    {
        $this->locations = $locations;

        return $this;
    }

    public function getEmbedding(): ?UserProjectEmbedding
    {
        return $this->embedding;
    }

    public function setEmbedding(UserProjectEmbedding $embedding): static
    {
        // set the owning side of the relation if necessary
        if ($embedding->getEntity() !== $this) {
            $embedding->setEntity($this);
        }

        $this->embedding = $embedding;

        return $this;
    }

    public function getOpenTo(): ?array
    {
        return $this->openTo;
    }

    public function setOpenTo(?array $openTo): static
    {
        $this->openTo = $openTo;

        return $this;
    }

    public function getProjectExamples(): ?string
    {
        return $this->projectExamples;
    }

    public function setProjectExamples(?string $projectExamples): static
    {
        $this->projectExamples = $projectExamples;

        return $this;
    }
}
