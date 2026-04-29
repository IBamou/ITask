<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('dashboard');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Category $category)
    {
        $validation = $request->validate([
            'title' => 'required|string',
            'description' => 'nullable',
            'priority' => 'required|in:low, medium, high',
            'due_date' => 'nullable',
        ]);

        $category->tasks()->create([
            ...$validation,
            'user_id' => auth()->id(),
        ]);

        return redirect()->route('categories.show', $category->id);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Category $category, Task $task)
    {
        $validation = $request->validate([
            'title' => 'required',
            'description' => 'nullable',
            'status' => 'required|in:pending, in_progress, done',
            'priority' => 'required|in:low, medium, high',
            'due_date' => 'nullable',
        ]);

        $task->update($validation);

        return redirect()->route('categories.show', $category->id);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Task $task, Category $category)
    {
        $task->delete();
        return redirect()->route('categories.show', $category->id);
    }
}
