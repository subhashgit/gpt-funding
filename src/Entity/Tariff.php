<?php

namespace App\Entity;

use App\Repository\TariffRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TariffRepository::class)]
class Tariff
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $productId = null;

    #[ORM\OneToMany(mappedBy: 'tariff', targetEntity: User::class)]
    private Collection $user;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $period = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $monthPrice = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $yearPrice = null;

    #[ORM\OneToMany(mappedBy: 'tariff', targetEntity: UserTariff::class)]
    private Collection $userTariffs;

    public function __construct()
    {
        $this->user = new ArrayCollection();
        $this->userTariffs = new ArrayCollection();
    }

    public function __toString(): string
    {
        return $this->title ?? '';
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getProductId(): ?string
    {
        return $this->productId;
    }

    public function setProductId(?string $productId): static
    {
        $this->productId = $productId;

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getUser(): Collection
    {
        return $this->user;
    }

    public function addUser(User $user): static
    {
        if (!$this->user->contains($user)) {
            $this->user->add($user);
            $user->setTariff($this);
        }

        return $this;
    }

    public function removeUser(User $user): static
    {
        if ($this->user->removeElement($user)) {
            // set the owning side to null (unless already changed)
            if ($user->getTariff() === $this) {
                $user->setTariff(null);
            }
        }

        return $this;
    }

    public function getPeriod(): ?string
    {
        return $this->period;
    }

    public function setPeriod(?string $period): static
    {
        $this->period = $period;

        return $this;
    }

    public function getMonthPrice(): ?string
    {
        return $this->monthPrice;
    }

    public function setMonthPrice(?string $monthPrice): static
    {
        $this->monthPrice = $monthPrice;

        return $this;
    }

    public function getYearPrice(): ?string
    {
        return $this->yearPrice;
    }

    public function setYearPrice(?string $yearPrice): static
    {
        $this->yearPrice = $yearPrice;

        return $this;
    }

    /**
     * @return Collection<int, UserTariff>
     */
    public function getUserTariffs(): Collection
    {
        return $this->userTariffs;
    }

    public function addUserTariff(UserTariff $userTariff): static
    {
        if (!$this->userTariffs->contains($userTariff)) {
            $this->userTariffs->add($userTariff);
            $userTariff->setTariff($this);
        }

        return $this;
    }

    public function removeUserTariff(UserTariff $userTariff): static
    {
        if ($this->userTariffs->removeElement($userTariff)) {
            // set the owning side to null (unless already changed)
            if ($userTariff->getTariff() === $this) {
                $userTariff->setTariff(null);
            }
        }

        return $this;
    }
}
