<?php

namespace App\Entity;

use App\Entity\Embedding\UserCompanyEmbedding;
use App\Repository\UserCompanyRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UserCompanyRepository::class)]
class UserCompany
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'companies')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(nullable: true)]
    private ?array $locations = null;

    #[ORM\OneToOne(mappedBy: 'entity', cascade: ['persist', 'remove'])]
    private ?UserCompanyEmbedding $embedding = null;

    #[ORM\Column(nullable: true)]
    private ?array $openTo = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $projectExamples = null;

    public function __construct() {}

    public function __toString(): string
    {
        return $this->description ?? '';
    }

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

    public function getEmbedding(): ?UserCompanyEmbedding
    {
        return $this->embedding;
    }

    public function setEmbedding(UserCompanyEmbedding $embedding): static
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
