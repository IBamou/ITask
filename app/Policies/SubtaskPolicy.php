<?php

namespace App\Policies;

use App\Models\Subtask;
use App\Models\User;

class SubtaskPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, Subtask $subtask): bool
    {
        return $user->id === $subtask->task->category->user_id;
    }

    public function delete(User $user, Subtask $subtask): bool
    {
        return $user->id === $subtask->task->category->user_id;
    }
}