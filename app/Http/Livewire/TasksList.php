<?php

namespace App\Http\Livewire;

use App\Data\Enums\TaskGroupingEnum;
use App\Data\Enums\TimeScaleEnum;
use App\Models\Task;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Livewire\Component;

class TasksList extends Component
{
    public $grouping = 'today';

    public Collection $tasks;

    public Carbon $startDate;
    public Carbon $endDate;

    protected function rules()
    {
        return [
            'startDate' => [
                'required',
                'date',
            ],
            'endDate' => [
                'nullable',
                'date',
                'after_or_equal:task.starts_at',
            ],
        ];
    }

    public function mount()
    {
        $this->resetProperties();
    }

    public function render()
    {
        $this->resetProperties();

        return view('livewire.tasks-list');
    }

    # Computed Properties

    public function getTaskGroupingOptionsProperty()
    {
        return collect(TaskGroupingEnum::cases())
            ->map(fn ($grouping) => [
                'label' => __('task.grouping.' . $grouping->name),
                'value' => $grouping
            ])
            ->values();
    }

    # Helpers

    public function resetProperties()
    {
        [$this->startDate, $this->endDate] = match ($this->grouping) {
            'today' => [Carbon::now(), Carbon::now()],
            'tomorrow' => [Carbon::tomorrow(), Carbon::tomorrow()],
            'next_week' => Carbon::now()->is(Carbon::SUNDAY)
                ? [Carbon::now(), Carbon::now()->next(Carbon::SATURDAY)]
                : [Carbon::now()->next(Carbon::SUNDAY), Carbon::now()->next(Carbon::SUNDAY)->next(Carbon::SATURDAY)],
            'next_month' => [Carbon::now()->startOfMonth()->addMonth(), Carbon::now()->startOfMonth()->addMonth()->endOfMonth()],
            'custom' => [$this->startDate, $this->endDate],
            default => [Carbon::now(), Carbon::now()],
        };

        // \DB::enableQueryLog();
        $this->tasks = Task::betweenDatesInclusive($this->startDate, $this->endDate)
            ->with(['weekSchedule', 'monthSchedule', 'completions' => function ($completions) {
                $completions->whereBetween('task_date', [$this->startDate->startOfDay(), $this->endDate->startOfDay()]);
            }])
            ->whereHas('completions', function ($completions) {
                $completions->whereBetween('task_date', [$this->startDate->startOfDay(), $this->endDate->startOfDay()]);
            })
            ->get();
        // dd(queries(\DB::getQueryLog()));
    }
}
