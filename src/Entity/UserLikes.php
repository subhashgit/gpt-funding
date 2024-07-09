<?php

namespace App\Entity;

use App\Repository\UserLikesRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UserLikesRepository::class)]
class UserLikes
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'userLikes')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\ManyToOne(inversedBy: 'userLikes')]
    #[ORM\JoinColumn(nullable: true)]
    private ?Funding $funding = null;

    #[ORM\ManyToOne(inversedBy: 'userLikes')]
    private ?Grant $grant = null;

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

    public function getFunding(): ?Funding
    {
        return $this->funding;
    }

    public function setFunding(?Funding $funding): static
    {
        $this->funding = $funding;

        return $this;
    }

    public function getGrant(): ?Grant
    {
        return $this->grant;
    }

    public function setGrant(?Grant $grant): static
    {
        $this->grant = $grant;

        return $this;
    }
}
