<?php

namespace App\Collections;

use App\Collections\MonthDaysCollection;
use App\Models\MonthSchedule;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

class PeriodMonthsCollection extends Collection
{
    public function getPreviewDates(MonthSchedule|array $monthSchedule, Carbon $startDate, Carbon $endDate, int $times = null)
    {
        return $this
            ->keyBy(function ($monthDate) use ($startDate, $endDate)  {
                return $monthDate->format(' M Y');
            })
            ->map(function ($monthDate) use ($monthSchedule, $startDate, $endDate) {
                $startOfMonth = $startDate->max($monthDate->copy()->startOfMonth());
                $endOfMonth = $endDate->min($monthDate->copy()->endOfMonth());

                $monthSchedule = $monthSchedule instanceof MonthSchedule
                    ? $monthSchedule
                    : new MonthSchedule($monthSchedule);

                return (new MonthDaysCollection([$monthSchedule]))->mapIntoDates($startOfMonth, $endOfMonth);
            })
            ->filter(function ($monthDate) {
                return $monthDate->count() > 0;
            })
            ->when($times, function ($monthDate) use ($times) {
                return $monthDate->reduce(function ($newPeriodMonth, $monthDates, $monthTitle) use (&$times) {
                    $itemsCount = $monthDates->count();

                    if ($times >= $itemsCount) {
                        $times -= $itemsCount;
                        $newPeriodMonth->put($monthTitle, $monthDates);
                    }
                    else {
                        $monthDates = $monthDates->take($times);
                        $times = 0;
                        $newPeriodMonth->put($monthTitle, $monthDates);
                    }

                    return $newPeriodMonth;
                }, new WeekDaysCollection);
            })
            ->filter->count();
    }
}
