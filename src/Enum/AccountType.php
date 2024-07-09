<?php

namespace App\Enum;

enum AccountType: string
{
    case FREE = 'free';
    case MONTHLY = 'monthly';
    case YEARLY = 'yearly';
}
