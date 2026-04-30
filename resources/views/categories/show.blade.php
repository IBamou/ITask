<x-app-layout>
    <!-- Toast Notification -->
    <div id="toast" class="fixed top-4 right-4 bg-green-600 text-white px-4 py-2 rounded-lg shadow-lg hidden z-[100]">
        <span id="toast-message"></span>
    </div>

    <script>
    function showToast(message) {
        var toast = document.getElementById('toast');
        var toastMessage = document.getElementById('toast-message');
        toastMessage.textContent = message;
        toast.classList.remove('hidden');
        setTimeout(function() {
            toast.classList.add('hidden');
        }, 3000);
    }
    </script>

    <!-- Global Delete Popup -->
    <div id="global-delete-popup" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl p-6 max-w-sm w-full mx-4">
            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Confirm Delete</h3>
            <p class="text-gray-600 dark:text-gray-400 mb-6">Are you sure you want to delete this item?</p>
            <div class="flex justify-end gap-3">
                <button type="button" onclick="closeDeletePopup()" class="px-4 py-2 bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-gray-200 rounded-lg hover:bg-gray-300 dark:hover:bg-gray-600">Cancel</button>
                <button type="button" id="confirm-delete-btn" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700">Delete</button>
            </div>
        </div>
    </div>

    <script>
    var deleteCallback = null;
    
    function showDeletePopup(callback) {
        deleteCallback = callback;
        document.getElementById('global-delete-popup').classList.remove('hidden');
        document.getElementById('confirm-delete-btn').onclick = function() {
            if (deleteCallback) deleteCallback();
            closeDeletePopup();
        };
    }
    
    function closeDeletePopup() {
        document.getElementById('global-delete-popup').classList.add('hidden');
        deleteCallback = null;
    }
    </script>

    <x-slot name="header">
        <div class="flex justify-between items-center">
            <a href="{{ route('categories.index') }}" class="text-sm text-gray-500 hover:text-gray-700">
                &larr; {{ __('Categories') }}
            </a>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight absolute left-1/2 transform -translate-x-1/2">
                {{ $category->name }}
            </h2>
            <x-primary-button x-data="" x-on:click="$dispatch('open-modal', 'create-task')">
                {{ __('Add Task') }}
            </x-primary-button>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if($tasks->count() > 0)
                <div class="flex gap-1 p-1 rounded-full shadow-sm bg-indigo-600 mb-4" id="pillNav2">
                    <button class="filter-btn nav-link rounded-full px-4 py-2 text-sm font-medium transition-all flex-1" data-filter="all">All</button>
                    <button class="filter-btn nav-link rounded-full px-4 py-2 text-sm font-medium transition-all flex-1" data-filter="pending">Pending</button>
                    <button class="filter-btn nav-link rounded-full px-4 py-2 text-sm font-medium transition-all flex-1" data-filter="in_progress">In Progress</button>
                    <button class="filter-btn nav-link rounded-full px-4 py-2 text-sm font-medium transition-all flex-1" data-filter="done">Done</button>
                </div>
            @endif

            @if($tasks->count() > 0)
                <div class="space-y-3" id="tasks-list">
                    @foreach($tasks as $task)
                        <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg p-4 hover:shadow-md transition group task-item {{ $task->status }} {{ $task->status === 'done' ? 'opacity-70' : '' }}" id="task-{{ $task->id }}">
                            <div class="flex items-center gap-4">
                                <button type="button" onclick="toggleTask({{ $task->id }}, '{{ $task->status }}')" class="flex-shrink-0 w-6 h-6 rounded-full border-2 flex items-center justify-center transition
                                    {{ $task->status === 'done' ? 'bg-green-500 border-green-500' : ($task->status === 'in_progress' ? 'border-blue-500' : 'border-gray-300 hover:border-green-500') }}">
                                    @if($task->status === 'done')
                                        <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/>
                                        </svg>
                                    @elseif($task->status === 'in_progress')
                                        <svg class="w-3 h-3 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                    @endif
                                </button>

                                <div class="flex-1 flex items-center gap-3">
                                    <h3 class="text-base font-medium text-gray-900 dark:text-gray-100 {{ $task->status === 'done' ? 'line-through text-gray-400' : '' }}">{{ $task->title }}</h3>
                                    <span class="px-2 py-1 text-xs font-medium rounded-full
                                        @if($task->priority === 'high') bg-red-100 text-red-700
                                        @elseif($task->priority === 'medium') bg-yellow-100 text-yellow-700
                                        @else bg-green-100 text-green-700
                                        @endif">
                                        {{ ucfirst($task->priority) }}
                                    </span>
                                    <span class="px-2 py-1 text-xs font-medium rounded-full
                                        @if($task->status === 'done') bg-emerald-100 text-emerald-700
                                        @elseif($task->status === 'in_progress') bg-blue-100 text-blue-700
                                        @else bg-gray-100 text-gray-700
                                        @endif">
                                        {{ ucfirst(str_replace('_', ' ', $task->status)) }}
                                    </span>
                                </div>

                                @if($task->due_date)
                                    @php
                                        $dueDate = \Carbon\Carbon::parse($task->due_date);
                                        $isOverdue = $dueDate->isPast() && $task->status !== 'done';
                                    @endphp
                                    <div class="flex items-center gap-1 text-xs whitespace-nowrap {{ $isOverdue ? 'text-red-600 font-medium' : 'text-green-600' }}">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                        {{ $dueDate->format('M d') }}
                                        @if($isOverdue)
                                            <span class="ml-1 text-xs">(Overdue)</span>
                                        @endif
                                    </div>
                                @endif

                                @if($task->subtasks->count() > 0)
                                    <div class="flex items-center gap-1 text-xs text-gray-500 whitespace-nowrap">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                        </svg>
                                        {{ $task->subtasks->count() }}
                                    </div>
                                @endif

                                <div class="flex items-center gap-1">
                                    <button x-data="" x-on:click="$dispatch('open-modal', 'view-task-{{ $task->id }}')"
                                            class="p-1.5 text-gray-400 hover:text-indigo-600 hover:bg-indigo-50 rounded transition" title="Edit">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                    </button>
                                    <button type="button" onclick="deleteTask({{ $task->id }})" class="p-1.5 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded transition" title="Delete">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

<script>
                document.addEventListener('DOMContentLoaded', function() {
                    const filterButtons = document.querySelectorAll('.filter-btn');
                    const tasks = document.querySelectorAll('.task-item');

                    // Get filter from URL params
                    const urlParams = new URLSearchParams(window.location.search);
                    const currentFilter = urlParams.get('filter') || 'all';

                    // Auto-select filter based on URL
                    const activeBtn = document.querySelector('[data-filter="' + currentFilter + '"]');
                    filterButtons.forEach(b => {
                        b.classList.remove('bg-white', 'text-indigo-600');
                        b.classList.add('text-white', 'hover:bg-white/20');
                    });
                    if (activeBtn) {
                        activeBtn.classList.remove('text-white', 'hover:bg-white/20');
                        activeBtn.classList.add('bg-white', 'text-indigo-600');
                    }

                    // Show tasks based on filter
                    tasks.forEach(task => {
                        if (currentFilter === 'all' || task.classList.contains(currentFilter)) {
                            task.style.display = 'block';
                        } else {
                            task.style.display = 'none';
                        }
                    });

                    filterButtons.forEach(btn => {
                        btn.addEventListener('click', function() {
                            const filter = this.dataset.filter;

                            filterButtons.forEach(b => {
                                b.classList.remove('bg-white', 'text-indigo-600');
                                b.classList.add('text-white', 'hover:bg-white/20');
                            });
                            this.classList.remove('text-white', 'hover:bg-white/20');
                            this.classList.add('bg-white', 'text-indigo-600');

                            tasks.forEach(task => {
                                if (filter === 'all' || task.classList.contains(filter)) {
                                    task.style.display = 'block';
                                } else {
                                    task.style.display = 'none';
                                }
                            });
                        });
                    });
                });

                function toggleTask(taskId, currentStatus) {
                    const newStatus = currentStatus === 'done' ? 'pending' : 'done';
                    const taskCard = document.getElementById('task-' + taskId);
                    const checkbox = taskCard.querySelector('button[onclick^="toggleTask"]');
                    const title = taskCard.querySelector('h3');
                    const statusBadge = taskCard.querySelector('[class*="bg-emerald-100"], [class*="bg-blue-100"], [class*="bg-gray-100"]');

                    taskCard.classList.add('opacity-50');
                    if (newStatus === 'done') {
                        checkbox.className = checkbox.className.replace('border-gray-300 hover:border-green-500', 'bg-green-500 border-green-500');
                        checkbox.innerHTML = '<svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>';
                        title.classList.add('line-through', 'text-gray-400');
                        if (statusBadge) statusBadge.className = statusBadge.className.replace('bg-blue-100 text-blue-700', 'bg-emerald-100 text-emerald-700').replace('bg-gray-100 text-gray-700', 'bg-emerald-100 text-emerald-700');
                    } else {
                        checkbox.className = checkbox.className.replace('bg-green-500 border-green-500', 'border-gray-300 hover:border-green-500');
                        checkbox.innerHTML = '';
                        title.classList.remove('line-through', 'text-gray-400');
                        if (statusBadge) statusBadge.className = statusBadge.className.replace('bg-emerald-100 text-emerald-700', 'bg-blue-100 text-blue-700');
                    }
                    checkbox.setAttribute('onclick', `toggleTask(${taskId}, '${newStatus}')`);

                    fetch('{{ route('categories.task.toggle', ['category' => $category->id, 'task' => ':taskId']) }}'.replace(':taskId', taskId), {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            status: newStatus
                        })
                    }).then(() => {
                        taskCard.classList.remove('opacity-50');
                        window.location.reload();
                    }).catch(() => {
                        taskCard.classList.remove('opacity-50');
                        if (currentStatus === 'done') {
                            checkbox.className = checkbox.className.replace('border-gray-300 hover:border-green-500', 'bg-green-500 border-green-500');
                            checkbox.innerHTML = '<svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>';
                            title.classList.add('line-through', 'text-gray-400');
                        } else {
                            checkbox.className = checkbox.className.replace('bg-green-500 border-green-500', 'border-gray-300 hover:border-green-500');
                            checkbox.innerHTML = '';
                            title.classList.remove('line-through', 'text-gray-400');
                            if (statusBadge) statusBadge.className = statusBadge.className.replace('bg-emerald-100 text-emerald-700', 'bg-blue-100 text-blue-700');
                        }
                        checkbox.setAttribute('onclick', `toggleTask(${taskId}, '${currentStatus}')`);
                    });
                }

                function deleteTask(taskId) {
                    showDeletePopup(function() {
                        fetch('{{ route('categories.task.delete', ['category' => $category->id, 'task' => ':taskId']) }}'.replace(':taskId', taskId), {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({ _method: 'DELETE' })
                        }).then(() => window.location.reload());
                    });
                }

function deleteTaskFromModal(taskId) {
                    showDeletePopup(function() {
                        fetch('{{ route('categories.task.delete', ['category' => $category->id, 'task' => ':taskId']) }}'.replace(':taskId', taskId), {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({ _method: 'DELETE' })
                        }).then(() => window.location.reload());
                    });
                }

                function saveTask(taskId) {
                    const form = document.getElementById('form-task-' + taskId);
                    const formData = new FormData(form);
                    const data = Object.fromEntries(formData.entries());
                    data._method = 'PUT';

                    fetch('{{ route('categories.task.update', ['category' => $category->id, 'task' => ':taskId']) }}'.replace(':taskId', taskId), {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify(data)
                    }).then(res => {
                        if (res.ok) {
                            showToast('Task saved successfully!');
                        }
                    });
                }
                </script>
            @else
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg p-6 text-center">
                    <p class="text-gray-500 mb-4">{{ __('No tasks yet.') }}</p>
                    <x-primary-button x-data="" x-on:click="$dispatch('open-modal', 'create-task')">
                        {{ __('Create your first task') }}
                    </x-primary-button>
                </div>
            @endif
        </div>
    </div>

    <x-modal name="create-task">
        <div class="p-6">
            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                {{ __('Create Task') }}
            </h2>
            <form method="POST" action="{{ route('categories.task.store', $category->id) }}" class="mt-6 space-y-6" onsubmit="event.preventDefault(); this.submit(); this.querySelector('button[type=submit]').disabled=true;">
                @csrf
                <div>
                    <x-input-label for="title" :value="__('Title')" />
                    <x-text-input id="title" name="title" type="text" class="mt-1 block w-full" required autofocus />
                    <x-input-error class="mt-2" :messages="$errors->get('title')" />
                </div>
                <div>
                    <x-input-label for="description" :value="__('Description')" />
                    <textarea id="description" name="description" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" rows="3"></textarea>
                    <x-input-error class="mt-2" :messages="$errors->get('description')" />
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <x-input-label for="priority" :value="__('Priority')" />
                        <select id="priority" name="priority" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
                            <option value="low">{{ __('Low') }}</option>
                            <option value="medium">{{ __('Medium') }}</option>
                            <option value="high">{{ __('High') }}</option>
                        </select>
                    </div>
                    <div>
                        <x-input-label for="due_date" :value="__('Due Date')" />
                        <x-text-input id="due_date" name="due_date" type="date" class="mt-1 block w-full" />
                    </div>
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

    @foreach($tasks as $task)
        <x-modal name="view-task-{{ $task->id }}" maxWidth="2xl">
            <div class="p-6">
                <div class="flex justify-between items-start mb-6">
                    <h2 class="text-xl font-semibold text-gray-900 dark:text-gray-100">
                        {{ $task->title }}
                    </h2>
                    <button x-on:click="$dispatch('close')" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                <form id="form-task-{{ $task->id }}" class="space-y-6" onsubmit="event.preventDefault();">
                    @csrf
                    @method('put')

                    <div>
                        <x-input-label for="title-{{ $task->id }}" :value="__('Title')" />
                        <x-text-input id="title-{{ $task->id }}" name="title" type="text" class="mt-1 block w-full" :value="$task->title" required />
                    </div>

                    <div>
                        <x-input-label for="description-{{ $task->id }}" :value="__('Description')" />
                        <textarea id="description-{{ $task->id }}" name="description" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" rows="3">{{ $task->description }}</textarea>
                    </div>

                    <div class="grid grid-cols-3 gap-4">
                        <div>
                            <x-input-label for="status-{{ $task->id }}" :value="__('Status')" />
                            <select id="status-{{ $task->id }}" name="status" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
                                <option value="pending" {{ $task->status === 'pending' ? 'selected' : '' }}>{{ __('Pending') }}</option>
                                <option value="in_progress" {{ $task->status === 'in_progress' ? 'selected' : '' }}>{{ __('In Progress') }}</option>
                                <option value="done" {{ $task->status === 'done' ? 'selected' : '' }}>{{ __('Done') }}</option>
                            </select>
                        </div>
                        <div>
                            <x-input-label for="priority-{{ $task->id }}" :value="__('Priority')" />
                            <select id="priority-{{ $task->id }}" name="priority" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
                                <option value="low" {{ $task->priority === 'low' ? 'selected' : '' }}>{{ __('Low') }}</option>
                                <option value="medium" {{ $task->priority === 'medium' ? 'selected' : '' }}>{{ __('Medium') }}</option>
                                <option value="high" {{ $task->priority === 'high' ? 'selected' : '' }}>{{ __('High') }}</option>
                            </select>
                        </div>
                        <div>
                            <x-input-label for="due_date-{{ $task->id }}" :value="__('Due Date')" />
                            <x-text-input id="due_date-{{ $task->id }}" name="due_date" type="date" class="mt-1 block w-full" :value="$task->due_date" />
                        </div>
                    </div>

<div class="border-t pt-4">
                        <h3 class="font-medium text-gray-900 mb-3">{{ __('Subtasks') }}</h3>
                        
                        @if($task->subtasks->count() > 0)
                            <div class="space-y-2 mb-4" id="subtasks-list-{{ $task->id }}">
                                @foreach($task->subtasks as $subtask)
                                    <div class="flex items-center gap-2 bg-gray-50 p-2 rounded" id="subtask-row-{{ $subtask->id }}">
                                        <input type="checkbox" {{ $subtask->done ? 'checked' : '' }} onchange="toggleSubtaskDone({{ $subtask->id }}, this.checked)" class="w-4 h-4 text-indigo-600 rounded border-gray-300 focus:ring-indigo-600" />
                                        <span class="flex-1 text-sm text-gray-700 subtask-text {{ $subtask->done ? 'line-through text-gray-400' : '' }}" id="subtask-text-{{ $subtask->id }}">{{ $subtask->task }}</span>
                                        <input type="text" class="flex-1 text-sm text-gray-700 border-gray-300 rounded hidden" id="subtask-input-{{ $subtask->id }}" value="{{ $subtask->task }}" />
                                        <button type="button" onclick="toggleEditSubtask({{ $subtask->id }})" class="text-gray-400 hover:text-gray-600 edit-btn" id="edit-btn-{{ $subtask->id }}">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                            </svg>
                                        </button>
                                        <button type="button" onclick="saveSubtask({{ $subtask->id }})" class="text-green-500 hover:text-green-700 hidden save-btn" id="save-btn-{{ $subtask->id }}">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                            </svg>
                                        </button>
                                        <button type="button" onclick="toggleEditSubtask({{ $subtask->id }})" class="text-red-400 hover:text-red-600 hidden cancel-btn" id="cancel-btn-{{ $subtask->id }}">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                            </svg>
                                        </button>
                                        <button type="button" onclick="deleteSubtask({{ $subtask->id }})" class="text-red-400 hover:text-red-600 delete-btn" id="delete-btn-{{ $subtask->id }}">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                        </button>
                                    </div>
                                @endforeach
                            </div>
                            <script>
                            function toggleEditSubtask(id) {
                                var text = document.getElementById('subtask-text-' + id);
                                var input = document.getElementById('subtask-input-' + id);
                                var editBtn = document.getElementById('edit-btn-' + id);
                                var saveBtn = document.getElementById('save-btn-' + id);
                                var cancelBtn = document.getElementById('cancel-btn-' + id);
                                var deleteBtn = document.getElementById('delete-btn-' + id);
                                
                                if (text.classList.contains('hidden')) {
                                    // Cancel edit - revert
                                    text.classList.remove('hidden');
                                    input.classList.add('hidden');
                                    editBtn.classList.remove('hidden');
                                    saveBtn.classList.add('hidden');
                                    cancelBtn.classList.add('hidden');
                                    deleteBtn.classList.remove('hidden');
                                } else {
                                    // Start edit
                                    input.value = text.textContent.trim();
                                    text.classList.add('hidden');
                                    input.classList.remove('hidden');
                                    editBtn.classList.add('hidden');
                                    saveBtn.classList.remove('hidden');
                                    cancelBtn.classList.remove('hidden');
                                    deleteBtn.classList.add('hidden');
                                }
                            }
                            
                            function saveSubtask(id) {
                                var input = document.getElementById('subtask-input-' + id);
                                var newTask = input.value.trim();
                                if (!newTask) return;
                                
                                fetch('/tasks/subtask/' + id + '/update', {
                                    method: 'PUT',
                                    headers: {
                                        'Content-Type': 'application/json',
                                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                    },
                                    body: JSON.stringify({ task: newTask })
                                }).then(function(res) {
                                    var text = document.getElementById('subtask-text-' + id);
                                    text.textContent = newTask;
                                    toggleEditSubtask(id);
                                });
                            }
                            
                            function toggleSubtaskDone(id, done) {
                                var text = document.getElementById('subtask-text-' + id);
                                if (done) {
                                    text.classList.add('line-through', 'text-gray-400');
                                } else {
                                    text.classList.remove('line-through', 'text-gray-400');
                                }
                                
                                fetch('/tasks/subtask/' + id + '/toggle', {
                                    method: 'PUT',
                                    headers: {
                                        'Content-Type': 'application/json',
                                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                    },
                                    body: JSON.stringify({ done: done })
                                });
                            }
                            
                            function deleteSubtask(id) {
                                showDeletePopup(function() {
                                    fetch('/tasks/subtask/' + id + '/delete', {
                                        method: 'DELETE',
                                        headers: {
                                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                        }
                                    }).then(function() {
                                        var row = document.getElementById('subtask-row-' + id);
                                        row.remove();
                                        showToast('Subtask deleted!');
                                    });
                                });
                            }
                            </script>
                        @else
                            <p class="text-sm text-gray-500 mb-3">No subtasks yet.</p>
                        @endif

                        <div class="flex gap-2" id="subtask-form-{{ $task->id }}">
                            <input type="text" id="subtask-input-{{ $task->id }}" class="flex-1 border-gray-300 rounded-md shadow-sm" placeholder="Add a subtask..." />
                            <button type="button" id="add-subtask-btn-{{ $task->id }}" class="px-3 py-2 bg-gray-800 text-white rounded-md">+</button>
                        </div>
                    </div>

                    <script>
                    document.getElementById('add-subtask-btn-{{ $task->id }}').addEventListener('click', function() {
                        var btn = this;
                        var input = document.getElementById('subtask-input-{{ $task->id }}');
                        var task = input.value.trim();
                        if (!task) {
                            alert('Please enter a subtask');
                            return;
                        }
                        
                        console.log('Adding subtask:', task, 'for task:', {{ $task->id }});
                        
                        fetch('/tasks/{{ $task->id }}/subtask-store', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({ task: task })
                        }).then(function(res) {
                            return res.json();
                        }).then(function(data) {
                            showToast('Subtask added successfully!');
                            input.value = '';
                            // Add new subtask to DOM without reload
                            var container = document.getElementById('subtasks-list-{{ $task->id }}');
                            if (container) {
                                var noSubtasksMsg = container.parentElement.querySelector('.text-sm.text-gray-500');
                                if (noSubtasksMsg) noSubtasksMsg.remove();
                                var newRow = document.createElement('div');
                                newRow.className = 'flex items-center gap-2 bg-gray-50 p-2 rounded';
                                newRow.id = 'subtask-row-' + data.subtask.id;
                                newRow.innerHTML = '<input type="checkbox" onchange="toggleSubtaskDone(' + data.subtask.id + ', this.checked)" class="w-4 h-4 text-indigo-600 rounded border-gray-300 focus:ring-indigo-600" /><span class="flex-1 text-sm text-gray-700" id="subtask-text-' + data.subtask.id + '">' + task + '</span><input type="text" class="flex-1 text-sm text-gray-700 border-gray-300 rounded hidden" id="subtask-input-' + data.subtask.id + '" value="' + task + '" /><button type="button" onclick="toggleEditSubtask(' + data.subtask.id + ')" class="text-gray-400 hover:text-gray-600 edit-btn" id="edit-btn-' + data.subtask.id + '"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg></button><button type="button" onclick="saveSubtask(' + data.subtask.id + ')" class="text-green-500 hover:text-green-700 hidden save-btn" id="save-btn-' + data.subtask.id + '"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg></button><button type="button" onclick="toggleEditSubtask(' + data.subtask.id + ')" class="text-red-400 hover:text-red-600 hidden cancel-btn" id="cancel-btn-' + data.subtask.id + '"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></button><button type="button" onclick="deleteSubtask(' + data.subtask.id + ')" class="text-red-400 hover:text-red-600 delete-btn" id="delete-btn-' + data.subtask.id + '"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg></button>';
                                container.appendChild(newRow);
                            } else {
                                window.location.reload();
                            }
                        }).catch(function(err) {
                            console.error('Error:', err);
                        });
                    });
                    </script>

                    <div class="flex justify-between items-center pt-4 border-t">
                        <button type="button" onclick="deleteTaskFromModal({{ $task->id }})" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700">
                                {{ __('Delete Task') }}
                            </button>
                        <div class="flex gap-2">
                            <x-secondary-button x-on:click="$dispatch('close')">
                                {{ __('Back') }}
                            </x-secondary-button>
                            <x-primary-button type="button" onclick="saveTask({{ $task->id }})">
                                {{ __('Save Changes') }}
                            </x-primary-button>
                        </div>
                    </div>
                </form>
            </div>
        </x-modal>
    @endforeach
</x-app-layout>
