<?php

namespace App\Model\API;

use App\Model\API\FindTender\FindTenderItem;
use App\Model\API\FindTender\Links;

class FindTender
{
    /**
     * @var FindTenderItem[]|null
     */
    public ?array $releases = null;
    public ?Links $links = null;
}
