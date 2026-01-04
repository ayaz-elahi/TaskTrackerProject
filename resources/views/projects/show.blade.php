<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight flex items-center gap-2">
                    {{ $project->name }}
                    <span class="text-sm font-normal text-gray-500 dark:text-gray-400">
                        ({{ $project->owner_id === Auth::id() ? 'Owner' : 'Member' }})
                    </span>
                </h2>
                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">{{ $project->description }}</p>
            </div>
            
            <div class="flex items-center gap-2">
                @if($project->owner_id === Auth::id())
                    <a href="{{ route('projects.edit', $project) }}" class="inline-flex items-center px-4 py-2 bg-gray-200 dark:bg-gray-700 border border-transparent rounded-md font-semibold text-xs text-gray-800 dark:text-white uppercase tracking-widest hover:bg-gray-300 dark:hover:bg-gray-600 transition ease-in-out duration-150">
                        Settings
                    </a>
                @else
                   <form method="POST" action="{{ route('projects.leave', $project) }}" onsubmit="return confirm('Are you sure you want to leave this project?')">
                        @csrf
                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-red-100 dark:bg-red-900 border border-transparent rounded-md font-semibold text-xs text-red-800 dark:text-red-200 uppercase tracking-widest hover:bg-red-200 dark:hover:bg-red-800 transition ease-in-out duration-150">
                            Leave Project
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <!-- Tasks Section -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white">Tasks</h3>
                        <!-- Link to Create Task with Project Pre-selected (would need query param logic in controller, simpler to just link to create) -->
                        <a href="{{ route('tasks.create') }}" class="text-sm text-indigo-600 dark:text-indigo-400 hover:underline">Add Task (Select Project manually)</a>
                    </div>
                    
                    @if($project->tasks->isEmpty())
                        <p class="text-gray-500 dark:text-gray-400 text-sm italic">No tasks yet.</p>
                    @else
                        <ul class="divide-y divide-gray-100 dark:divide-gray-700">
                            @foreach($project->tasks as $task)
                                <li class="py-3 flex justify-between items-center">
                                    <div>
                                        <a href="{{ route('tasks.show', $task) }}" class="text-gray-800 dark:text-gray-200 hover:underline font-medium">{{ $task->title }}</a>
                                        <div class="flex gap-2 text-xs mt-1">
                                            <span class="px-2 py-0.5 rounded-full {{ $task->status === 'completed' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                                {{ ucfirst(str_replace('_', ' ', $task->status)) }}
                                            </span>
                                            @if($task->assigned_to)
                                                <span class="text-gray-500">Assigned: {{ $task->assignee->name }}</span>
                                            @endif
                                        </div>
                                    </div>
                                    <span class="text-xs text-gray-400">{{ $task->deadline ? $task->deadline->format('M d') : '' }}</span>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            </div>

            <!-- Members Section -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white">Members</h3>
                        
                        <!-- Invite Form (Owner Only) -->
                        @if($project->owner_id === Auth::id())
                            <form method="POST" action="{{ route('projects.invite', $project) }}" class="flex gap-2">
                                @csrf
                                <input type="email" name="email" placeholder="Invite by email..." class="text-sm border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-white rounded-md focus:border-indigo-500 focus:ring-indigo-500" required>
                                <button type="submit" class="px-3 py-1 bg-indigo-600 text-white rounded-md text-sm hover:bg-indigo-700">Invite</button>
                            </form>
                        @endif
                    </div>

                    <ul class="divide-y divide-gray-100 dark:divide-gray-700">
                        @foreach($project->users as $user)
                            <li class="py-3 flex justify-between items-center">
                                <div class="flex items-center gap-3">
                                    <div class="h-8 w-8 rounded-full bg-indigo-100 dark:bg-indigo-900 flex items-center justify-center text-indigo-600 dark:text-indigo-300 font-bold">
                                        {{ substr($user->name, 0, 1) }}
                                    </div>
                                    <div>
                                        <div class="text-gray-800 dark:text-gray-200 font-medium">{{ $user->name }}</div>
                                        <div class="text-xs text-gray-500">{{ $user->email }}</div>
                                    </div>
                                    @if($user->id === $project->owner_id)
                                        <span class="text-xs bg-indigo-100 text-indigo-800 px-2 py-0.5 rounded-full ml-2">Owner</span>
                                    @endif
                                </div>
                                
                                @if($project->owner_id === Auth::id() && $user->id !== Auth::id())
                                    <form method="POST" action="{{ route('projects.members.remove', [$project, $user]) }}" onsubmit="return confirm('Remove this member?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-500 hover:text-red-700 text-sm font-medium">Remove</button>
                                    </form>
                                @endif
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
