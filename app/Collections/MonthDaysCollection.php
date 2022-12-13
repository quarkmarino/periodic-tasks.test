<?php

namespace App\Collections;

use App\Data\Enums\WeekDayEnum;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

class MonthDaysCollection extends Collection
{
    public function mapIntoDates(Carbon $startOfMonth, Carbon $endOfMonth)
    {
        return $this
            ->filter(function ($monthSchedule) use ($startOfMonth) {
                return !$monthSchedule->month || $startOfMonth->is($monthSchedule->month);
            })
            ->map(function ($monthSchedule) use ($startOfMonth) {
                return $monthSchedule->present()->asDateExpression($startOfMonth);
            })
            ->map(function ($dateExpression) {
                return Carbon::createFromTimestamp(strtotime($dateExpression));
            })
            ->filter(function ($monthDay) use ($startOfMonth, $endOfMonth) {
                return $monthDay->between($startOfMonth, $endOfMonth);
            })
            ->unique()
            ->sort();
    }
}
