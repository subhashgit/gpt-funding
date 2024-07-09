<?php

namespace App\Entity\Notification;

use App\Entity\Grant;
use App\Entity\User;
use App\Repository\Notification\UserGrantNotificationRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UserGrantNotificationRepository::class)]
class UserGrantNotification extends UserNotification
{
    #[ORM\ManyToOne(inversedBy: 'userGrantNotifications')]
    private ?Grant $grant = null;

    #[ORM\ManyToOne(inversedBy: 'userGrantNotifications')]
    private ?User $user = null;

    #[ORM\Column]
    private ?int $grant_id = null;

    public function getGrant(): ?Grant
    {
        return $this->grant;
    }

    public function setGrant(?Grant $grant): static
    {
        $this->grant = $grant;

        return $this;
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

    public function getGrantId(): ?int
    {
        return $this->grant_id;
    }

    public function setGrantId(int $grant_id): static
    {
        $this->grant_id = $grant_id;

        return $this;
    }
}
