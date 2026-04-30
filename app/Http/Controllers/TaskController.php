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
        // Check if user owns the category
        if ($category->user_id !== auth()->id()) {
            abort(403, 'You do not own this category');
        }

        $validation = $request->validate([
            'title' => 'required|string',
            'description' => 'nullable',
            'priority' => 'required|in:low,medium,high',
            'due_date' => 'nullable',
        ]);

        $task = $category->tasks()->create([
            ...$validation,
            'user_id' => auth()->id(),
        ]);
        //  dd($task);
        return redirect()->route('categories.show', $category->id)->with('success', 'Task created successfully.');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Category $category, Task $task)
    {
        $this->authorize('update', $task);

        $request->validate([
            'title' => 'required',
            'description' => 'nullable',
            'status' => 'required|in:pending,in_progress,done',
            'priority' => 'required|in:low,medium,high',
            'due_date' => 'nullable',
        ]);

        $task->update($request->only(['title', 'description', 'status', 'priority', 'due_date']));

        return redirect()->route('categories.show', $category->id)->with('success', 'Task updated successfully.');
    }

    /**
     * Toggle task status (partial update).
     */
    public function toggleStatus(Request $request, Category $category, Task $task)
    {
        $this->authorize('update', $task);

        $request->validate([
            'status' => 'required|in:pending,in_progress,done',
        ]);

        $task->update(['status' => $request->status]);

        return response()->json(['success' => true, 'status' => $request->status]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Task $task, Category $category)
    {
        $this->authorize('delete', $task);
        $task->delete();
        return redirect()->route('categories.show', $category->id)->with('success', 'Task deleted successfully.');
    }
}
