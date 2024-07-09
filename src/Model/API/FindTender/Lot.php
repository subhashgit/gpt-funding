<?php

namespace App\Model\API\FindTender;

class Lot
{
    public ?string $id = null;
    public ?string $title = null;
    public ?string $status = null;
    public ?string $description = null;
    public ?bool $hasOptions = null;
    public ?Value $value = null;
    public ?bool $hasRenewal = null;
    public ?Renewal $renewal = null;
    public ?SubmissionTerms $submissionTerms = null;
    public ?ContractPeriod $contractPeriod = null;
    public ?AwardCriteria $awardCriteria = null;
}
