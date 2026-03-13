<?php

namespace App\Enums;

enum AuthResult: string
{
    case PASS = 'pass';
    case FAIL = 'fail';
    case SOFTFAIL = 'softfail';
    case NEUTRAL = 'neutral';
    case NONE = 'none';
    case TEMPERROR = 'temperror';
    case PERMERROR = 'permerror';
}
