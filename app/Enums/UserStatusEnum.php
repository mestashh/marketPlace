<?php

namespace App\Enums;

enum UserStatusEnum: string
{
    case ACCESS = 'access';
    case BANNED = 'banned';
    case CHECKING = 'checking';
}
