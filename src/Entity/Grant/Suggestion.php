<?php

namespace App\Entity\Grant;

use App\Entity\Grant;
use App\Entity\User;
use App\Repository\Grant\SuggestionRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Knp\DoctrineBehaviors\Contract\Entity\SluggableInterface;
use Knp\DoctrineBehaviors\Contract\Entity\TimestampableInterface;
use Knp\DoctrineBehaviors\Model\Sluggable\SluggableTrait;
use Knp\DoctrineBehaviors\Model\Timestampable\TimestampableTrait;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: SuggestionRepository::class)]
class Suggestion implements TimestampableInterface, SluggableInterface
{
    use TimestampableTrait;
    use SluggableTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(fetch: 'EXTRA_LAZY', inversedBy: 'suggestions')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Grant $grant = null;

    #[ORM\ManyToOne(fetch: 'EXTRA_LAZY', inversedBy: 'suggestions')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $description = null;

    #[ORM\Column(options: ['default' => 0])]
    private ?int $promptTokens = 0;

    #[ORM\Column(options: ['default' => 0])]
    private ?int $completionTokens = 0;

    #[ORM\Column(options: ['default' => 0])]
    private ?int $totalTokens = 0;

    #[ORM\Column(options: ['default' => 0])]
    private ?int $pointsConsumed = 0;

    #[ORM\Column(options: ['default' => 0])]
    private ?float $estimatedPrice = 0;

    public function __toString(): string
    {
        return 'Suggestion for: '.$this->grant->getTitle();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getPromptTokens(): ?int
    {
        return $this->promptTokens;
    }

    public function setPromptTokens(int $promptTokens): static
    {
        $this->promptTokens = $promptTokens;

        return $this;
    }

    public function getCompletionTokens(): ?int
    {
        return $this->completionTokens;
    }

    public function setCompletionTokens(int $completionTokens): static
    {
        $this->completionTokens = $completionTokens;

        return $this;
    }

    public function getTotalTokens(): ?int
    {
        return $this->totalTokens;
    }

    public function setTotalTokens(int $totalTokens): static
    {
        $this->totalTokens = $totalTokens;

        return $this;
    }

    public function getPointsConsumed(): ?int
    {
        return $this->pointsConsumed;
    }

    public function setPointsConsumed(int $pointsConsumed): static
    {
        $this->pointsConsumed = $pointsConsumed;

        return $this;
    }

    public function getEstimatedPrice(): ?float
    {
        return $this->estimatedPrice;
    }

    public function setEstimatedPrice(float $estimatedPrice): static
    {
        $this->estimatedPrice = $estimatedPrice;

        return $this;
    }

    public function getSluggableFields(): array
    {
        return [];
    }

    public function generateSlugValue($values): string
    {
        return Uuid::v4();
    }
}
