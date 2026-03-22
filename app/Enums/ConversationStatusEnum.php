<?php

namespace App\Enums;

enum ConversationStatusEnum: string
{
    case OPEN = 'open';
    case WAIT_FOR_ADMIN = 'wait for admin';
    case CLOSED = 'closed';
}
