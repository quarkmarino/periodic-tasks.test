<div>
    <div class="border-b border-gray-200 bg-white px-4 py-5 sm:px-6">
      <h3 class="text-lg font-medium leading-6 text-gray-900">Periodic Tasks List</h3>
    </div>

    <form class="p-6 text-gray-900 flex flex-col">
        <div class="w-full mb-4 grid grid-cols-1 sm:grid-cols-12 gap-4">
            <div class="col-span-12">
                <x-native-select
                    label="Select Task Grouping"
                    :options="$this->taskGroupingOptions"
                    option-value="value"
                    option-label="label"
                    wire:model="grouping"
                />
            </div>
        </div>
    </form>

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
                        @foreach($task->completions as $completion)
                            <li class="bg-white">
                                <div class="relative flex items-center space-x-3 px-6 py-5 focus-within:ring-2 focus-within:ring-inset focus-within:ring-indigo-500 hover:bg-gray-50">
                                    <div class="min-w-0 flex-1">
                                        <div class="flex flex-row ocus:outline-none">
                                            <p class="truncate text-sm text-gray-500">
                                                <span class="inline-flex items-center rounded-full {{ $completion->status->color('bg') }} px-2.5 py-0.5 text-xs font-medium {{ $completion->status->color('text') }}">
                                                    {{ $completion->status->label() }}
                                                    {{ $completion->status == App\Data\Enums\StatusEnum::DONE_STATUS
                                                        ? ' : ' . $completion->completed_at->diffForHumans()
                                                        : ''
                                                    }}
                                                </span>
                                                <span class="grow">{{ $completion->task_date->format('l, M d, Y') }}</span>
                                                @if($completion->status != App\Data\Enums\StatusEnum::DONE_STATUS)
                                                        <x-button
                                                            class="w-14 inline-flex items-center flex-none"
                                                            xs
                                                            label="Done"
                                                            icon="check"
                                                            wire:click="completeTask({{ $completion->id }})"
                                                        />
                                                @endif
                                            </p>
                                        </div>
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
