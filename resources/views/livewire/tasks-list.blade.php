<div>
    <div class="border-b border-gray-200 bg-white px-4 py-5 sm:px-6">
      <h3 class="text-lg font-medium leading-6 text-gray-900">Periodic Tasks List</h3>
    </div>

    <form class="p-6 text-gray-900 flex flex-col">
        <div class="w-full mb-4 grid grid-cols-1 sm:grid-cols-12 gap-4">
            <div class="col-span-4">
                <x-native-select
                    label="Select Task Grouping"
                    :options="$this->taskGroupingOptions"
                    option-value="value"
                    option-label="label"
                    wire:model="grouping"
                />
            </div>
            {{-- <div class="col-span-4">
                <x-datetime-picker
                    without-timezone
                    without-time
                    label="Start Date"
                    placeholder="Start Date"
                    wire:model="startDate"
                    without-time="true"
                />
            </div>
            <div class="col-span-4">
                <x-datetime-picker
                    without-timezone
                    without-time
                    label="End Date"
                    placeholder="End Date"
                    wire:model="endDate"
                    without-time="true"
                />
            </div> --}}
        </div>
    </form>

    <div class="border-b border-gray-200 bg-white px-4 py-5 sm:px-6">
      <h3 class="text-lg font-medium leading-6 text-gray-900">Tasks Between: {{ $this->startDate->format('l, M d, Y') }} And: {{ $this->endDate->format('l, M d, Y') }}</h3>
    </div>

    <div class="mx-auto w-full" style="height: 46rem">
        <nav class="h-full overflow-y-auto" aria-label="Directory">
            @foreach($this->tasks as $task)
                <div class="relative">
                    <div class="sticky top-0 z-10 border-t border-b border-gray-200 bg-gray-50 px-6 py-1 text-sm font-medium text-gray-500">
                        <h3>{{ $task->description }} <strong>({{ $task->starts_at->format('l, M d, Y') }} - {{ optional($task->ends_at)->format('l, M d, Y') }})</strong></h3>
                    </div>
                    <ul role="list" class="relative z-0 divide-y divide-gray-200">
                        @foreach($task->completions as $key => $value)
                            <li class="bg-white">
                                <div class="relative flex items-center space-x-3 px-6 py-5 focus-within:ring-2 focus-within:ring-inset focus-within:ring-indigo-500 hover:bg-gray-50">
                                    <div class="min-w-0 flex-1">
                                        <a href="#" class="focus:outline-none">
                                            <!-- Extend touch target to entire panel -->
                                            <span class="absolute inset-0" aria-hidden="true"></span>
                                            <p class="truncate text-sm text-gray-500">{{ $value->task_date->format('l, M d, Y') }}</p>
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
