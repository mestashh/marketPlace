<?php

namespace App\Enums;

enum StatusEnum: string
{
    case ACCESS = 'access';
    case BANNED = 'banned';
    case CHECKING = 'checking';
}
