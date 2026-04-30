<?php

namespace App\Http\Controllers;

use App\Models\Subtask;
use App\Models\Task;
use Illuminate\Http\Request;

class SubtaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Task $task)
    {
        // Check if user owns the task's category
        if ($task->category->user_id !== auth()->id()) {
            abort(403, 'You do not own this task');
        }
        
        $validation = $request->validate([
            'task' => 'required'
        ]);

        \Log::info('Creating subtask', [
            'task_id' => $task->id,
            'subtask_data' => $validation
        ]);

        $subtask = $task->subtasks()->create($validation);

        return response()->json(['message' => 'Subtask created successfully.', 'subtask' => $subtask]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Subtask $subtask)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Subtask $subtask)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Task $task, string $subtask)
    {
        $subtask = Subtask::findOrFail($subtask);
        $this->authorize('update', $subtask);
        $validation = $request->validate([
            'task' => 'required',
        ]);

        $subtask->update($validation);

        return redirect()->route('categories.show', $subtask->task()->first()->category->id)->with('success', 'Subtask updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Task $task, string $subtask)
    {
        $subtask = Subtask::findOrFail($subtask);
        $this->authorize('delete', $subtask);
        $categoryId = $subtask->task()->first()->category->id;
        $subtask->delete();
        return redirect()->route('categories.show', $categoryId)->with('success', 'Subtask deleted successfully.');
    }

    /**
     * Toggle subtask done status.
     */
    public function toggle(Request $request, string $subtask)
    {
        $subtask = Subtask::findOrFail($subtask);
        
        if ($subtask->task->category->user_id !== auth()->id()) {
            abort(403, 'You do not own this task');
        }

        $subtask->update(['done' => $request->done]);
        
        return response()->json(['success' => true, 'done' => $subtask->done]);
    }
}
