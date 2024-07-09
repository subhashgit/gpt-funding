<?php

namespace App\Message;

use App\Model\API\FindTender\FindTenderItem;

final class ApiFindTender
{
    public function __construct(public readonly FindTenderItem $tender) {}
}
