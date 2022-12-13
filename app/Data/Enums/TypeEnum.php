<?php

namespace App\Data\Enums;

enum TypeEnum: string
{
    case BOUNDED_TYPE  = 'bounded';
    case REPEATED_TYPE = 'repeated';
}
