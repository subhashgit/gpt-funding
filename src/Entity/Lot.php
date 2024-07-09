<?php

namespace App\Entity;

use App\Model\API\FindTender\AwardCriteria;
use App\Model\API\FindTender\ContractPeriod;
use App\Model\API\FindTender\Renewal;
use App\Model\API\FindTender\SubmissionTerms;
use App\Repository\LotRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: LotRepository::class)]
class Lot
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(nullable: true)]
    private ?float $value = null;

    #[ORM\ManyToOne(inversedBy: 'lots')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Funding $funding = null;

    #[ORM\Column(nullable: true)]
    private ?bool $has_options = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $status = null;

    /**
     * @var ContractPeriod|null
     */
    #[ORM\Column(type: 'jsonb', nullable: true)]
    private $contract_period;

    /**
     * @var SubmissionTerms|null
     */
    #[ORM\Column(type: 'jsonb', nullable: true)]
    private $submission_terms;

    /**
     * @var AwardCriteria|null
     */
    #[ORM\Column(type: 'json', nullable: true)]
    private $award_criteria;

    #[ORM\Column(nullable: true)]
    private ?bool $has_renewal = null;

    /**
     * @var Renewal|null
     */
    #[ORM\Column(type: 'json', nullable: true)]
    private $renewal;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getValue(): ?float
    {
        return $this->value;
    }

    public function setValue(?float $value): static
    {
        $this->value = $value;

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

    public function isHasOptions(): ?bool
    {
        return $this->has_options;
    }

    public function setHasOptions(?bool $has_options): static
    {
        $this->has_options = $has_options;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(?string $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function getContractPeriod(): ?array
    {
        return $this->contract_period;
    }

    public function setContractPeriod($contract_period): static
    {
        $this->contract_period = $contract_period;

        return $this;
    }

    public function getSubmissionTerms(): ?array
    {
        return $this->submission_terms;
    }

    public function setSubmissionTerms($submission_terms): static
    {
        $this->submission_terms = $submission_terms;

        return $this;
    }

    public function getAwardCriteria(): ?array
    {
        return $this->award_criteria;
    }

    public function setAwardCriteria($award_criteria): static
    {
        $this->award_criteria = $award_criteria;

        return $this;
    }

    public function isHasRenewal(): ?bool
    {
        return $this->has_renewal;
    }

    public function setHasRenewal(?bool $has_renewal): static
    {
        $this->has_renewal = $has_renewal;

        return $this;
    }

    public function getRenewal(): ?array
    {
        return $this->renewal;
    }

    public function setRenewal($renewal): static
    {
        $this->renewal = $renewal;

        return $this;
    }
}
