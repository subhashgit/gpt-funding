<?php

namespace App\Model\Scraper\Contractsfinder;

use Symfony\Component\Serializer\Annotation\SerializedName;

class Entry
{
    #[SerializedName('Procurement stage')]
    public string $procurement_stage;

    #[SerializedName('Notice status')]
    public string $notice_status;

    #[SerializedName('Closing')]
    public ?string $closing = null;

    #[SerializedName('Contract location')]
    public string $contract_location;

    #[SerializedName('Contract value')]
    public string $contract_value;

    #[SerializedName('Publication date')]
    public string $publication_date;

    public function getProcurementStage(): string
    {
        return trim($this->procurement_stage);
    }

    public function getNoticeStatus(): string
    {
        return trim($this->notice_status);
    }

    public function getClosing(): ?string
    {
        return trim($this->closing);
    }

    public function getContractLocation(): string
    {
        return trim($this->contract_location);
    }

    public function getContractValue(): string
    {
        return trim($this->contract_value);
    }

    public function getPublicationDate(): string
    {
        return trim($this->publication_date);
    }
}
