<?php

namespace App\Models;

use Database\Factories\SubtaskFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subtask extends Model
{
    protected $fillable = ['task', 'task_id', 'done'];
    
    protected $casts = [
        'done' => 'boolean',
    ];

    /** @use HasFactory<SubtaskFactory> */
    use HasFactory;

    public function task()
    {
        return $this->belongsTo(Task::class);
    }

}
