<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->authorize('viewAny', Category::class);
        $categories = auth()->user()->categories;

        return view('categories.index', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->authorize('create', Category::class);
        $validation = $request->validate([
            'name' => 'required',
        ]);
        auth()->user()->categories()->create($validation);

        return redirect()->route('categories.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category)
    {
        $this->authorize('view', $category);
        $tasks = $category->tasks;

        return view('categories.show', compact('category', 'tasks'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Category $category)
    {
        $this->authorize('update', $category);
        $validation = $request->validate([
            'name' => 'required',
        ]);
        $category->update($validation);

        return redirect()->route('categories.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        $this->authorize('delete', $category);
        $category->delete();

        return redirect()->route('categories.index');
    }
}
