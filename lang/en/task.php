<?php

use App\Data\Enums\TaskGroupingEnum;

return [
    'events' => [
        'created' => [
            'success' => 'The task has been saved succesfully.',
            'failure' => 'There has been a problem saving the task. Please try again later.'
        ]
    ],
    'grouping' => collect(TaskGroupingEnum::cases())->mapWithKeys(fn ($timeScale) => [$timeScale->name => $timeScale->label()] )
];
