<?php

namespace App\Model\API\FindTender;

class Documents
{
    public int|string|null $id = null;
    public ?string $documentType = null;
    public ?string $description = null;
    public ?string $url = null;
    public ?string $datePublished = null;
}
