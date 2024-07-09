<?php

namespace App\Model\API\FindTender;

class FindTenderItem
{
    public ?string $ocid = null;
    public ?string $id = null;
    public ?\DateTimeImmutable $date = null;
    public ?string $initiationType = null;
    public ?string $description = null;
    public ?Buyer $buyer = null;
    public ?Tender $tender = null;
    /**
     * @var Party[]|null
     */
    public ?array $parties = null;

    /**
     * @var string[]|null
     */
    public ?array $tag = null;

    public function getUrl(string $type): string
    {
        if ('contractsfinder' === $type) {
            $id = explode('-', $this->id, -1);
            $id = implode('-', $id);

            return "https://www.contractsfinder.service.gov.uk/notice/{$id}";
        }
        if ('find-tender' === $type) {
            return "https://www.find-tender.service.gov.uk/Notice/{$this->id}";
        }
    }
}
