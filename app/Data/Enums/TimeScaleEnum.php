<?php

namespace App\Data\Enums;

use ArchTech\Enums\Options;
use ArchTech\Enums\Values;

enum TimeScaleEnum: string
{
    use Options, Values;

    case DAY_SCALE   = 'day';
    case WEEK_SCALE  = 'week';
    case MONTH_SCALE = 'month';

    public function label(): string
    {
        return ucfirst($this->value);
    }

    public function adverb(): string
    {
        return match ($this) {
            Self::DAY_SCALE   => 'Daily',
            Self::WEEK_SCALE  => 'Weekly',
            Self::MONTH_SCALE => 'Monthly',
        };
    }
}
