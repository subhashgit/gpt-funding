<?php

namespace App\Message;

final class UserEmailNotify
{
    public function __construct(public readonly int $userId, public readonly string $type) {}
}
