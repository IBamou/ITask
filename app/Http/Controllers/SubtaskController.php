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
        $validation = $request->validate([
            'task' => 'required'
        ]);

        $task->subtasks()->create($validation);
        return redirect()->route('categories.show', $task->category->id);
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
    public function update(Request $request, Subtask $subtask)
    {
        $validation = $request->validate([
            'task' => 'required',
        ]);

        $subtask->update($validation);

        return redirect()->route('categories.show', $subtask->task->category->id);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Subtask $subtask)
    {
        $categoryId = $subtask->task->category->id;
        $subtask->delete();
        return redirect()->route('categories.show', $categoryId);
    }
}
