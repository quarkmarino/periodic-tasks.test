<div>
    <div class="border-b border-gray-200 bg-white px-4 py-5 sm:px-6">
      <h3 class="text-lg font-medium leading-6 text-gray-900">Create a new Periodic Task</h3>
    </div>
    <x-errors />
    <div>
        @if (session()->has('message'))
            <div class="rounded-md bg-green-50 p-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <!-- Heroicon name: mini/check-circle -->
                        <svg class="h-5 w-5 text-green-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-green-800">{{ session('message') }}</h3>
                    </div>
                </div>
            </div>
        @endif
    </div>
    <form class="p-6 text-gray-900 flex flex-col">
        <div class="w-full mb-4 grid grid-cols-1 sm:grid-cols-12 gap-4">
            <div class="col-span-6">
                <x-datetime-picker
                    without-timezone
                    without-time
                    label="Start Date"
                    placeholder="Start Date"
                    wire:model="task.starts_at"
                    without-time="true"
                />
            </div>
            <div class="col-span-6">
                <x-datetime-picker
                    without-timezone
                    without-time
                    label="End Date"
                    placeholder="End Date"
                    wire:model="task.ends_at"
                    without-time="true"
                />
            </div>

            <div class="col-span-4">
                <x-native-select
                    label="Select Time Scale"
                    :options="$this->timeScaleOptions"
                    option-label="label"
                    option-value="value"
                    wire:model="task.scale"
                />
            </div>

            <div class="col-span-4">
                <x-inputs.number label="Every {{ $this->scale_plural }}" wire:model="task.every" min="1" step="1"/>
            </div>

            <div class="col-span-4">
                <x-inputs.number label="Times" wire:model="task.times" min="0" step="0"/>
            </div>
        </div>

        <div
            class="w-full mb-4 grid grid-cols-1 sm:grid-cols-12 gap-4"
            x-data
            x-show="$wire.task.scale == '{{ App\Data\Enums\TimeScaleEnum::WEEK_SCALE->value }}'"
        >
            <div class="col-span-12">
                <x-select
                    label="Select Week Days"
                    placeholder="Week Days"
                    multiselect
                    :options="$this->weekDayOptions"
                    option-value="value"
                    option-label="label"
                    wire:model="weekSchedule.week_days"
                />
            </div>
        </div>
        <div
            class="w-full mb-4 grid grid-cols-1 sm:grid-cols-12 gap-4"
            x-data
            x-show="$wire.task.scale == '{{ App\Data\Enums\TimeScaleEnum::MONTH_SCALE->value }}'"
        >
            <div class="col-span-3">
                <x-native-select
                    label="Select Month"
                    placeholder="Select Month"
                    :options="$this->monthOptions"
                    option-label="label"
                    option-value="value"
                    wire:model="monthSchedule.month"
                />
            </div>
            <div class="col-span-3">
                <x-inputs.number
                    label="Month Day"
                    wire:model="monthSchedule.month_day"
                    min="0"
                    max="31"
                    step="1"
                />
            </div>

            <div class="col-span-3">
                <x-native-select
                    label="Nth Week Day"
                    placeholder="Nth Week Day"
                    :options="$this->nthMonthDayOptions"
                    option-label="label"
                    option-value="value"
                    wire:model="monthSchedule.nth_week_day.nth"
                />
            </div>

            <div class="col-span-3">
                <x-native-select
                    label="Select Week Day"
                    placeholder="Week Day"
                    :options="$this->weekDayOptions"
                    option-value="value"
                    option-label="label"
                    wire:model="monthSchedule.nth_week_day.week_day"
                />
            </div>
        </div>

        <div class="w-full mb-4 grid grid-cols-1 sm:grid-cols-12 gap-4">
            <div class="col-span-12">
                <x-textarea label="Description" placeholder="The detailed task description" wire:model="task.description"/>
            </div>

            <div class="col-span-12">
                <div class="flex flex-row justify-end">
                    <x-button outline black label="Add Task" icon="check" wire:click="save"/>
                </div>
            </div>
        </div>
    </form>

    <div class="border-b border-gray-200 bg-white px-4 py-5 sm:px-6">
      <h3 class="text-lg font-medium leading-6 text-gray-900">
        @if($this->previewDates instanceof Illuminate\Support\Collection)
            <strong>({{ $this->previewDates->count() }})</strong>
        @endif
        Dates Preview By: {{ $this->task->scale->label() }}</h3>
    </div>
    <div class="mx-auto w-full" style="height: 26rem">
        <nav class="h-full overflow-y-auto" aria-label="Directory">
            @foreach($this->previewDates as $label => $week)
                <div class="relative">
                    @if($week instanceof Illuminate\Support\Collection)
                        <div class="sticky top-0 z-10 border-t border-b border-gray-200 bg-gray-50 px-6 py-1 text-sm font-medium text-gray-500">
                            <h3><strong>({{ $week->count() }})</strong> {{ $label }}</h3>
                        </div>
                    @endif
                    <ul role="list" class="relative z-0 divide-y divide-gray-200">
                        @foreach($week instanceof Illuminate\Support\Carbon ? Arr::wrap($week) : $week as $date)
                            <li class="bg-white">
                                <div class="relative flex items-center space-x-3 px-6 py-5 focus-within:ring-2 focus-within:ring-inset focus-within:ring-indigo-500 hover:bg-gray-50">
                                    <div class="min-w-0 flex-1">
                                        <a href="#" class="focus:outline-none">
                                            <!-- Extend touch target to entire panel -->
                                            <span class="absolute inset-0" aria-hidden="true"></span>
                                            <p class="truncate text-sm text-gray-500">{{ $date->format('l, M d, Y') }}</p>
                                        </a>
                                    </div>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endforeach
        </nav>
    </div>
</div>
