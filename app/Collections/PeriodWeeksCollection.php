<?php

namespace App\Collections;

use App\Collections\WeekDaysCollection;
use App\Data\Enums\WeekDayEnum;
use App\Models\WeekSchedule;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

class PeriodWeeksCollection extends Collection
{
    public function getPreviewDates(WeekSchedule $weekSchedule, Carbon $startDate, Carbon $endDate, int $times = null)
    {
        return $this
            ->keyBy(function ($weekDate) use ($startDate, $endDate)  {
                $startOfWeek = $startDate->max($weekDate->copy()->startOfWeek(Carbon::SUNDAY));
                $endOfWeek = $endDate->min($weekDate->copy()->endOfWeek(Carbon::SATURDAY));

                return $startOfWeek->format('l, M d, Y').' - '.$endOfWeek->format('l, M d, Y');
            })
            ->map(function ($weekDate) use ($weekSchedule, $startDate, $endDate) {
                $startOfWeek = $startDate->max($weekDate->copy()->startOfWeek(Carbon::SUNDAY));
                $endOfWeek = $endDate->min($weekDate->copy()->endOfWeek(Carbon::SATURDAY));

                return (new WeekDaysCollection($weekSchedule->week_days))->mapIntoDates($startOfWeek, $endOfWeek);
            })
            ->when($times, function ($weekDate) use ($times) {
                return $weekDate->reduce(function ($newPeriodWeeks, $weekDates, $weekTitle) use (&$times) {
                    $itemsCount = $weekDates->count();

                    if ($times >= $itemsCount) {
                        $times -= $itemsCount;
                        $newPeriodWeeks->put($weekTitle, $weekDates);
                    }
                    else {
                        $weekDates = $weekDates->take($times);
                        $times = 0;
                        $newPeriodWeeks->put($weekTitle, $weekDates);
                    }

                    return $newPeriodWeeks;
                }, new WeekDaysCollection);
            })
            ->filter->count();
    }
}
