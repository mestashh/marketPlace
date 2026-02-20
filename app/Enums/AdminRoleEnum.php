<?php

namespace App\Enums;

enum AdminRoleEnum: string
{
    case SUPPORT = 'support';
    case ADMIN = 'admin';
    case SUPER_ADMIN = 'super_admin';
}
