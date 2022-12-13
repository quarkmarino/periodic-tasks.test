<?php

namespace App\Models;

use App\Casts\AsWeekDaysCollection;
use App\Casts\WeekDaysCast;
use App\Collections\WeekDaysCollection;
use App\Data\Enums\WeekDayEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Livewire\Wireable;

/**
 * For TimeScaleEnum::WEEK_SCALE
 * [sun, mon, tue, wen, thu, fri, sat]
 */
class WeekSchedule extends Model implements Wireable
{
    use HasFactory;

    protected $fillable = [
        'week_days'
    ];

    protected $attributes = [
        'week_days' => '[]',
    ];

    protected $casts = [
        'week_days' => 'array',
    ];

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
