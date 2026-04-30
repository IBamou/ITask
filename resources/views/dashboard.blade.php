<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @php
                $categories = auth()->user()->categories;
                $totalTasks = $categories->flatMap->tasks;
                $pendingTasks = $totalTasks->where('status', 'pending');
                $inProgressTasks = $totalTasks->where('status', 'in_progress');
                $doneTasks = $totalTasks->where('status', 'done');
                $overdueTasks = $totalTasks->filter(function($task) {
                    return $task->due_date && \Carbon\Carbon::parse($task->due_date)->isPast() && $task->status !== 'done';
                });
            @endphp

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="flex items-center">
                        <div class="p-3 bg-blue-100 rounded-full">
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm text-gray-500 dark:text-gray-400">Total Tasks</p>
                            <p class="text-2xl font-bold text-gray-900 dark:text-gray-100">{{ $totalTasks->count() }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="flex items-center">
                        <div class="p-3 bg-yellow-100 rounded-full">
                            <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm text-gray-500 dark:text-gray-400">Pending</p>
                            <p class="text-2xl font-bold text-gray-900 dark:text-gray-100">{{ $pendingTasks->count() }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="flex items-center">
                        <div class="p-3 bg-indigo-100 rounded-full">
                            <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm text-gray-500 dark:text-gray-400">In Progress</p>
                            <p class="text-2xl font-bold text-gray-900 dark:text-gray-100">{{ $inProgressTasks->count() }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="flex items-center">
                        <div class="p-3 bg-green-100 rounded-full">
                            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm text-gray-500 dark:text-gray-400">Completed</p>
                            <p class="text-2xl font-bold text-gray-900 dark:text-gray-100">{{ $doneTasks->count() }}</p>
                        </div>
                    </div>
                </div>
            </div>

            @if($overdueTasks->count() > 0)
                <div class="bg-red-50 dark:bg-red-900/20 border-l-4 border-red-500 p-4 mb-8 rounded-r-lg">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-red-700 dark:text-red-200">
                                You have <strong>{{ $overdueTasks->count() }}</strong> overdue task(s)! 
                                <a href="{{ route('categories.index') }}" class="underline font-medium">View now</a>
                            </p>
                        </div>
                    </div>
                </div>
            @endif

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Your Categories</h3>
                        <a href="{{ route('categories.index') }}" class="text-sm text-blue-600 hover:underline">View all</a>
                    </div>

                    @if($categories->count() > 0)
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                            @foreach($categories->take(6) as $category)
                                @php
                                    $overdueCount = $category->tasks->filter(function($task) {
                                        return $task->due_date && \Carbon\Carbon::parse($task->due_date)->isPast() && $task->status !== 'done';
                                    })->count();
                                @endphp
                                <a href="{{ route('categories.show', $category->id) }}" class="block p-4 bg-gray-50 dark:bg-gray-700 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-600 transition">
                                    <div class="flex justify-between items-start">
                                        <h4 class="font-semibold text-gray-900 dark:text-gray-100">{{ $category->name }}</h4>
                                        @if($overdueCount > 0)
                                            <span class="px-2 py-0.5 bg-red-100 text-red-700 rounded-full text-xs font-medium">{{ $overdueCount }} overdue</span>
                                        @endif
                                    </div>
                                    <div class="flex items-center gap-2 mt-2 text-sm text-gray-500">
                                        <span class="px-2 py-0.5 bg-gray-200 dark:bg-gray-600 rounded-full">{{ $category->tasks->where('status', 'pending')->count() }} pending</span>
                                        <span class="px-2 py-0.5 bg-blue-100 rounded-full">{{ $category->tasks->where('status', 'in_progress')->count() }} in progress</span>
                                        <span class="px-2 py-0.5 bg-green-100 rounded-full">{{ $category->tasks->where('status', 'done')->count() }} done</span>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    @else
                        <p class="text-gray-500 text-center py-4">No categories yet. 
                            <a href="{{ route('categories.index') }}" class="text-blue-600 hover:underline">Create your first category</a>
                        </p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>