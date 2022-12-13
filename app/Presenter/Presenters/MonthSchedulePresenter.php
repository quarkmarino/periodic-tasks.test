<?php

namespace App\Presenter\Presenters;

use App\Models\MonthSchedule;
use App\Presenter\Presenter;
use Illuminate\Support\Carbon;

class MonthSchedulePresenter extends Presenter
{
    public function asDateExpression(Carbon $startOfMonth)
    {
        $nth_week_day = json_decode(json_encode($this->nth_week_day ?: ['nth' => null, 'week_day' => null]), false);

        $monthYear = (!!$this->month ? $this->month : $startOfMonth->monthName) . ' ' . $startOfMonth->year;

        return !!$this->month_day
            ? min($this->month_day, $startOfMonth->copy()->endOfMonth()->day) . ' ' . $monthYear
            : optional($nth_week_day)->nth . ' ' . optional($nth_week_day)->week_day . ' ' . $monthYear;
    }
}
