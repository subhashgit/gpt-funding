<?php

namespace App\Entity;

use App\Repository\PartyRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PartyRepository::class)]
class Party
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 500)]
    private ?string $name = null;

    #[ORM\Column(type: 'jsonb', nullable: true)]
    private $address;

    #[ORM\Column(type: 'jsonb', nullable: true)]
    private $contact_point;

    /**
     * @var string[]|null
     */
    #[ORM\Column(type: 'jsonb', nullable: true)]
    private ?array $roles = null;

    #[ORM\Column(length: 255)]
    private ?string $external_id = null;

    #[ORM\Column(type: 'jsonb', nullable: true)]
    private $details;

    #[ORM\ManyToMany(targetEntity: Funding::class, mappedBy: 'parties')]
    private Collection $fundings;

    public function __construct()
    {
        $this->fundings = new ArrayCollection();
    }

    public function __toString(): string
    {
        return $this->name ?? '';
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getAddress()
    {
        return $this->address;
    }

    public function setAddress($address): static
    {
        $this->address = $address;

        return $this;
    }

    public function getContactPoint()
    {
        return $this->contact_point;
    }

    public function setContactPoint($contact_point): static
    {
        $this->contact_point = $contact_point;

        return $this;
    }

    public function getRoles(): ?array
    {
        return $this->roles;
    }

    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    public function getFunding(): ?Funding
    {
        return $this->funding;
    }

    public function setFunding(?Funding $funding): static
    {
        $this->funding = $funding;

        return $this;
    }

    public function getExternalId(): ?string
    {
        return $this->external_id;
    }

    public function setExternalId(string $external_id): static
    {
        $this->external_id = $external_id;

        return $this;
    }

    public function getDetails()
    {
        return $this->details;
    }

    public function setDetails($details): static
    {
        $this->details = $details;

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
            $funding->addParty($this);
        }

        return $this;
    }

    public function removeFunding(Funding $funding): static
    {
        if ($this->fundings->removeElement($funding)) {
            $funding->removeParty($this);
        }

        return $this;
    }
}
