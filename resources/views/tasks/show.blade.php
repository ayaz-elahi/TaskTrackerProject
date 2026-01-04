<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Task Details') }}
            </h2>
            <a href="{{ route('tasks.index') }}" class="text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100">
                ← Back to Tasks
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex justify-between items-start mb-6">
                        <div>
                            <h1 class="text-3xl font-bold text-gray-900 dark:text-gray-100 mb-2">
                                {{ $task->title }}
                            </h1>
                            <div class="flex space-x-2">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-{{ $task->priority_color }}-100 text-{{ $task->priority_color }}-800">
                                    {{ ucfirst($task->priority) }} Priority
                                </span>
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium 
                                    @if($task->is_completed) bg-green-100 text-green-800 
                                    @else bg-gray-100 text-gray-800 
                                    @endif">
                                    {{ $task->is_completed ? 'Completed' : ucfirst($task->status) }}
                                </span>
                            </div>
                        </div>
                    </div>

                    @if($task->description)
                        <div class="mb-6">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-2">Description</h3>
                            <p class="text-gray-700 dark:text-gray-300 whitespace-pre-line">{{ $task->description }}</p>
                        </div>
                    @endif

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                        @if($task->deadline)
                            <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                                <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Deadline</h4>
                                <p class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                                    {{ $task->deadline->format('F d, Y') }}
                                </p>
                                <p class="text-sm text-gray-600 dark:text-gray-400">
                                    {{ $task->deadline->format('h:i A') }}
                                </p>
                            </div>
                        @endif

                        <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                            <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Created</h4>
                            <p class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                                {{ $task->created_at->format('F d, Y') }}
                            </p>
                            <p class="text-sm text-gray-600 dark:text-gray-400">
                                {{ $task->created_at->diffForHumans() }}
                            </p>
                        </div>
                    </div>

                    <div class="flex flex-wrap gap-3 border-t dark:border-gray-700 pt-6">
                        <form method="POST" action="{{ route('tasks.toggle-complete', $task) }}" class="inline">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest 
                                @if($task->is_completed) 
                                    bg-gray-600 hover:bg-gray-700 
                                @else 
                                    bg-green-600 hover:bg-green-700 
                                @endif 
                                focus:outline-none focus:ring-2 focus:ring-offset-2 transition ease-in-out duration-150">
                                @if($task->is_completed)
                                    Mark as Incomplete
                                @else
                                    Mark as Complete
                                @endif
                            </button>
                        </form>

                        <a href="{{ route('tasks.edit', $task) }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            Edit Task
                        </a>

                        <form method="POST" action="{{ route('tasks.destroy', $task) }}" class="inline" onsubmit="return confirm('Are you sure you want to delete this task?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                Delete Task
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>