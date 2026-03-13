<?php

namespace App\Enums;

enum DnsRecordStatus: string
{
    case VALID = 'valid';
    case INVALID = 'invalid';
    case MISSING = 'missing';
    case WARNING = 'warning';
}
