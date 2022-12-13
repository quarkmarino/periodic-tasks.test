<?php

namespace App\Models;

use App\Data\Enums\StatusEnum;
use App\Models\Task;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Completion extends Model
{
    use HasFactory;

    protected $fillable = [
        'task_date'
    ];

    protected $casts = [
        'task_date' => 'date',
        'completed_at' => 'datetime'
    ];

    # Relationships

    public function task()
    {
        return $this->belongsTo(Task::class, 'task_id');
    }

    # Accessors

    public function getStatusAttribute()
    {
        return is_null($this->completed_at) ? StatusEnum::PENDING_STATUS : StatusEnum::DONE_STATUS;
    }

}
