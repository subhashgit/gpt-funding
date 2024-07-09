<?php

namespace App\Event;

use App\Entity\Funding;
use App\Entity\Grant;
use Symfony\Contracts\EventDispatcher\Event;

class View extends Event
{
    public function __construct(public readonly Funding|Grant $entity) {}
}
