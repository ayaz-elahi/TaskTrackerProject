<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Create New Task') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <form method="POST" action="{{ route('tasks.store') }}">
                        @csrf

                        <div class="mb-4">
                            <x-input-label for="title" :value="__('Task Title')" />
                            <x-text-input id="title" class="block mt-1 w-full" type="text" name="title" :value="old('title')" required autofocus />
                            <x-input-error :messages="$errors->get('title')" class="mt-2" />
                        </div>

                        <div class="mb-4">
                            <x-input-label for="description" :value="__('Description')" />
                            <textarea id="description" name="description" rows="4" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">{{ old('description') }}</textarea>
                            <x-input-error :messages="$errors->get('description')" class="mt-2" />
                        </div>

                        <div class="mb-4">
                            <x-input-label for="deadline" :value="__('Deadline (Optional)')" />
                            <x-text-input id="deadline" class="block mt-1 w-full" type="datetime-local" name="deadline" :value="old('deadline')" />
                            <x-input-error :messages="$errors->get('deadline')" class="mt-2" />
                        </div>

                        <div class="mb-4">
                            <x-input-label for="priority" :value="__('Priority')" />
                            <select id="priority" name="priority" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" required>
                                <option value="low" {{ old('priority') == 'low' ? 'selected' : '' }}>Low</option>
                                <option value="medium" {{ old('priority') == 'medium' ? 'selected' : '' }} selected>Medium</option>
                                <option value="high" {{ old('priority') == 'high' ? 'selected' : '' }}>High</option>
                            </select>
                            <x-input-error :messages="$errors->get('priority')" class="mt-2" />
                        </div>

                        <!-- Project Selection -->
                         <div class="mb-4">
                            <x-input-label for="project_id" :value="__('Project (Optional)')" />
                            <select id="project_id" name="project_id" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                                <option value="">None</option>
                                @foreach($allProjects as $project)
                                    <option value="{{ $project->id }}" {{ old('project_id') == $project->id ? 'selected' : '' }}>{{ $project->name }}</option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('project_id')" class="mt-2" />
                        </div>

                        <!-- Assignee Selection -->
                        <div class="mb-4">
                            <x-input-label for="assigned_to" :value="__('Assign To (Optional)')" />
                            <select id="assigned_to" name="assigned_to" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                                <option value="">Unassigned</option>
                                @foreach($projectUsers as $user)
                                    @if($user->id !== Auth::id())
                                        <option value="{{ $user->id }}" {{ old('assigned_to') == $user->id ? 'selected' : '' }}>
                                            {{ $user->name }} ({{ $user->email }})
                                        </option>
                                    @endif
                                @endforeach
                            </select>
                            <p class="text-xs text-gray-500 mt-1">Note: User must be a member of the selected project.</p>
                            <x-input-error :messages="$errors->get('assigned_to')" class="mt-2" />
                        </div>

                         <!-- Assignee Selection (Simple JS logic needed to filter by project ideally, but strictly not required for MVP) -->
                         <!-- NOTE: For MVP, we can just allow assigning to anyone if we wanted, but contextually it makes sense to only show if a project is selected. 
                              However, since we don't have extensive JS here, let's just show a text hint or leave it simple for now. 
                              Actually, standard Laravel way: All users? No, only project members.
                              To keep it simple without heavy JS: We will skip Assignee on CREATE for now unless we add dynamic fetching. 
                              TaskController::store() allows it, but UI is tricky without JS.
                              Let's stick to creating the task first, then editing it to assign, OR just let them assign later. 
                              Wait, I can just create the task and then edit it. 
                              Actually, let's add it but purely as a text input for ID or just skip it?
                              Better: Skip "Assignee" on Create to keep UI simple. User can assign in Edit view or Project view.
                          -->

                        <div class="flex items-center justify-end mt-6 space-x-4">
                            <a href="{{ route('tasks.index') }}" class="text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100">
                                Cancel
                            </a>
                            <x-primary-button>
                                {{ __('Create Task') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>