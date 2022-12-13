<?php

namespace App\Models;

use App\Collections\MonthSchedulesCollection;
use App\Data\Enums\MonthEnum;
use App\Data\Enums\WeekDayEnum;
use App\Presenter\Contracts\Presentable;
use App\Presenter\Presenters\MonthSchedulePresenter;
use App\Presenter\Traits\HasPresenter;
use App\Values\NthWeekDayValue;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Livewire\Wireable;

/**
 * For TimeScaleEnum::MONTH_SCALE
 *
 * [01-min(monthLastDay()|[28,29,30,31])] x [Jan, Feb, Mar, Apr, May, Jun, Jul, Ago, Sep, Oct, Nov, Dec]
 * [1st, 2nd, 3rd, 4th, 5th] x [sun, mon, tue, wen, thu, fri, sat] x [Jan, Feb, Mar, Apr, May, Jun, Jul, Ago, Sep, Oct, Nov, Dec] N times
 * e.g.
 * [
 *      ['month_days' => [['nth' => '01', 'week_day' => null], ['nth' => 'first', 'week_day' => 'sun']], 'months' => ['Oct'], 'offset' => 0],
 *      ['month_days' => [['nth' => '15', 'week_day' => null], ['nth' => 'third', 'week_day' => 'fri']], 'months' => ['Dec', 'Sep',]],
 *      ['month_days' => ['25'], 'months' => ['Jan']],
 *      ['month_days' => [['nth' => 'fourth', 'week_day' => 'thu']], 'months' => ['Jun', 'Aug', 'Oct']],
 * ]
 */
class MonthSchedule extends Model implements Wireable, Presentable
{
    use HasFactory, HasPresenter;

    protected $fillable = [
        'month_day',
        'nth_week_day',
        'month',
    ];

    protected $attributes = [
        'month_day' => null,
        'nth_week_day' => "{'nth': null, 'week_day': null}",
        'month' => null,
    ];

    protected $casts = [
        'month_day' => 'integer',
        'nth_week_day' => 'array',
        'month' => 'string',
    ];

    # Default Presenter

    protected $presenter = MonthSchedulePresenter::class;

    # Wireable Methods

    public function toLivewire()
    {
        return $this->only(array_keys($this->casts));
    }

    public static function fromLivewire($value)
    {
        return new static($value);
    }
}
