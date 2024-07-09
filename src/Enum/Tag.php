<?php

namespace App\Enum;

enum Tag: string
{
    case AWARD_UPDATE = 'awardUpdate';
    case CONTRACT_UPDATE = 'contractUpdate';
    case AWARD = 'award';
    case CONTRACT = 'contract';
    case PLANNING = 'planning';
    case TENDER = 'tender';
    case TENDER_UPDATE = 'tenderUpdate';
    case TENDER_AMENDMENT = 'tenderAmendment';
}
