<?php

namespace App\Model\API\FindTender;

class Party
{
    public ?string $name = null;
    public ?string $id = null;
    public ?Address $address = null;
    public ?ContactPoint $contactPoint = null;
    /**
     * @var string[]|null
     */
    public ?array $roles = null;

    public ?Details $details = null;

    public ?Identifier $identifier = null;
}
