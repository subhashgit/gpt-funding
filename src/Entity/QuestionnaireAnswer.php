<?php

namespace App\Entity;

use App\Repository\QuestionnaireAnswerRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Knp\DoctrineBehaviors\Contract\Entity\SluggableInterface;
use Knp\DoctrineBehaviors\Contract\Entity\TimestampableInterface;
use Knp\DoctrineBehaviors\Model\Sluggable\SluggableTrait;
use Knp\DoctrineBehaviors\Model\Timestampable\TimestampableTrait;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: QuestionnaireAnswerRepository::class)]
class QuestionnaireAnswer implements TimestampableInterface, SluggableInterface
{
    use TimestampableTrait;
    use SluggableTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\OneToMany(mappedBy: 'questionnaireAnswer', targetEntity: Answer::class, cascade: ['remove'])]
    #[ORM\OrderBy(['id' => 'ASC'])]
    private Collection $answer;

    #[ORM\ManyToOne(inversedBy: 'questionnaireAnswers')]
    #[ORM\JoinColumn(nullable: true)]
    private ?User $user = null;

    #[ORM\ManyToOne(inversedBy: 'questionnaireAnswers')]
    private ?Grant $grant = null;

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

    #[ORM\Column(type: Types::JSON, nullable: true)]
    private ?array $postcodes = null;

    public function __construct()
    {
        $this->answer = new ArrayCollection();
    }

    public function __toString(): string
    {
        return $this->id ?? '';
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSluggableFields(): array
    {
        return ['id'];
    }

    public function generateSlugValue($values): string
    {
        return Uuid::v4();
    }

    /**
     * @return Collection<int, Answer>
     */
    public function getAnswer(): Collection
    {
        return $this->answer;
    }

    public function addAnswer(Answer $answer): self
    {
        if (!$this->answer->contains($answer)) {
            $this->answer->add($answer);
            $answer->setQuestionnaireAnswer($this);
        }

        return $this;
    }

    public function removeAnswer(Answer $answer): self
    {
        if ($this->answer->removeElement($answer)) {
            // set the owning side to null (unless already changed)
            if ($answer->getQuestionnaireAnswer() === $this) {
                $answer->setQuestionnaireAnswer(null);
            }
        }

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

    public function getGrant(): ?Grant
    {
        return $this->grant;
    }

    public function setGrant(?Grant $grant): static
    {
        $this->grant = $grant;

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

    public function getPostcodes(): ?array
    {
        return $this->postcodes;
    }

    public function setPostcodes(?array $postcodes): static
    {
        $this->postcodes = $postcodes;

        return $this;
    }
}
