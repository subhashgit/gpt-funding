<?php

namespace App\Enum;

enum Status: string
{
    case OPEN = 'Open';

    case CLOSED = 'Closed';

    case ACTIVE = 'active';

    case COMPLETE = 'complete';
}
