<?php

namespace App\Casts;

use App\Collections\WeekDaysCollection;
use App\Data\Enums\WeekDayEnum;
use Illuminate\Contracts\Database\Eloquent\Castable;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;

class AsWeekDaysCollection implements Castable
{
    /**
     * Get the caster class to use when casting from / to this cast target.
     *
     * @param  array  $arguments
     * @return object|string
     */
    public static function castUsing(array $arguments)
    {
        return new class implements CastsAttributes
        {
            public function get($model, $key, $value, $attributes)
            {
                if (! isset($attributes[$key])) {
                    return;
                }

                $data = json_decode($attributes[$key], true);

                return is_array($data)
                    ? (new WeekDaysCollection($data))
                        // ->sortBy(fn ($weekDay) => $weekDay->order())
                    : null;
            }

            public function set($model, $key, $value, $attributes)
            {
                return [$key => json_encode($value)];
            }
        };
    }
}
