<?php

namespace App\Http\Livewire;

use App\Data\Enums\MonthEnum;
use App\Data\Enums\NthDayEnum;
use App\Data\Enums\TimeScaleEnum;
use App\Data\Enums\WeekDayEnum;
use App\Models\Completion;
use App\Models\MonthSchedule;
use App\Models\Task;
use App\Models\WeekSchedule;
use App\Values\NthWeekDayValue;
use Carbon\CarbonInterval;
use Carbon\CarbonPeriod;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Livewire\Component;

class TaskForm extends Component
{
    public Task $task;
    public WeekSchedule $weekSchedule;
    public MonthSchedule $monthSchedule;

    protected function rules()
    {
        return [
            'task.description' => 'required|min:6',
            'task.scale' => [
                'required',
                Rule::in(TimeScaleEnum::values())
            ],
            'task.every' => 'required|integer|min:1',
            'task.starts_at' => [
                'required',
                'date',
            ],
            'task.ends_at' => [
                'nullable',
                'required_if:task.times,null',
                'date',
                'after_or_equal:task.starts_at',
            ],
            'task.times' => 'nullable|required_if:task.ends_at,null|integer',
            'weekSchedule.week_days' => [
                'required_if:task.scale,'.TimeScaleEnum::WEEK_SCALE->value,
                'array'
            ],
            'weekSchedule.week_days.*' => [
                'required_if:task.scale,'.TimeScaleEnum::WEEK_SCALE->value,
                Rule::in(WeekDayEnum::values())
            ],
            'monthSchedule' => [
                'required_if:task.scale,'.TimeScaleEnum::MONTH_SCALE->value,
                'required_array_keys:month,month_day,nth_week_day'
            ],
            'monthSchedule.month' => [
                'nullable',
                // 'required_if:task.scale,'.TimeScaleEnum::MONTH_SCALE->value,
                Rule::in(MonthEnum::values())
            ],
            'monthSchedule.month_day' => [
                'nullable',
                'sometimes',
                Rule::requiredIf(request()->get('task.scale') == TimeScaleEnum::MONTH_SCALE->value && !request()->get('monthSchedule.nth_week_day.nth') && !request()->get('monthSchedule.nth_week_day.week_day')),
                // 'required_if:task.scale,'.TimeScaleEnum::MONTH_SCALE->value,
                // 'required_without_all:monthSchedule.nth_week_day.nth,monthSchedule.nth_week_day.week_day',
                'integer',
                'between:0,31'
            ],
            'monthSchedule.nth_week_day' => [
                'nullable',
                // 'required_if:task.scale,'.TimeScaleEnum::MONTH_SCALE->value,
                Rule::requiredIf(request()->get('task.scale') == TimeScaleEnum::MONTH_SCALE->value && !request()->get('monthSchedule.month_day')),
                // 'required_without:monthSchedule.month_day',
                'required_array_keys:nth,week_day'
            ],
            'monthSchedule.nth_week_day.nth' => [
                'nullable',
                Rule::in(NthDayEnum::values())
            ],
            'monthSchedule.nth_week_day.week_day' => [
                'nullable',
                Rule::requiredIf(request()->get('task.scale') == TimeScaleEnum::MONTH_SCALE->value && request()->get('monthSchedule.nth_week_day.nth')),
                // 'required_with:monthSchedule.nth_week_day.nth',
                Rule::in(WeekDayEnum::values())
            ],
        ];
    }

    public function mount()
    {
        $this->resetProps();
    }

    public function render()
    {
        // $this->previewDates = $this->previewDates();

        return view('livewire.task-form');
    }

    # Hooks

    public function updatedTaskStartsAt()
    {
        $this->task->starts_at = $this->task->starts_at
            ? $this->task->starts_at->format('Y-m-d')
            : null;
    }

    public function updatedTaskEndsAt()
    {
        if ($this->task->end_at) {
            $this->task->end_at = $this->task->end_at->format('Y-m-d');
            // $this->task->times = null;
        }

        $this->task->end_at = null;
    }

    public function updatedMonthScheduleNthWeekDayNth()
    {
        if (!!($this->monthSchedule->nth_week_day['nth'] ?? false) && !!($this->monthSchedule->nth_week_day['week_day'] ?? false)) {
            $this->monthSchedule->month_day = null;
        }
    }

    public function updatedMonthScheduleNthWeekDayWeekDay()
    {
        if (!!($this->monthSchedule->nth_week_day['nth'] ?? false) && !!($this->monthSchedule->nth_week_day['week_day'] ?? false)) {
            $this->monthSchedule->month_day = null;
        }
    }

    public function updatedMonthScheduleMonthDay()
    {
        if (!!($this->monthSchedule->month_day ?? false)) {
            $this->monthSchedule->nth_week_day = ['nth' => null, 'week_day' => null];
        }
    }

    public function updatedTaskEvery()
    {
        if (!$this->task->every) {
            $this->task->every = 1;
        }
    }

    // public function updatedTaskTimes()
    // {
    //     if ($this->task->times <= 0) {
    //         $this->task->times = null;
    //     }
    //     else {
    //         $this->task->end_at = null;
    //     }
    // }

    # Computed Properties

    public function getTimeScaleOptionsProperty()
    {
        return collect(TimeScaleEnum::options())
            ->map(fn ($timeScaleValue, $timeScaleName) => [
                'label' => __('periodicity.time_scales.' . $timeScaleName),
                'value' => $timeScaleValue
            ])
            ->values();
    }

    public function getWeekDayOptionsProperty()
    {
        return collect(WeekDayEnum::options())
            ->map(fn ($weekDayValue, $weekDayName) => [
                'label' => __('periodicity.week_days.' . $weekDayName),
                'value' => $weekDayValue
            ])
            ->values();
    }

    public function getNthMonthDayOptionsProperty()
    {
        return collect(NthDayEnum::cases())
            ->map(fn ($nthDay) => [
                'label' => __('periodicity.nth_days.' . $nthDay->name),
                'value' => $nthDay
            ])
            ->values();
    }

    public function getMonthOptionsProperty()
    {
        return collect(MonthEnum::options())
            ->map(fn ($monthValue, $monthName) => [
                'label' => __('periodicity.months.' . $monthName),
                'value' => $monthValue
            ])
            ->values();
    }

    public function getScalePluralProperty()
    {
        return Str::of($this->task->scale->label())
            ->plural((int) ($this->task->every ?? 1));
    }

    public function getPreviewDatesProperty()
    {
        $startDate = $this->task->workable_start_date;
        $endDate = $this->task->workable_end_date;

        return match ($this->task->scale) {
            TimeScaleEnum::DAY_SCALE => $this->task->grouppedDates($startDate, $endDate),
            TimeScaleEnum::WEEK_SCALE => $this->task->grouppedDates($startDate, $endDate, $this->weekSchedule),
            TimeScaleEnum::MONTH_SCALE => $this->task->grouppedDates($startDate, $endDate, $this->monthSchedule),
            // TimeScaleEnum::DAY_SCALE => $this->task->present()
            //     ->dailyPeriodicDates($startDate, $endDate, (int) $this->task->every, (int) $this->task->times),
            // TimeScaleEnum::WEEK_SCALE => $this->task->present()
            //     ->weeklyPeriodicDates($startDate, $endDate, $this->weekSchedule, (int) $this->task->every, (int) $this->task->times),
            // TimeScaleEnum::MONTH_SCALE => $this->task->present()
            //     ->monthlyPeriodicDates($startDate, $endDate, $this->monthSchedule, (int) $this->task->every, (int) $this->task->times),
            // default => null,
        };
    }

    # Livewire Actions

    public function clearTimes()
    {
        $this->task->times = null;
    }

    public function save()
    {
        $this->validate();

        $startDate = $this->task->workable_start_date;

        $endDate = $this->task->workable_end_date;

        if ($this->task->save()) {
            if ($this->task->scale == TimeScaleEnum::WEEK_SCALE && $this->weekSchedule->isDirty()) {
                $this->task->weekSchedule()->save($this->weekSchedule);
            }
            if ($this->task->scale == TimeScaleEnum::MONTH_SCALE && $this->monthSchedule->isDirty()) {
                $this->task->monthSchedule()->save($this->monthSchedule);
            }

            $this->task->completions()->saveMany($this->task->dates($startDate, $endDate)->map(function (Carbon $date) {
                return new Completion(['task_date' => $date]);
            }));

            session()->flash('message', __('task.events.created.success'));

            $this->resetProps();
        } else {
            session()->flash('message', __('task.events.created.failure'));
        }
    }

    public function resetProps()
    {
        $this->task = new Task;

        $this->weekSchedule = $this->task->weekSchedule ?? new WeekSchedule;

        $this->monthSchedule = $this->task->monthSchedule ?? new MonthSchedule;
    }
}
