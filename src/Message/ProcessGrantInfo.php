<?php

namespace App\Message;

use App\Model\Grant;

final class ProcessGrantInfo
{
    public function __construct(public Grant $grantInfo) {}
}
