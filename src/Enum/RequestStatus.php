<?php

namespace App\Enum;

enum RequestStatus: string
{
    // case NEW = 'new';
    case QUEUED = 'queued';
    case WRITING = 'writing';
    case SUBMITTED = 'submitted';
    case SUCCESSFUL = 'successful';
    case UNSUCCESSFUL = 'unsuccessful';
    // case PENDING = 'pending';
    // case APPROVED = 'approved';
    // case REJECTED = 'rejected';
    // case CANCELLED = 'cancelled';
    // case COMPLETED = 'completed';
    // case ARCHIVED = 'archived';

    public function label(): string
    {
        return match ($this) {
            // self::NEW => 'New',
            self::QUEUED => 'Queued',
            self::WRITING => 'Writing',
            self::SUBMITTED => 'Submitted',
            self::SUCCESSFUL => 'Successful',
            self::UNSUCCESSFUL => 'Unsuccessful',
            // self::PENDING => 'Pending',
            // self::APPROVED => 'Approved',
            // self::REJECTED => 'Rejected',
            // self::CANCELLED => 'Cancelled',
            // self::COMPLETED => 'Completed',
            // self::ARCHIVED => 'Archived',
        };
    }
}
