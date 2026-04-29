<?php

namespace App\Models;

use Database\Factories\TaskFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $fillable = ['title', 'description', 'status', 'priority', 'due_date', 'user_id', 'category_id'];

    /** @use HasFactory<TaskFactory> */
    use HasFactory;

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function category()
    {
        $this->belongsTo(Category::class);
    }

    public function subtasks()
    {
        $this->hasMany(Subtask::class);
    }
}
