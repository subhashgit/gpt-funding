<?php

namespace App\Entity;

use App\Entity\Embedding\FundingEmbedding;
use App\Entity\Notification\UserFundingNotification;
use App\Enum\Status;
use App\Enum\Tag;
use App\Repository\FundingRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Knp\DoctrineBehaviors\Contract\Entity\SluggableInterface;
use Knp\DoctrineBehaviors\Contract\Entity\TimestampableInterface;
use Knp\DoctrineBehaviors\Model\Sluggable\SluggableTrait;
use Knp\DoctrineBehaviors\Model\Timestampable\TimestampableTrait;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[ORM\Entity(repositoryClass: FundingRepository::class)]
#[UniqueEntity(fields: ['identifier'])]
#[ORM\Index(columns: ['status'])]
class Funding implements TimestampableInterface, SluggableInterface
{
    use TimestampableTrait;
    use SluggableTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, unique: true)]
    private ?string $notice_identifier = null;

    #[ORM\Column(length: 255)]
    private ?string $notice_type = null;

    #[ORM\Column(length: 255)]
    private ?string $organisation_name = null;

    #[ORM\Column(type: Types::STRING, length: 255, enumType: Status::class)]
    private ?Status $status = null;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE, nullable: true)]
    private ?\DateTimeInterface $published_date = null;

    #[ORM\Column(length: 1000)]
    private ?string $title = 'No Title';

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $postcode = null;

    #[ORM\Column(type: Types::JSON, nullable: true)]
    private ?array $links = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $additional_text = null;

    #[ORM\Column(type: Types::DATE_IMMUTABLE, nullable: true)]
    private ?\DateTimeInterface $start_date = null;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE, nullable: true)]
    private ?\DateTimeInterface $end_date = null;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE, nullable: true)]
    private ?\DateTimeInterface $closing_date = null;

    #[ORM\Column(nullable: true)]
    private ?bool $suitable_for_sme = null;

    #[ORM\Column(nullable: true)]
    private ?bool $suitable_for_vco = null;

    #[ORM\Column]
    private ?bool $supply_chain = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $ojeu_contract_type = null;

    #[ORM\Column(type: Types::BIGINT, nullable: true)]
    private ?int $value_low = null;

    #[ORM\Column(type: Types::BIGINT, nullable: true)]
    private ?int $value_high = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $ojeu_procedure_type = null;

    #[ORM\ManyToMany(targetEntity: CPVCode::class, inversedBy: 'fundings', fetch: 'EXTRA_LAZY')]
    private Collection $cpv;

    #[ORM\Column(length: 1000, nullable: true)]
    private ?string $external_link = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $external_id = null;

    #[ORM\Column(nullable: true)]
    private ?array $downloads = [];

    #[ORM\ManyToMany(targetEntity: FundingRegion::class, mappedBy: 'funding', fetch: 'EXTRA_LAZY')]
    private Collection $fundingRegions;

    #[ORM\OneToMany(mappedBy: 'funding', targetEntity: UserFundingNotification::class)]
    private Collection $userFundingNotifications;

    #[ORM\Column(length: 1000, nullable: true)]
    protected $slug;

    /**
     * @var string[]|null
     */
    #[ORM\Column(type: 'json', length: 255, nullable: true)]
    private ?array $submission_method = null;

    #[ORM\Column(length: 2000, nullable: true)]
    private ?string $submission_method_details = null;

    /**
     * @var Tag[]|null
     */
    #[ORM\Column(type: 'jsonb', nullable: true)]
    private ?array $tags = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $procurement_method = null;

    #[ORM\Column(length: 500, nullable: true)]
    private ?string $procurement_method_details = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $main_procurement_category = null;

    #[ORM\ManyToMany(targetEntity: Party::class, inversedBy: 'fundings', cascade: ['persist'], fetch: 'EXTRA_LAZY')]
    private Collection $parties;

    #[ORM\OneToMany(mappedBy: 'funding', targetEntity: Lot::class, cascade: ['persist'], fetch: 'EXTRA_LAZY')]
    private Collection $lots;

    #[ORM\OneToMany(mappedBy: 'funding', targetEntity: UserLikes::class, cascade: ['persist'], fetch: 'EXTRA_LAZY')]
    private Collection $userLikes;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $ocid = null;

    #[ORM\OneToOne(mappedBy: 'entity', fetch: 'EXTRA_LAZY')]
    private ?FundingEmbedding $embedding = null;

    public function __construct()
    {
        $this->cpv = new ArrayCollection();
        $this->fundingRegions = new ArrayCollection();
        $this->userFundingNotifications = new ArrayCollection();
        $this->parties = new ArrayCollection();
        $this->lots = new ArrayCollection();
        $this->userLikes = new ArrayCollection();
    }

    public function __toString(): string
    {
        return $this->title ?? '';
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNoticeIdentifier(): ?string
    {
        return $this->notice_identifier;
    }

    public function setNoticeIdentifier(string $notice_identifier): static
    {
        $this->notice_identifier = $notice_identifier;

        return $this;
    }

    public function getNoticeType(): ?string
    {
        return $this->notice_type;
    }

    public function setNoticeType(string $notice_type): static
    {
        $this->notice_type = $notice_type;

        return $this;
    }

    public function getOrganisationName(): ?string
    {
        return $this->organisation_name;
    }

    public function setOrganisationName(string $organisation_name): static
    {
        $this->organisation_name = $organisation_name;

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

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(?string $title): static
    {
        $this->title = $title;

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

    public function getPostcode(): ?string
    {
        return $this->postcode;
    }

    public function setPostcode(?string $postcode): static
    {
        $this->postcode = $postcode;

        return $this;
    }

    public function getLinks(): ?array
    {
        return $this->links;
    }

    public function setLinks(?array $links): static
    {
        $this->links = $links;

        return $this;
    }

    public function getAdditionalText(): ?string
    {
        return $this->additional_text;
    }

    public function setAdditionalText(?string $additional_text): static
    {
        $this->additional_text = $additional_text;

        return $this;
    }

    public function getStartDate(): ?\DateTimeInterface
    {
        return $this->start_date;
    }

    public function setStartDate(?\DateTimeInterface $start_date): static
    {
        $this->start_date = $start_date;

        return $this;
    }

    public function getEndDate(): ?\DateTimeInterface
    {
        return $this->end_date;
    }

    public function setEndDate(?\DateTimeInterface $end_date): static
    {
        $this->end_date = $end_date;

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

    public function isSuitableForSme(): ?bool
    {
        return $this->suitable_for_sme;
    }

    public function setSuitableForSme(?bool $suitable_for_sme): static
    {
        $this->suitable_for_sme = $suitable_for_sme;

        return $this;
    }

    public function isSuitableForVco(): ?bool
    {
        return $this->suitable_for_vco;
    }

    public function setSuitableForVco(?bool $suitable_for_vco): static
    {
        $this->suitable_for_vco = $suitable_for_vco;

        return $this;
    }

    public function isSupplyChain(): ?bool
    {
        return $this->supply_chain;
    }

    public function setSupplyChain(bool $supply_chain): static
    {
        $this->supply_chain = $supply_chain;

        return $this;
    }

    public function getOjeuContractType(): ?string
    {
        return $this->ojeu_contract_type;
    }

    public function setOjeuContractType(?string $ojeu_contract_type): static
    {
        $this->ojeu_contract_type = $ojeu_contract_type;

        return $this;
    }

    public function getValueLow(): ?int
    {
        return $this->value_low;
    }

    public function setValueLow(?int $value_low): static
    {
        $this->value_low = $value_low;

        return $this;
    }

    public function getValueHigh(): ?int
    {
        return $this->value_high;
    }

    public function setValueHigh(?int $value_high): static
    {
        $this->value_high = $value_high;

        return $this;
    }

    public function getOjeuProcedureType(): ?string
    {
        return $this->ojeu_procedure_type;
    }

    public function setOjeuProcedureType(?string $ojeu_procedure_type): static
    {
        $this->ojeu_procedure_type = $ojeu_procedure_type;

        return $this;
    }

    public function getStatus(): ?Status
    {
        return $this->status;
    }

    public function setStatus(?Status $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function getSluggableFields(): array
    {
        return ['title', 'external_id'];
    }

    /**
     * @return Collection<int, CPVCode>
     */
    public function getCpv(): Collection
    {
        return $this->cpv;
    }

    public function addCpv(CPVCode $cpv): static
    {
        if (!$this->cpv->contains($cpv)) {
            $this->cpv->add($cpv);
        }

        return $this;
    }

    public function removeCpv(CPVCode $cpv): static
    {
        $this->cpv->removeElement($cpv);

        return $this;
    }

    public function getExternalLink(): ?string
    {
        return $this->external_link;
    }

    public function setExternalLink(?string $external_link): static
    {
        $this->external_link = $external_link;

        return $this;
    }

    public function getExternalId(): ?string
    {
        return $this->external_id;
    }

    public function setExternalId(?string $external_id): static
    {
        $this->external_id = $external_id;

        return $this;
    }

    public function getDownloads(): ?array
    {
        return $this->downloads;
    }

    public function setDownloads(?array $downloads): static
    {
        $this->downloads = $downloads;

        return $this;
    }

    /**
     * @return Collection<int, FundingRegion>
     */
    public function getFundingRegions(): Collection
    {
        return $this->fundingRegions;
    }

    public function addFundingRegion(FundingRegion $fundingRegion): static
    {
        if (!$this->fundingRegions->contains($fundingRegion)) {
            $this->fundingRegions->add($fundingRegion);
            $fundingRegion->addFunding($this);
        }

        return $this;
    }

    public function removeFundingRegion(FundingRegion $fundingRegion): static
    {
        if ($this->fundingRegions->removeElement($fundingRegion)) {
            $fundingRegion->removeFunding($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, UserFundingNotification>
     */
    public function getUserFundingNotifications(): Collection
    {
        return $this->userFundingNotifications;
    }

    public function addUserFundingNotification(UserFundingNotification $userFundingNotification): static
    {
        if (!$this->userFundingNotifications->contains($userFundingNotification)) {
            $this->userFundingNotifications->add($userFundingNotification);
            $userFundingNotification->setFunding($this);
        }

        return $this;
    }

    public function removeUserFundingNotification(UserFundingNotification $userFundingNotification): static
    {
        if ($this->userFundingNotifications->removeElement($userFundingNotification)) {
            // set the owning side to null (unless already changed)
            if ($userFundingNotification->getFunding() === $this) {
                $userFundingNotification->setFunding(null);
            }
        }

        return $this;
    }

    public function getSubmissionMethod(): ?array
    {
        return $this->submission_method;
    }

    public function setSubmissionMethod(?array $submission_method): static
    {
        $this->submission_method = $submission_method;

        return $this;
    }

    public function getSubmissionMethodDetails(): ?string
    {
        return $this->submission_method_details;
    }

    public function setSubmissionMethodDetails(?string $submission_method_details): static
    {
        $this->submission_method_details = $submission_method_details;

        return $this;
    }

    public function getTags(): ?array
    {
        return $this->tags;
    }

    public function setTags(?array $tags): static
    {
        $this->tags = $tags;

        return $this;
    }

    public function getProcurementMethod(): ?string
    {
        return $this->procurement_method;
    }

    public function setProcurementMethod(?string $procurement_method): static
    {
        $this->procurement_method = $procurement_method;

        return $this;
    }

    public function getProcurementMethodDetails(): ?string
    {
        return $this->procurement_method_details;
    }

    public function setProcurementMethodDetails(?string $procurement_method_details): static
    {
        $this->procurement_method_details = $procurement_method_details;

        return $this;
    }

    public function getMainProcurementCategory(): ?string
    {
        return $this->main_procurement_category;
    }

    public function setMainProcurementCategory(?string $main_procurement_category): static
    {
        $this->main_procurement_category = $main_procurement_category;

        return $this;
    }

    /**
     * @return Collection<int, Party>
     */
    public function getParties(): Collection
    {
        return $this->parties;
    }

    public function addParty(Party $party): static
    {
        if (!$this->parties->contains($party)) {
            $this->parties->add($party);
        }

        return $this;
    }

    public function removeParty(Party $party): static
    {
        $this->parties->removeElement($party);

        return $this;
    }

    /**
     * @return Collection<int, Lot>
     */
    public function getLots(): Collection
    {
        return $this->lots;
    }

    public function addLot(Lot $lot): static
    {
        if (!$this->lots->contains($lot)) {
            $this->lots->add($lot);
            $lot->setFunding($this);
        }

        return $this;
    }

    public function removeLot(Lot $lot): static
    {
        if ($this->lots->removeElement($lot)) {
            // set the owning side to null (unless already changed)
            if ($lot->getFunding() === $this) {
                $lot->setFunding(null);
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
            $userLike->setFunding($this);
        }

        return $this;
    }

    public function removeUserLike(UserLikes $userLike): static
    {
        if ($this->userLikes->removeElement($userLike)) {
            // set the owning side to null (unless already changed)
            if ($userLike->getFunding() === $this) {
                $userLike->setFunding(null);
            }
        }

        return $this;
    }

    public function getOcid(): ?string
    {
        return $this->ocid;
    }

    public function setOcid(?string $ocid): static
    {
        $this->ocid = $ocid;

        return $this;
    }

    public function getEmbedding(): ?FundingEmbedding
    {
        return $this->embedding;
    }

    public function setEmbedding(FundingEmbedding $embedding): static
    {
        // set the owning side of the relation if necessary
        if ($embedding->getEntity() !== $this) {
            $embedding->setEntity($this);
        }

        $this->embedding = $embedding;

        return $this;
    }

    public function indexable(): bool
    {
        return null === $this->closing_date || $this->closing_date >= new \DateTimeImmutable();
    }
}
