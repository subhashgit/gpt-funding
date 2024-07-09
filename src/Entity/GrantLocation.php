<?php

namespace App\Entity;

use App\Repository\GrantLocationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: GrantLocationRepository::class)]
class GrantLocation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $location = null;

    #[ORM\ManyToMany(targetEntity: Grant::class, mappedBy: 'locations')]
    private Collection $grant;

    #[ORM\Column(options: ['default' => false])]
    private ?bool $show_in_filter = false;

    public function __construct()
    {
        $this->grant = new ArrayCollection();
    }

    public function __toString(): string
    {
        return $this->location ?? '';
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLocation(): ?string
    {
        return $this->location;
    }

    public function setLocation(string $location): static
    {
        $this->location = $location;

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
        }

        return $this;
    }

    public function removeGrant(Grant $grant): static
    {
        $this->grant->removeElement($grant);

        return $this;
    }
}
