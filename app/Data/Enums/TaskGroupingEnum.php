<?php

namespace App\Data\Enums;

use ArchTech\Enums\Options;
use ArchTech\Enums\Values;

enum TaskGroupingEnum: string
{
    use Options, Values;

    case TODAY      = 'today';
    case TOMORROW   = 'tomorrow';
    case NEXT_WEEK  = 'next_week';
    case NEXT_MONTH = 'next_month';
    case CUSTOM     = 'custom';

    public function label(): string
    {
        return match ($this) {
            Self::TODAY      => 'Today',
            Self::TOMORROW   => 'Tomorrow',
            Self::NEXT_WEEK  => 'Next Week',
            Self::NEXT_MONTH => 'Next Month',
            Self::CUSTOM     => 'Custom',
        };
    }
}
