<?php

namespace App\Models;

use Database\Factories\SubtaskFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subtask extends Model
{
    protected $fillable = ['name', 'task_id'];

    /** @use HasFactory<SubtaskFactory> */
    use HasFactory;

    public function task()
    {
        $this->belongsTo(Task::class);
    }

}
