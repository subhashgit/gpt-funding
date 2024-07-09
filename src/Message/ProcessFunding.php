<?php

namespace App\Message;

use App\Model\Funding;

final class ProcessFunding
{
    public function __construct(public Funding $funding) {}
}
