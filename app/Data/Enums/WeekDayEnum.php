<?php

namespace App\Data\Enums;

use ArchTech\Enums\Options;
use ArchTech\Enums\Values;
use Illuminate\Support\Carbon;

enum WeekDayEnum: string
{
    use Options, Values;

    case SUNDAY    = 'sunday';
    case MONDAY    = 'monday';
    case TUESDAY   = 'tuesday';
    case WEDNESDAY = 'wednesday';
    case THURSDAY  = 'thursday';
    case FRIDAY    = 'friday';
    case SATURDAY  = 'saturday';

    public function label(): string
    {
        return ucfirst($this->value);
    }

    public function abbr(): string
    {
        return Str::limit($this->label(), 3, '');
    }

    public function order(): string
    {
        return Carbon::$this->name;
    }
}
