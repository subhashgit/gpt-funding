<?php

namespace App\Model\API\FindTender;

class Tender
{
    public ?string $id = null;

    public ?string $title = null;

    public ?string $description = null;

    public ?string $status = null;

    public ?string $mainProcurementCategory = null;

    public ?Classification $classification = null;

    /**
     * @var Classification[]|null
     */
    public ?array $additionalClassifications = null;

    public ?Value $minValue = null;

    public ?Value $value = null;

    public ?string $procurementMethod = null;

    public ?string $procurementMethodDetails = null;

    /**
     * @var Lot[]|null
     */
    public ?array $lots = null;

    /**
     * @var LotItem[]|null
     */
    public ?array $items = null;

    /**
     * @var string[]|null
     */
    public ?array $submissionMethod = null;

    public ?string $submissionMethodDetails = null;

    public ?ContractTerms $contractTerms = null;

    public ?Period $tenderPeriod = null;

    public ?Period $contractPeriod = null;

    public ?Period $awardPeriod = null;

    public ?Period $bidOpening = null;

    public ?bool $hasRecurrence = null;

    public ?Suitability $suitability = null;

    /**
     * @var Documents[]|null
     */
    public ?array $documents = null;
}
