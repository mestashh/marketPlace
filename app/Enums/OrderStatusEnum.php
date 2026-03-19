<?php

namespace App\Enums;

enum OrderStatusEnum: string
{
    case CREATED = 'created';
    case PAID = 'paid';
    case SHIPPED = 'shipped';
    case DELIVERED = 'delivered';
}
