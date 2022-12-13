<?php

namespace App\Models;

use App\Casts\WeekDaysCast;
use App\Data\Enums\StatusEnum;
use App\Data\Enums\TimeScaleEnum;
use App\Presenter\Contracts\Presentable;
use App\Presenter\Presenters\TaskPresenter;
use App\Presenter\Traits\HasPresenter;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class Task extends Model implements Presentable
{
    use HasFactory, HasPresenter;

    protected $attributes = [
        'every' => 1,
        'scale' => TimeScaleEnum::DAY_SCALE,
        'times' => null,
    ];

    protected $casts = [
        'scale' => TimeScaleEnum::class,
        'starts_at' => 'date',
        'ends_at' => 'date',
    ];

    # Default Presenter

    protected $presenter = TaskPresenter::class;

    # Relationships

    public function weekSchedule()
    {
        return $this->morphOne(WeekSchedule::class, 'scheduable');
    }

    public function monthSchedule()
    {
        return $this->morphOne(MonthSchedule::class, 'scheduable');
    }

    public function completions()
    {
        return $this->hasMany(Completion::class, 'task_id');
    }

    # Accessors

    protected function getWorkableStartDateAttribute()
    {
        return $this->starts_at ?? Carbon::now();
    }

    protected function getWorkableEndDateAttribute()
    {
        return $this->ends_at ? $this->ends_at : ($this->times ? null : Carbon::now()->endOfYear());
    }

    # Helpers

    public function grouppedDates(Carbon $startDate, Carbon $endDate = null, $schedule = null)
    {
        $startDate = $startDate->max($this->starts_at);

        if ($this->ends_at) {
            $endDate = $endDate->min($this->ends_at);
        }
        else if($this->times) {
            $lastDateForTimes = $startDate->copy()->add($this->times, $this->scale->value);
            $endDate = $lastDateForTimes->max($endDate);
        }

        return match ($this->scale) {
            TimeScaleEnum::DAY_SCALE => $this->present()->dailyPeriodicDates($startDate, $endDate),
            TimeScaleEnum::WEEK_SCALE => $this->present()->weeklyPeriodicDates($startDate, $endDate, $schedule ?? $this->weekSchedule),
            TimeScaleEnum::MONTH_SCALE => $this->present()->monthlyPeriodicDates($startDate, $endDate, $schedule ?? $this->monthSchedule),
            default => null,
        };
    }

    public function dates(Carbon $startDate, Carbon $endDate = null, $schedule = null)
    {
        $startDate = $startDate->max($this->workable_start_date);

        if ($this->workable_end_date) {
            $endDate = $endDate->min($this->workable_end_date);
        }
        else if($this->times) {
            $lastDateForTimes = $startDate->copy()->add($this->times, $this->scale->value);

            $endDate = $lastDateForTimes->max($endDate);
        }

        return match ($this->scale) {
            TimeScaleEnum::DAY_SCALE => $this->present()->dailyPeriodicDates($startDate, $endDate),
            TimeScaleEnum::WEEK_SCALE => $this->present()->weeklyPeriodicDates($startDate, $endDate, $schedule ?? $this->weekSchedule)->flatten(),
            TimeScaleEnum::MONTH_SCALE => $this->present()->monthlyPeriodicDates($startDate, $endDate, $schedule ?? $this->monthSchedule)->flatten(),
            default => null,
        };
    }

    # Scopes

    public function scopeBetweenDatesInclusive($tasks, Carbon $periodStart, Carbon $periodEnd)
    {
        return $tasks
            ->where(function($tasks) use ($periodStart, $periodEnd) {
                $tasks
                    ->whereDate('starts_at', '<=', $periodEnd)
                    ->where(function ($tasks) use ($periodStart) {
                        $tasks->whereDate('ends_at', '>=', $periodStart)->orWhereNull('ends_at');
                    });
            })
            ->orWhere(function ($tasks) use ($periodStart, $periodEnd) {
                $tasks
                    ->whereDate('starts_at', '>=', $periodStart)
                    ->where(function ($tasks) use ($periodEnd) {
                        $tasks->whereDate('ends_at', '<=', $periodEnd)->orWhereNull('ends_at');
                    });
            });
    }
}
