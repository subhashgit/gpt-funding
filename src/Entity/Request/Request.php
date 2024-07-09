<?php

namespace App\Entity\Request;

use App\Entity\User;
use App\Enum\RequestStatus;
use App\Repository\Request\RequestRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Knp\DoctrineBehaviors\Contract\Entity\TimestampableInterface;
use Knp\DoctrineBehaviors\Model\Timestampable\TimestampableTrait;

#[ORM\Entity(repositoryClass: RequestRepository::class)]
#[ORM\InheritanceType('SINGLE_TABLE')]
#[ORM\DiscriminatorColumn(name: 'discr', type: 'string', length: 20)]
#[ORM\DiscriminatorMap([
    'grant' => GrantRequest::class,
    'funding' => FundingRequest::class,
])]
class Request implements TimestampableInterface
{
    use TimestampableTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'requests')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\Column(length: 50, enumType: RequestStatus::class, options: ['default' => RequestStatus::QUEUED])]
    private ?RequestStatus $status = RequestStatus::QUEUED;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $text = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $projectOverview = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $budgetBreakdown = null;

    // #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    // private ?string $dateRequested = null;

    // #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    // private ?string $dateSubmitted = null;

    // #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    // private ?string $expectedDecision = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $dateRequested = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $dateSubmitted = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $expectedDecision = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $value = null;

    public function __toString(): string
    {
        return $this->id ? (string) $this->id : 'New';
    }

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

    public function getStatus(): ?RequestStatus
    {
        return $this->status;
    }

    public function setStatus(RequestStatus $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function getText(): ?string
    {
        return $this->text;
    }

    public function setText(?string $text): static
    {
        $this->text = $text;

        return $this;
    }

    public function getProjectOverview(): ?string
    {
        return $this->projectOverview;
    }

    public function setProjectOverview(?string $projectOverview): static
    {
        $this->projectOverview = $projectOverview;

        return $this;
    }

    public function getBudgetBreakdown(): ?string
    {
        return $this->budgetBreakdown;
    }

    public function setBudgetBreakdown(?string $budgetBreakdown): static
    {
        $this->budgetBreakdown = $budgetBreakdown;

        return $this;
    }

    public function getDateRequested(): ?\DateTimeImmutable
    {
        return $this->dateRequested;
    }

    public function setDateRequested(?\DateTimeImmutable $dateRequested): static
    {
        $this->dateRequested = $dateRequested;

        return $this;
    }


    public function getDateSubmitted(): ?\DateTimeImmutable
    {
        return $this->dateSubmitted;
    }

    public function setDateSubmitted(?\DateTimeImmutable $dateSubmitted): static
    {
        $this->dateSubmitted = $dateSubmitted;

        return $this;
    }
    
    public function getExpectedDecision(): ?\DateTimeImmutable
    {
        return $this->expectedDecision;
    }

    public function setExpectedDecision(?\DateTimeImmutable $expectedDecision): static
    {
        $this->expectedDecision = $expectedDecision;

        return $this;
    }
    
    public function getValue(): ?string
    {
        return $this->value;
    }

    public function setValue(?string $value): static
    {
        $this->value = $value;

        return $this;
    }

}
