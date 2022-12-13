<?php

namespace App\Collections;

use App\Collections\WeekDaysCollection;
use App\Data\Enums\WeekDayEnum;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

class PeriodDaysCollection extends Collection
{
    public function getPreviewDates(Carbon $startDate, Carbon $endDate, int $times = null)
    {
        return $this
            ->keyBy(function ($dayDate) {
                return $dayDate->format('l, M d, Y');
            })
            ->when($times, function ($dayDates) use ($times) {
                return $dayDates->take($times);
            });
    }
}
