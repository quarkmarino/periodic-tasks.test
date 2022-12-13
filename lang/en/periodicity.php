<?php

use App\Data\Enums\MonthEnum;
use App\Data\Enums\NthDayEnum;
use App\Data\Enums\TimeScaleEnum;
use App\Data\Enums\WeekDayEnum;

return [
    'time_scales' => collect(TimeScaleEnum::cases())->mapWithKeys(fn ($timeScale) => [$timeScale->name => $timeScale->label()] ),
    'nth_days' => collect(NthDayEnum::cases())->mapWithKeys(fn ($nthDay) => [$nthDay->name => $nthDay->label()] ),
    'week_days' => collect(WeekDayEnum::cases())->mapWithKeys(fn ($weekDay) => [$weekDay->name => $weekDay->label()] ),
    'months' => collect(MonthEnum::cases())->mapWithKeys(fn ($month) => [$month->name => $month->label()] ),
];
