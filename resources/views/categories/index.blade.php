<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Categories') }}
            </h2>
            <x-primary-button x-data="" x-on:click="$dispatch('open-modal', 'create-category')">
                {{ __('Add Category') }}
            </x-primary-button>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if($categories->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach($categories as $category)
                        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-5 hover:shadow-md transition relative">
                            <div class="flex justify-between items-start mb-3">
                                <a href="{{ route('categories.show', $category->id) }}" class="text-lg font-semibold text-gray-900 dark:text-gray-100 hover:text-blue-600">
                                    {{ $category->name }}
                                </a>
                                <div class="flex items-center gap-1">
                                    <button x-data="" x-on:click="$dispatch('open-modal', 'edit-category-{{ $category->id }}')" class="p-1 text-gray-400 hover:text-indigo-600 rounded">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                    </button>
                                    <button type="button" onclick="showDeletePopup(function() { document.getElementById('delete-category-form-{{ $category->id }}').submit(); })" class="p-1 text-gray-400 hover:text-red-600 rounded">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                    <form id="delete-category-form-{{ $category->id }}" method="POST" action="{{ route('categories.delete', $category->id) }}" class="hidden">
                                        @csrf
                                        @method('delete')
                                    </form>
                                </div>
                            </div>
                            @php
                                $totalTasks = $category->tasks->count();
                                $doneTasks = $category->tasks->where('status', 'done')->count();
                                $progressPercent = $totalTasks > 0 ? ($doneTasks / $totalTasks) * 100 : 0;
                            @endphp
                            <div class="space-y-2">
                                <a href="{{ route('categories.show', $category->id) }}" class="flex items-center justify-between text-sm hover:bg-gray-50 dark:hover:bg-gray-700 -mx-2 px-2 py-1 rounded">
                                    <span class="text-gray-500 dark:text-gray-400">Pending</span>
                                    <span class="px-2 py-0.5 bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 rounded-full text-xs font-medium">
                                        {{ $category->tasks->where('status', 'pending')->count() }}
                                    </span>
                                </a>
                                <a href="{{ route('categories.show', $category->id) }}?filter=in_progress" class="flex items-center justify-between text-sm hover:bg-gray-50 dark:hover:bg-gray-700 -mx-2 px-2 py-1 rounded">
                                    <span class="text-blue-600">In Progress</span>
                                    <span class="px-2 py-0.5 bg-blue-100 text-blue-700 rounded-full text-xs font-medium">
                                        {{ $category->tasks->where('status', 'in_progress')->count() }}
                                    </span>
                                </a>
                                <a href="{{ route('categories.show', $category->id) }}?filter=done" class="flex items-center justify-between text-sm hover:bg-gray-50 dark:hover:bg-gray-700 -mx-2 px-2 py-1 rounded">
                                    <span class="text-green-600">Done</span>
                                    <span class="px-2 py-0.5 bg-green-100 text-green-700 rounded-full text-xs font-medium">
                                        {{ $category->tasks->where('status', 'done')->count() }}
                                    </span>
                                </a>
                            </div>
                            @if($totalTasks > 0)
                                <div class="mt-3">
                                    <div class="flex justify-between text-xs text-gray-500 mb-1">
                                        <span>Progress</span>
                                        <span>{{ round($progressPercent) }}%</span>
                                    </div>
                                    <div class="w-full bg-gray-200 rounded-full h-2">
                                        <div class="bg-green-600 h-2 rounded-full transition-all duration-300" style="width: {{ $progressPercent }}%"></div>
                                    </div>
                                </div>
                            @endif
                            <div class="mt-3 pt-3 border-t border-gray-100 dark:border-gray-700">
                                <a href="{{ route('categories.show', $category->id) }}" class="text-sm text-blue-600 font-medium hover:underline">View Tasks &rarr;</a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6 text-center">
                    <p class="text-gray-500 dark:text-gray-400 mb-4">{{ __('No categories yet.') }}</p>
                    <x-primary-button x-data="" x-on:click="$dispatch('open-modal', 'create-category')">
                        {{ __('Create your first category') }}
                    </x-primary-button>
                </div>
            @endif
        </div>
    </div>

    <x-modal name="create-category">
        <div class="p-6">
            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                {{ __('Create Category') }}
            </h2>
            <form method="POST" action="{{ route('categories.store') }}" class="mt-6 space-y-6">
                @csrf
                <div>
                    <x-input-label for="name" :value="__('Name')" />
                    <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" required autofocus />
                    <x-input-error class="mt-2" :messages="$errors->get('name')" />
                </div>
                <div class="flex justify-end">
                    <x-secondary-button x-on:click="$dispatch('close')">
                        {{ __('Cancel') }}
                    </x-secondary-button>
                    <x-primary-button class="ms-3">
                        {{ __('Create') }}
                    </x-primary-button>
                </div>
            </form>
        </div>
    </x-modal>

    @foreach($categories as $category)
        <x-modal name="edit-category-{{ $category->id }}">
            <div class="p-6">
                <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                    {{ __('Edit Category') }}
                </h2>
                <form method="POST" action="{{ route('categories.update', $category->id) }}" class="mt-6 space-y-6">
                    @csrf
                    @method('put')
                    <div>
                        <x-input-label for="name-{{ $category->id }}" :value="__('Name')" />
                        <x-text-input id="name-{{ $category->id }}" name="name" type="text" class="mt-1 block w-full" :value="$category->name" required autofocus />
                        <x-input-error class="mt-2" :messages="$errors->get('name')" />
                    </div>
                    <div class="flex justify-end">
                        <x-secondary-button x-on:click="$dispatch('close')">
                            {{ __('Cancel') }}
                        </x-secondary-button>
                        <x-primary-button class="ms-3">
                            {{ __('Update') }}
                        </x-primary-button>
                    </div>
                </form>
            </div>
        </x-modal>
    @endforeach
</x-app-layout>