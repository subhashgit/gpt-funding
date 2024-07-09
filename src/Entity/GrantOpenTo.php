<?php

namespace App\Entity;

use App\Repository\GrantOpenToRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: GrantOpenToRepository::class)]
class GrantOpenTo
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 500)]
    private ?string $open_to = null;

    #[ORM\ManyToMany(targetEntity: Grant::class, mappedBy: 'openTo')]
    private Collection $grant;

    #[ORM\Column(options: ['default' => false])]
    private ?bool $show_in_filter = false;

    public function __construct()
    {
        $this->grant = new ArrayCollection();
    }

    public function __toString(): string
    {
        return $this->open_to ?? '';
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getOpenTo(): ?string
    {
        return $this->open_to;
    }

    public function setOpenTo(string $open_to): static
    {
        $this->open_to = $open_to;

        return $this;
    }

    public function isShowInFilter(): ?bool
    {
        return $this->show_in_filter;
    }

    public function setShowInFilter(bool $show_in_filter): static
    {
        $this->show_in_filter = $show_in_filter;

        return $this;
    }

    /**
     * @return Collection<int, Grant>
     */
    public function getGrant(): Collection
    {
        return $this->grant;
    }

    public function addGrant(Grant $grant): static
    {
        if (!$this->grant->contains($grant)) {
            $this->grant->add($grant);
            $grant->addOpenTo($this);
        }

        return $this;
    }

    public function removeGrant(Grant $grant): static
    {
        if ($this->grant->removeElement($grant)) {
            $grant->removeOpenTo($this);
        }

        return $this;
    }
}
