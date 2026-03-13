<?php

namespace App\Enums;

enum SubscriptionPlan: string
{
    case FREE = 'free';
    case PRO = 'pro';
    case ENTERPRISE = 'enterprise';
}
