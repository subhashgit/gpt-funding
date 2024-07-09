<?php

namespace App\Message;

use App\Model\API\FindTender\FindTenderItem;

final class ScrapFindTenderPage
{
    public function __construct(public FindTenderItem $listItem) {}
}
