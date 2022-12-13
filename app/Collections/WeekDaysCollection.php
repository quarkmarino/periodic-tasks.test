<?php

namespace App\Collections;

use App\Data\Enums\WeekDayEnum;
use Illuminate\Support\Collection;

class WeekDaysCollection extends Collection
{
    public function mapIntoDates($startOfWeek, $endOfWeek)
    {
        return $this->map(fn ($weekDay) => WeekDayEnum::tryFrom($weekDay))
            ->map(function ($weekDay) use ($startOfWeek) {
                return $startOfWeek->is($weekDay->value)
                    ? $startOfWeek
                    : $startOfWeek->copy()->next($weekDay->value);
            })
            ->filter(function ($weekDay) use ($startOfWeek, $endOfWeek) {
                return $weekDay->between($startOfWeek, $endOfWeek);
            })
            ->sort();
    }
}
