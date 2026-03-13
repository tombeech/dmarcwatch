<?php

namespace App\Enums;

enum AlertStatus: string
{
    case SENT = 'sent';
    case FAILED = 'failed';
    case RETRYING = 'retrying';
}
