<?php

namespace App\Data\Enums;

use ArchTech\Enums\Options;
use ArchTech\Enums\Values;

enum NthDayEnum: string
{
    use Options, Values;

    case FIRST_DAY  = 'first';
    case SECOND_DAY = 'second';
    case THIRD_DAY  = 'third';
    case FOURTH_DAY = 'fourth';
    case FIFTH_DAY  = 'fifth';

    public function label(): string
    {
        return match ($this) {
            Self::FIRST_DAY  => 'First',
            Self::SECOND_DAY => 'Second',
            Self::THIRD_DAY  => 'Third',
            Self::FOURTH_DAY => 'Fourth',
            Self::FIFTH_DAY  => 'Fifth',
        };
    }

    public function abr(): string
    {
        return match ($this) {
            Self::FIRST_DAY  => '1st',
            Self::SECOND_DAY => '2nd',
            Self::THIRD_DAY  => '3rd',
            Self::FOURTH_DAY => '4th',
            Self::FIFTH_DAY  => '5th',
        };
    }

    public function number(): int
    {
        return match ($this) {
            Self::FIRST_DAY  => 1,
            Self::SECOND_DAY => 2,
            Self::THIRD_DAY  => 3,
            Self::FOURTH_DAY => 4,
            Self::FIFTH_DAY  => 5,
        };
    }
}
