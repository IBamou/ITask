<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TaskController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [TaskController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::controller(CategoryController::class)->prefix('categories')->group(function () {
        Route::get('/', 'index')->name('categories.index');
        Route::post('/store', 'store')->name('categories.store');
        Route::get('/{category}', 'show')->name('categories.show');
        Route::put('/{category}/update', 'update')->name('categories.update');
        Route::delete('/{category}/delete', 'destroy')->name('categories.delete');
    });

    Route::controller(TaskController::class)->prefix('categories')->group(function () {
        Route::post('/{category}/task-store', 'store')->name('categories.task.store');
        Route::put('/{category}/{task}/update', 'update')->name('categories.task.update');
        Route::delete('/{category}/{task}/delete', 'destroy')->name('categories.task.delete');
    });
});

require __DIR__.'/auth.php';
