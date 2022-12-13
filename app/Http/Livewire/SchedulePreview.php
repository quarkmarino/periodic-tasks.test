<?php

namespace App\Http\Livewire;

use Illuminate\Support\Collection;
use Livewire\Component;

class SchedulePreview extends Component
{
    public Collection $previewDates;

    public function mount($previewDates)
    {
        $this->previewDates = $previewDates;
    }

    public function render()
    {
        return view('livewire.schedule-preview');
    }
}
