<?php

namespace App\Values;

use App\Data\Enums\NthDayEnum;
use App\Data\Enums\WeekDayEnum;
use Livewire\Wireable;
use Spatie\LaravelData\Concerns\WireableData;
use Spatie\LaravelData\Data;

class NthWeekDayValue extends Data implements Wireable
{
    use WireableData;

    public function __construct(
        public $nth = null,
        public $week_day = null
    ) {
    }
}
