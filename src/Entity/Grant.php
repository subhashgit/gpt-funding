<?php

namespace App\Entity;

use App\Entity\Embedding\GrantEmbedding;
use App\Entity\Grant\Suggestion;
use App\Entity\Notification\UserGrantNotification;
use App\Enum\Status;
use App\Repository\GrantRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Knp\DoctrineBehaviors\Contract\Entity\SluggableInterface;
use Knp\DoctrineBehaviors\Contract\Entity\TimestampableInterface;
use Knp\DoctrineBehaviors\Model\Sluggable\SluggableTrait;
use Knp\DoctrineBehaviors\Model\Timestampable\TimestampableTrait;

#[ORM\Entity(repositoryClass: GrantRepository::class)]
#[ORM\Table(name: '`grant`')]
class Grant implements TimestampableInterface, SluggableInterface
{
    use TimestampableTrait;
    use SluggableTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 500)]
    private ?string $title = null;

    #[ORM\Column(length: 500, nullable: true)]
    private ?string $funder = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(nullable: true)]
    private ?int $max_amount = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $how_to_apply = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTime $closing_date = null;

    #[ORM\ManyToMany(targetEntity: GrantLocation::class, inversedBy: 'grant', cascade: ['persist'])]
    private Collection $locations;

    #[ORM\ManyToMany(targetEntity: GrantCategory::class, inversedBy: 'grant', cascade: ['persist'])]
    private Collection $categories;

    #[ORM\ManyToMany(targetEntity: GrantOpenTo::class, inversedBy: 'grant', cascade: ['persist'])]
    private Collection $openTo;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $fundingDetails = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $whoCanApply = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $findOutMore = null;

    #[ORM\OneToMany(mappedBy: 'grant', targetEntity: UserGrantNotification::class, cascade: ['persist', 'remove'])]
    private Collection $userGrantNotifications;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $published_date = null;

    #[ORM\OneToMany(mappedBy: 'grant', targetEntity: QuestionnaireAnswer::class, cascade: ['persist', 'remove'])]
    private Collection $questionnaireAnswers;

    #[ORM\Column(type: Types::STRING, length: 255, enumType: Status::class, options: ['default' => Status::OPEN])]
    private ?Status $status = Status::OPEN;

    #[ORM\Column(length: 500, nullable: true)]
    protected $slug;

    #[ORM\OneToMany(mappedBy: 'grant', targetEntity: UserLikes::class, cascade: ['persist', 'remove'])]
    private Collection $userLikes;

    #[ORM\OneToOne(mappedBy: 'entity', cascade: ['persist', 'remove'], fetch: 'LAZY')]
    private ?GrantEmbedding $embedding = null;

    #[ORM\OneToMany(mappedBy: 'grant', targetEntity: Suggestion::class, cascade: ['persist', 'remove'])]
    private Collection $suggestions;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $guidanceDocuments = null;

    public function __construct()
    {
        $this->locations = new ArrayCollection();
        $this->categories = new ArrayCollection();
        $this->openTo = new ArrayCollection();
        $this->userGrantNotifications = new ArrayCollection();
        $this->questionnaireAnswers = new ArrayCollection();
        $this->userLikes = new ArrayCollection();
        $this->suggestions = new ArrayCollection();
        $this->published_date = new \DateTime();
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

    public function getFunder(): ?string
    {
        return $this->funder;
    }

    public function setFunder(?string $funder): static
    {
        $this->funder = $funder;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getMaxAmount(): ?int
    {
        return $this->max_amount;
    }

    public function setMaxAmount(?int $max_amount): static
    {
        $this->max_amount = $max_amount;

        return $this;
    }

    public function getHowToApply(): ?string
    {
        return $this->how_to_apply;
    }

    public function setHowToApply(?string $how_to_apply): static
    {
        $this->how_to_apply = $how_to_apply;

        return $this;
    }

    public function getClosingDate(): ?\DateTimeInterface
    {
        return $this->closing_date;
    }

    public function setClosingDate(?\DateTimeInterface $closing_date): static
    {
        $this->closing_date = $closing_date;

        return $this;
    }

    public function getSluggableFields(): array
    {
        return ['id', 'title'];
    }

    public function getFundingDetails(): ?string
    {
        return $this->fundingDetails;
    }

    public function setFundingDetails(?string $fundingDetails): static
    {
        $this->fundingDetails = $fundingDetails;

        return $this;
    }

    public function getWhoCanApply(): ?string
    {
        return $this->whoCanApply;
    }

    public function setWhoCanApply(?string $whoCanApply): static
    {
        $this->whoCanApply = $whoCanApply;

        return $this;
    }

    public function getFindOutMore(): ?string
    {
        return $this->findOutMore;
    }

    public function setFindOutMore(?string $findOutMore): static
    {
        $this->findOutMore = $findOutMore;

        return $this;
    }

    public function getPublishedDate(): ?\DateTimeInterface
    {
        return $this->published_date;
    }

    public function setPublishedDate(?\DateTimeInterface $published_date): static
    {
        $this->published_date = $published_date;

        return $this;
    }

    public function getStatus(): ?Status
    {
        return $this->status;
    }

    public function setStatus(Status $status): static
    {
        $this->status = $status;

        return $this;
    }

    /**
     * @return Collection<int, GrantLocation>
     */
    public function getLocations(): Collection
    {
        return $this->locations;
    }

    public function addLocation(GrantLocation $location): static
    {
        if (!$this->locations->contains($location)) {
            $this->locations->add($location);
            $location->addGrant($this);
        }

        return $this;
    }

    public function removeLocation(GrantLocation $location): static
    {
        if ($this->locations->removeElement($location)) {
            $location->removeGrant($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, GrantCategory>
     */
    public function getCategories(): Collection
    {
        return $this->categories;
    }

    public function addCategory(GrantCategory $category): static
    {
        if (!$this->categories->contains($category)) {
            $this->categories->add($category);
            $category->addGrant($this);
        }

        return $this;
    }

    public function removeCategory(GrantCategory $category): static
    {
        if ($this->categories->removeElement($category)) {
            $category->removeGrant($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, GrantOpenTo>
     */
    public function getOpenTo(): Collection
    {
        return $this->openTo;
    }

    public function addOpenTo(GrantOpenTo $openTo): static
    {
        if (!$this->openTo->contains($openTo)) {
            $this->openTo->add($openTo);
        }

        return $this;
    }

    public function removeOpenTo(GrantOpenTo $openTo): static
    {
        $this->openTo->removeElement($openTo);

        return $this;
    }

    /**
     * @return Collection<int, UserGrantNotification>
     */
    public function getUserGrantNotifications(): Collection
    {
        return $this->userGrantNotifications;
    }

    public function addUserGrantNotification(UserGrantNotification $userGrantNotification): static
    {
        if (!$this->userGrantNotifications->contains($userGrantNotification)) {
            $this->userGrantNotifications->add($userGrantNotification);
            $userGrantNotification->setGrant($this);
        }

        return $this;
    }

    public function removeUserGrantNotification(UserGrantNotification $userGrantNotification): static
    {
        if ($this->userGrantNotifications->removeElement($userGrantNotification)) {
            // set the owning side to null (unless already changed)
            if ($userGrantNotification->getGrant() === $this) {
                $userGrantNotification->setGrant(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, QuestionnaireAnswer>
     */
    public function getQuestionnaireAnswers(): Collection
    {
        return $this->questionnaireAnswers;
    }

    public function addQuestionnaireAnswer(QuestionnaireAnswer $questionnaireAnswer): static
    {
        if (!$this->questionnaireAnswers->contains($questionnaireAnswer)) {
            $this->questionnaireAnswers->add($questionnaireAnswer);
            $questionnaireAnswer->setGrant($this);
        }

        return $this;
    }

    public function removeQuestionnaireAnswer(QuestionnaireAnswer $questionnaireAnswer): static
    {
        if ($this->questionnaireAnswers->removeElement($questionnaireAnswer)) {
            // set the owning side to null (unless already changed)
            if ($questionnaireAnswer->getGrant() === $this) {
                $questionnaireAnswer->setGrant(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, UserLikes>
     */
    public function getUserLikes(): Collection
    {
        return $this->userLikes;
    }

    public function addUserLike(UserLikes $userLike): static
    {
        if (!$this->userLikes->contains($userLike)) {
            $this->userLikes->add($userLike);
            $userLike->setGrant($this);
        }

        return $this;
    }

    public function removeUserLike(UserLikes $userLike): static
    {
        if ($this->userLikes->removeElement($userLike)) {
            // set the owning side to null (unless already changed)
            if ($userLike->getGrant() === $this) {
                $userLike->setGrant(null);
            }
        }

        return $this;
    }

    public function getEmbedding(): ?GrantEmbedding
    {
        return $this->embedding;
    }

    public function setEmbedding(?GrantEmbedding $embedding): static
    {
        // unset the owning side of the relation if necessary
        if (null === $embedding && null !== $this->embedding) {
            $this->embedding->setEntity(null);
        }

        // set the owning side of the relation if necessary
        if (null !== $embedding && $embedding->getEntity() !== $this) {
            $embedding->setEntity($this);
        }

        $this->embedding = $embedding;

        return $this;
    }

    /**
     * @return Collection<int, Suggestion>
     */
    public function getSuggestions(): Collection
    {
        return $this->suggestions;
    }

    public function addSuggestion(Suggestion $suggestion): static
    {
        if (!$this->suggestions->contains($suggestion)) {
            $this->suggestions->add($suggestion);
            $suggestion->setGrant($this);
        }

        return $this;
    }

    public function removeSuggestion(Suggestion $suggestion): static
    {
        if ($this->suggestions->removeElement($suggestion)) {
            // set the owning side to null (unless already changed)
            if ($suggestion->getGrant() === $this) {
                $suggestion->setGrant(null);
            }
        }

        return $this;
    }

    public function getGuidanceDocuments(): ?string
    {
        return $this->guidanceDocuments;
    }

    public function setGuidanceDocuments(?string $guidanceDocuments): static
    {
        $this->guidanceDocuments = $guidanceDocuments;

        return $this;
    }
}
