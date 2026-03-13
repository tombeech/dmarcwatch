<?php

namespace App\Enums;

enum DmarcPolicy: string
{
    case NONE = 'none';
    case QUARANTINE = 'quarantine';
    case REJECT = 'reject';
}
