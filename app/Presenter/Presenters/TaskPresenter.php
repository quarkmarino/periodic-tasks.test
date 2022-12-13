<?php

namespace App\Presenter\Presenters;

use App\Collections\PeriodDaysCollection;
use App\Collections\PeriodMonthsCollection;
use App\Collections\PeriodWeeksCollection;
use App\Models\MonthSchedule;
use App\Models\WeekSchedule;
use App\Presenter\Presenter;
use Carbon\CarbonPeriod;
use Illuminate\Support\Carbon;

class TaskPresenter extends Presenter
{
    public function dailyPeriodicDates(
        Carbon $startDate,
        Carbon $endDate,
        int $every = null,
        int $times = null
    )
    {
        $dailyPeriodExpression = ((int)($every ?? $this->every)) . ' day';
        $times = (int) ($times ?? $this->times);

        return (new PeriodDaysCollection(CarbonPeriod::create($startDate, $dailyPeriodExpression, $endDate)->toArray()))
            ->getPreviewDates($startDate, $endDate, $times);
    }

    public function weeklyPeriodicDates(
        Carbon $startDate,
        Carbon $endDate,
        WeekSchedule $weekSchedule,
        int $every = null,
        int $times = null
    )
    {
        $weeklyPeriodExpression = ($every ?? $this->every) . ' week';
        $times = (int) ($times ?? $this->times);

        return (new PeriodWeeksCollection(CarbonPeriod::create($startDate, $weeklyPeriodExpression, $endDate)->toArray()))
            ->getPreviewDates($weekSchedule ?? $this->weekSchedule, $startDate, $endDate, $times);
    }

    public function monthlyPeriodicDates(
        Carbon $startDate,
        Carbon $endDate,
        MonthSchedule $monthSchedule,
        int $every = null,
        int $times = null
    )
    {
        $monthlyPeriodExpression = ($every ?? $this->every) . ' month';
        $times = (int) ($times ?? $this->times);

        return (new PeriodMonthsCollection(CarbonPeriod::create($startDate, $monthlyPeriodExpression, $endDate)->toArray()))
            ->getPreviewDates($monthSchedule ?? $this->monthSchedule, $startDate, $endDate, $times);
    }
}
