<?php

namespace App\Models;

use Database\Factories\CategoryFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = ['name', 'user_id'];

    /** @use HasFactory<CategoryFactory> */
    use HasFactory;

    public function user()
    {
        $this->belongsTo(User::class);
    }

    public function tasks()
    {
        $this->hasMany(Task::class);
    }
}
