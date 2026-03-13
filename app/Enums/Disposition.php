<?php

namespace App\Enums;

enum Disposition: string
{
    case NONE = 'none';
    case QUARANTINE = 'quarantine';
    case REJECT = 'reject';
}
