<?php

namespace App\Entity;

use App\Repository\FundingRegionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FundingRegionRepository::class)]
class FundingRegion
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToMany(targetEntity: Funding::class, inversedBy: 'fundingRegions', cascade: ['persist'], fetch: 'EXTRA_LAZY')]
    private Collection $funding;

    #[ORM\Column(length: 255)]
    private ?string $region = null;

    #[ORM\Column(options: ['default' => false])]
    private ?bool $show_in_filter = false;

    public function __construct()
    {
        $this->funding = new ArrayCollection();
    }

    public function __toString(): string
    {
        return $this->region ?? '';
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection<int, Funding>
     */
    public function getFunding(): Collection
    {
        return $this->funding;
    }

    public function addFunding(Funding $funding): static
    {
        if (!$this->funding->contains($funding)) {
            $this->funding->add($funding);
        }

        return $this;
    }

    public function removeFunding(Funding $funding): static
    {
        $this->funding->removeElement($funding);

        return $this;
    }

    public function getRegion(): ?string
    {
        return $this->region;
    }

    public function setRegion(string $region): static
    {
        $this->region = $region;

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
}
