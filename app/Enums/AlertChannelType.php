<?php

namespace App\Enums;

enum AlertChannelType: string
{
    case EMAIL = 'email';
    case SLACK = 'slack';
    case WEBHOOK = 'webhook';
    case PUSHOVER = 'pushover';
}
