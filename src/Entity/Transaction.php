<?php

namespace App\Entity;

use App\Repository\TransactionRepository;
use Doctrine\ORM\Mapping as ORM;
use Knp\DoctrineBehaviors\Contract\Entity\TimestampableInterface;
use Knp\DoctrineBehaviors\Model\Timestampable\TimestampableTrait;

#[ORM\Entity(repositoryClass: TransactionRepository::class)]
class Transaction implements TimestampableInterface
{
    use TimestampableTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'transactions')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\Column(length: 255)]
    private ?string $sessionId = null;

    #[ORM\Column]
    private ?int $amountTotal = null;

    #[ORM\Column]
    private ?int $amountSubtotal = null;

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

    public function getSessionId(): ?string
    {
        return $this->sessionId;
    }

    public function setSessionId(string $sessionId): static
    {
        $this->sessionId = $sessionId;

        return $this;
    }

    public function getAmountTotal(): ?int
    {
        return $this->amountTotal;
    }

    public function setAmountTotal(int $amountTotal): static
    {
        $this->amountTotal = $amountTotal;

        return $this;
    }

    public function getAmountSubtotal(): ?int
    {
        return $this->amountSubtotal;
    }

    public function setAmountSubtotal(int $amountSubtotal): static
    {
        $this->amountSubtotal = $amountSubtotal;

        return $this;
    }
}
