<?php

namespace App\Model;

use Symfony\Component\Serializer\Annotation\SerializedName;

class Grant
{
    #[SerializedName('Title')]
    public ?string $title = null;

    #[SerializedName('Funder')]
    public ?string $funder = null;

    #[SerializedName('Description')]
    public ?string $description = null;

    #[SerializedName('Max Amount')]
    public ?string $maxAmount = null;

    #[SerializedName('Funding Details')]
    public ?string $fundingDetails = null;

    #[SerializedName('Who can apply')]
    public ?string $whoCanApply = null;

    #[SerializedName('Find out more')]
    public ?string $findOutMore = null;

    #[SerializedName('Deadline')]
    public ?string $deadline = null;

    #[SerializedName('Region')]
    public ?string $region = null;

    #[SerializedName('Category')]
    public ?string $category = null;

    #[SerializedName('Open to')]
    public ?string $openTo = null;

    #[SerializedName('how_to_apply')]
    public ?string $howToApply = null;

    #[SerializedName('Date added')]
    public ?\DateTimeImmutable $dateAdded = null;

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(?string $title): void
    {
        $this->title = $title;
    }

    public function getFunder(): ?string
    {
        return $this->funder;
    }

    public function setFunder(?string $funder): void
    {
        $this->funder = $funder;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): void
    {
        $this->description = $description;
    }

    public function getMaxAmount(): ?string
    {
        return $this->maxAmount;
    }

    public function setMaxAmount(?string $maxAmount): void
    {
        $this->maxAmount = $maxAmount;
    }

    public function getFundingDetails(): ?string
    {
        return $this->fundingDetails;
    }

    public function setFundingDetails(?string $fundingDetails): void
    {
        $this->fundingDetails = $fundingDetails;
    }

    public function getWhoCanApply(): ?string
    {
        return $this->whoCanApply;
    }

    public function setWhoCanApply(?string $whoCanApply): void
    {
        $this->whoCanApply = $whoCanApply;
    }

    public function getFindOutMore(): ?string
    {
        return $this->findOutMore;
    }

    public function setFindOutMore(?string $findOutMore): void
    {
        $this->findOutMore = $findOutMore;
    }

    public function getDeadline(): ?string
    {
        return $this->deadline ? str_replace([
            'This grant is open to applications.Applications can be submitted at any time.',
            'This grant is open to applications.The closing date for applications is',
        ], '', $this->deadline) : null;
    }

    public function setDeadline(?string $deadline): void
    {
        $this->deadline = $deadline;
    }

    public function getRegion(): ?string
    {
        return $this->region;
    }

    public function setRegion(?string $region): void
    {
        $this->region = $region;
    }

    public function getCategory(): ?string
    {
        return $this->category;
    }

    public function setCategory(?string $category): void
    {
        $this->category = $category;
    }

    public function getOpenTo(): ?string
    {
        return $this->openTo;
    }

    public function setOpenTo(?string $openTo): void
    {
        $this->openTo = $openTo;
    }

    public function getHowToApply(): ?string
    {
        return $this->howToApply;
    }

    public function setHowToApply(?string $howToApply): void
    {
        $this->howToApply = $howToApply;
    }

    public function getDateAdded(): ?\DateTimeImmutable
    {
        return $this->dateAdded;
    }

    public function setDateAdded(?\DateTimeImmutable $dateAdded): void
    {
        $this->dateAdded = $dateAdded;
    }
}
