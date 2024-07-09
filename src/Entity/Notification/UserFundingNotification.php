<?php

namespace App\Entity\Notification;

use App\Entity\Funding;
use App\Entity\User;
use App\Repository\Notification\UserFundingNotificationRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UserFundingNotificationRepository::class)]
class UserFundingNotification extends UserNotification
{
    #[ORM\ManyToOne(inversedBy: 'userFundingNotifications')]
    private ?Funding $funding = null;

    #[ORM\ManyToOne(inversedBy: 'userFundingNotifications')]
    private ?User $user = null;

    #[ORM\Column]
    private ?int $funding_id = null;

    public function getFunding(): ?Funding
    {
        return $this->funding;
    }

    public function setFunding(?Funding $funding): static
    {
        $this->funding = $funding;

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

    public function getFundingId(): ?int
    {
        return $this->funding_id;
    }

    public function setFundingId(int $funding_id): static
    {
        $this->funding_id = $funding_id;

        return $this;
    }
}
