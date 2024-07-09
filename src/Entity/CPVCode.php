<?php

namespace App\Entity;

use App\Repository\CPVCodeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CPVCodeRepository::class)]
class CPVCode
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: 'string', length: 15)]
    private ?string $code = null;

    #[ORM\Column(length: 255)]
    private ?string $description = null;

    #[ORM\ManyToMany(targetEntity: Funding::class, mappedBy: 'cpv')]
    private Collection $fundings;

    public function __construct()
    {
        $this->fundings = new ArrayCollection();
    }

    public function __toString(): string
    {
        return $this->description ?? '';
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(string $code): static
    {
        $this->code = $code;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return Collection<int, Funding>
     */
    public function getFundings(): Collection
    {
        return $this->fundings;
    }

    public function addFunding(Funding $funding): static
    {
        if (!$this->fundings->contains($funding)) {
            $this->fundings->add($funding);
            $funding->addCpv($this);
        }

        return $this;
    }

    public function removeFunding(Funding $funding): static
    {
        if ($this->fundings->removeElement($funding)) {
            $funding->removeCpv($this);
        }

        return $this;
    }
}
