<?php

namespace App\Enums;

enum PayoutStatusEnum: string
{
    case PENDING = 'pending';
    case VERIFIED = 'verified';
    case DISABLED = 'disabled';
}
