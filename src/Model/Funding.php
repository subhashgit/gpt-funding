<?php

namespace App\Model;

use App\Enum\Status;
use Nette\Utils\DateTime;
use Symfony\Component\Serializer\Annotation\SerializedName;

class Funding
{
    public const NOTICE_IDENTIFIER = 'Notice Identifier';
    public const NOTICE_TYPE = 'Notice Type';
    public const ORGANISATION_NAME = 'Organisation Name';
    public const STATUS = 'Status';
    public const PUBLISHED_DATE = 'Published Date';
    public const TITLE = 'Title';
    public const DESCRIPTION = 'Description';
    public const POSTCODE = 'Postcode';
    public const REGION = 'Region';
    public const CPV_CODES = 'Cpv Codes';
    public const CONTACT_NAME = 'Contact Name';
    public const CONTACT_EMAIL = 'Contact Email';
    public const CONTACT_ADDRESS1 = 'Contact Address 1';
    public const CONTACT_ADDRESS2 = 'Contact Address 2';
    public const CONTACT_TOWN = 'Contact Town';
    public const CONTACT_POSTCODE = 'Contact Postcode';
    public const CONTACT_COUNTRY = 'Contact Country';
    public const CONTACT_TELEPHONE = 'Contact Telephone';
    public const CONTACT_WEBSITE = 'Contact Website';
    public const LINKS = 'Links';
    public const SUPPLY_CHAIN = 'Supply Chain';
    public const VALUE_LOW = 'Value Low';
    public const VALUE_HIGH = 'Value High';
    public const START_DATE = 'Start Date';
    public const CLOSING_DATE = 'Closing Date';
    public const END_DATE = 'End Date';
    public const SUITABLE_FOR_SME = 'Suitable For SME';
    public const SUITABLE_FOR_VCO = 'Suitable for VCO';
    public const OJEU_PROCEDURE_TYPE = 'OJEU Procedure Type';
    public const OJEU_CONTRACT_TYPE = 'OJEU Contract Type';
    public const ADDITIONAL_TEXT = 'Additional Text';
    public const CLOSING_TIME = 'Closing Time';
    public const EXTERNAL_LINK = 'External Link';
    public const EXTERNAL_ID = 'External Id';
    public const DOWNLOADS = 'Downloads';

    #[SerializedName(Funding::NOTICE_IDENTIFIER)]
    public ?string $notice_identifier = null;

    #[SerializedName(Funding::NOTICE_TYPE)]
    public ?string $notice_type = null;

    #[SerializedName(Funding::ORGANISATION_NAME)]
    public ?string $organisation_name = null;

    #[SerializedName(Funding::STATUS)]
    public ?string $status = null;

    #[SerializedName(Funding::PUBLISHED_DATE)]
    public ?\DateTime $published_date = null;

    #[SerializedName(Funding::TITLE)]
    public ?string $title = null;

    #[SerializedName(Funding::DESCRIPTION)]
    public ?string $description = null;

    #[SerializedName(Funding::POSTCODE)]
    public ?string $postcode = null;

    #[SerializedName(Funding::REGION)]
    public ?array $region = null;

    #[SerializedName(Funding::CPV_CODES)]
    public ?string $cpv_codes = null;

    #[SerializedName(Funding::CONTACT_NAME)]
    public ?string $contact_name = null;

    #[SerializedName(Funding::CONTACT_EMAIL)]
    public ?string $contact_email = null;

    #[SerializedName(Funding::CONTACT_ADDRESS1)]
    public ?string $contact_address1 = null;

    #[SerializedName(Funding::CONTACT_ADDRESS2)]
    public ?string $contact_address2 = null;

    #[SerializedName(Funding::CONTACT_TOWN)]
    public ?string $contact_town = null;

    #[SerializedName(Funding::CONTACT_POSTCODE)]
    public ?string $contact_postcode = null;

    #[SerializedName(Funding::CONTACT_COUNTRY)]
    public ?string $contact_country = null;

    #[SerializedName(Funding::CONTACT_TELEPHONE)]
    public ?string $contact_telephone = null;

    #[SerializedName(Funding::CONTACT_WEBSITE)]
    public ?string $contact_website = null;

    #[SerializedName(Funding::LINKS)]
    public ?string $links = null;

    #[SerializedName(Funding::ADDITIONAL_TEXT)]
    public ?string $additional_text = null;

    #[SerializedName(Funding::START_DATE)]
    public ?string $start_date = null;

    #[SerializedName(Funding::END_DATE)]
    public ?string $end_date = null;

    #[SerializedName(Funding::CLOSING_DATE)]
    public ?string $closing_date = null;

    #[SerializedName(Funding::SUITABLE_FOR_SME)]
    public ?string $suitable_for_sme = null;

    #[SerializedName(Funding::SUITABLE_FOR_VCO)]
    public ?string $suitable_for_vco = null;

    #[SerializedName(Funding::SUPPLY_CHAIN)]
    public ?string $supply_chain = null;

    #[SerializedName(Funding::OJEU_CONTRACT_TYPE)]
    public ?string $ojeu_contract_type = null;

    #[SerializedName(Funding::VALUE_LOW)]
    public ?string $value_low = null;

    #[SerializedName(Funding::VALUE_HIGH)]
    public ?string $value_high = null;

    #[SerializedName(Funding::OJEU_PROCEDURE_TYPE)]
    public ?string $ojeu_procedure_type = null;

    #[SerializedName(Funding::CLOSING_TIME)]
    public ?string $closing_time = null;

    #[SerializedName(Funding::EXTERNAL_LINK)]
    public ?string $external_link = null;

    #[SerializedName(Funding::EXTERNAL_ID)]
    public ?string $external_id = null;

    #[SerializedName(Funding::DOWNLOADS)]
    public ?array $downloads = null;

    public function getNoticeIdentifier(): ?string
    {
        return $this->notice_identifier;
    }

    public function setNoticeIdentifier(?string $notice_identifier): void
    {
        $this->notice_identifier = $notice_identifier;
    }

    public function getNoticeType(): ?string
    {
        return $this->notice_type;
    }

    public function setNoticeType(?string $notice_type): void
    {
        $this->notice_type = $notice_type;
    }

    public function getOrganisationName(): ?string
    {
        return $this->organisation_name;
    }

    public function setOrganisationName(?string $organisation_name): void
    {
        $this->organisation_name = $organisation_name;
    }

    public function getStatus(): Status
    {
        return 'Open' === $this->status ? Status::OPEN : Status::CLOSED;
    }

    public function setStatus(?string $status): void
    {
        $this->status = $status;
    }

    public function getPublishedDate(): ?\DateTimeImmutable
    {
        if (!$this->published_date) {
            return null;
        }

        return new \DateTimeImmutable(DateTime::from($this->published_date));
    }

    public function setPublishedDate(?\DateTime $published_date): void
    {
        $this->published_date = $published_date;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(?string $title): void
    {
        $this->title = $title;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): void
    {
        $this->description = $description;
    }

    public function getPostcode(): ?string
    {
        return $this->postcode;
    }

    public function setPostcode(?string $postcode): void
    {
        $this->postcode = $postcode;
    }

    public function getRegion(): ?array
    {
        return $this->region ?: null;
    }

    public function setRegion(?array $region): void
    {
        $this->region = $region;
    }

    public function getCpvCodes(): ?array
    {
        return explode(' ', trim($this->cpv_codes));
    }

    public function setCpvCodes(?string $cpv_codes): void
    {
        $this->cpv_codes = $cpv_codes;
    }

    public function getContactName(): ?string
    {
        return $this->contact_name;
    }

    public function setContactName(?string $contact_name): void
    {
        $this->contact_name = $contact_name;
    }

    public function getContactEmail(): ?string
    {
        return $this->contact_email;
    }

    public function setContactEmail(?string $contact_email): void
    {
        $this->contact_email = $contact_email;
    }

    public function getContactAddress1(): ?string
    {
        return $this->contact_address1;
    }

    public function setContactAddress1(?string $contact_address1): void
    {
        $this->contact_address1 = $contact_address1;
    }

    public function getContactAddress2(): ?string
    {
        return '' !== $this->contact_address2 ? $this->contact_address2 : null;
    }

    public function setContactAddress2(?string $contact_address2): void
    {
        $this->contact_address2 = $contact_address2;
    }

    public function getContactTown(): ?string
    {
        return $this->contact_town;
    }

    public function setContactTown(?string $contact_town): void
    {
        $this->contact_town = $contact_town;
    }

    public function getContactPostcode(): ?string
    {
        return '' !== $this->contact_postcode ? $this->contact_postcode : null;
    }

    public function setContactPostcode(?string $contact_postcode): void
    {
        $this->contact_postcode = $contact_postcode;
    }

    public function getContactCountry(): ?string
    {
        return $this->contact_country;
    }

    public function setContactCountry(?string $contact_country): void
    {
        $this->contact_country = $contact_country;
    }

    public function getContactTelephone(): ?string
    {
        return '' !== $this->contact_telephone ? $this->contact_telephone : null;
    }

    public function setContactTelephone(?string $contact_telephone): void
    {
        $this->contact_telephone = $contact_telephone;
    }

    public function getContactWebsite(): ?string
    {
        return '' !== $this->contact_website ? $this->contact_website : null;
    }

    public function setContactWebsite(?string $contact_website): void
    {
        $this->contact_website = $contact_website;
    }

    public function getAdditionalText(): ?string
    {
        return '' !== $this->additional_text ? trim($this->additional_text) : null;
    }

    public function setAdditionalText(?string $additional_text): void
    {
        $this->additional_text = $additional_text;
    }

    public function getStartDate(): \DateTimeImmutable
    {
        return new \DateTimeImmutable(DateTime::from($this->start_date));
    }

    public function setStartDate(?string $start_date): void
    {
        $this->start_date = $start_date;
    }

    public function getEndDate(): \DateTimeImmutable
    {
        return new \DateTimeImmutable(DateTime::from($this->end_date));
    }

    public function setEndDate(?string $end_date): void
    {
        $this->end_date = $end_date;
    }

    public function getClosingDate(): \DateTimeImmutable
    {
        return new \DateTimeImmutable(DateTime::from($this->closing_date));
    }

    public function setClosingDate(?string $closing_date): void
    {
        $this->closing_date = $closing_date;
    }

    public function getSuitableForSme(): ?bool
    {
        return 'Yes' === $this->suitable_for_sme;
    }

    public function setSuitableForSme(?string $suitable_for_sme): void
    {
        $this->suitable_for_sme = $suitable_for_sme;
    }

    public function getSuitableForVco(): ?bool
    {
        return 'Yes' === $this->suitable_for_vco;
    }

    public function setSuitableForVco(?string $suitable_for_vco): void
    {
        $this->suitable_for_vco = $suitable_for_vco;
    }

    public function getSupplyChain(): ?bool
    {
        return 'Yes' === $this->supply_chain ? true : false;
    }

    public function setSupplyChain(?string $supply_chain): void
    {
        $this->supply_chain = $supply_chain;
    }

    public function getOjeuContractType(): ?string
    {
        return $this->ojeu_contract_type;
    }

    public function setOjeuContractType(?string $ojeu_contract_type): void
    {
        $this->ojeu_contract_type = $ojeu_contract_type;
    }

    public function getValueLow(): ?int
    {
        return (int) $this->value_low;
    }

    public function setValueLow(?string $value_low): void
    {
        $this->value_low = $value_low;
    }

    public function getValueHigh(): ?int
    {
        return '' !== $this->value_high ? (int) $this->value_high : null;
    }

    public function setValueHigh(?string $value_high): void
    {
        $this->value_high = $value_high;
    }

    public function getOjeuProcedureType(): ?string
    {
        return $this->ojeu_procedure_type;
    }

    public function setOjeuProcedureType(?string $ojeu_procedure_type): void
    {
        $this->ojeu_procedure_type = $ojeu_procedure_type;
    }

    public function getClosingTime(): \DateTimeImmutable
    {
        return new \DateTimeImmutable(DateTime::from($this->closing_time)->format('H:i'));
    }

    public function setClosingTime(?string $closing_time): void
    {
        $this->closing_time = $closing_time;
    }

    public function getLinks(): ?array
    {
        $array = trim($this->links);

        return '' !== $array ? explode(' ', $array) : null;
    }

    public function setLinks(?string $links): void
    {
        $this->links = $links;
    }

    public function getExternalLink(): ?string
    {
        return $this->external_link;
    }

    public function setExternalLink(?string $external_link): void
    {
        $this->external_link = $external_link;
    }

    public function getExternalId(): ?string
    {
        return $this->external_id;
    }

    public function setExternalId(?string $external_id): void
    {
        $this->external_id = $external_id;
    }

    public function getDownloads(): ?array
    {
        return $this->downloads;
    }

    public function setDownloads(?array $downloads): void
    {
        $this->downloads = $downloads;
    }
}
