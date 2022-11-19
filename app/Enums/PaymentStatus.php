<?php

namespace App\Enums;

enum PaymentStatus: string
{
    case pending = 'pending';
    case paid = 'paid';
}
