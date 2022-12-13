<?php

namespace App\Data\Enums;

use ArchTech\Enums\Options;
use ArchTech\Enums\Values;

enum MonthEnum: string
{
    use Options, Values;

    case JANUARY   = 'january';
    case FEBRUARY  = 'february';
    case MARCH     = 'march';
    case APRIL     = 'april';
    case MAY       = 'may';
    case JUNE      = 'june';
    case JULY      = 'july';
    case AUGUST    = 'august';
    case SEPTEMBER = 'september';
    case OCTOBER   = 'october';
    case NOVEMBER  = 'november';
    case DECEMBER  = 'december';

    public function label(): string
    {
        return ucfirst($this->value);
        // return match ($this) {
        //     Self::JANUARY   => 'January',
        //     Self::FEBRUARY  => 'February',
        //     Self::MARCH     => 'March',
        //     Self::APRIL     => 'April',
        //     Self::MAY       => 'May',
        //     Self::JUNE      => 'June',
        //     Self::JULY      => 'July',
        //     Self::AUGUST    => 'August',
        //     Self::SEPTEMBER => 'September',
        //     Self::OCTOBER   => 'October',
        //     Self::NOVEMBER  => 'November',
        //     Self::DECEMBER  => 'December',
        // };
    }

    public function abbr(): string
    {
        return Str::limit($this->label(), 3, '');
        // return match ($this) {
        //     Self::JANUARY   => 'Jan',
        //     Self::FEBRUARY  => 'Feb',
        //     Self::MARCH     => 'Mar',
        //     Self::APRIL     => 'Apr',
        //     Self::MAY       => 'May',
        //     Self::JUNE      => 'Jun',
        //     Self::JULY      => 'Jul',
        //     Self::AUGUST    => 'Aug',
        //     Self::SEPTEMBER => 'Sep',
        //     Self::OCTOBER   => 'Oct',
        //     Self::NOVEMBER  => 'Nov',
        //     Self::DECEMBER  => 'Dec',
        // };
    }
}
