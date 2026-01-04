<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <!-- Stats Grid -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                <!-- Total Tasks -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="text-gray-500 dark:text-gray-400 text-sm font-medium uppercase tracking-wide">Total Tasks</div>
                    <div class="mt-2 text-3xl font-bold text-gray-900 dark:text-white">{{ $totalTasks }}</div>
                </div>

                <!-- Pending -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-yellow-500">
                    <div class="text-gray-500 dark:text-gray-400 text-sm font-medium uppercase tracking-wide">Pending</div>
                    <div class="mt-2 text-3xl font-bold text-gray-900 dark:text-white">{{ $pendingTasks }}</div>
                </div>

                <!-- In Progress -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-blue-500">
                    <div class="text-gray-500 dark:text-gray-400 text-sm font-medium uppercase tracking-wide">In Progress</div>
                    <div class="mt-2 text-3xl font-bold text-gray-900 dark:text-white">{{ $inProgressTasks }}</div>
                </div>

                <!-- Completed -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-green-500">
                    <div class="text-gray-500 dark:text-gray-400 text-sm font-medium uppercase tracking-wide">Completed</div>
                    <div class="mt-2 text-3xl font-bold text-gray-900 dark:text-white">{{ $completedTasks }}</div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Project Stats -->
                 <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                     <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Active Projects</h3>
                     <div class="flex items-center justify-between">
                         <span class="text-gray-600 dark:text-gray-400">Total Projects</span>
                         <span class="text-2xl font-bold text-gray-900 dark:text-white">{{ $projectCount }}</span>
                     </div>
                     <div class="mt-4">
                         <a href="{{ route('projects.index') }}" class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-800 dark:hover:text-indigo-200 text-sm font-medium">View All Projects &rarr;</a>
                     </div>
                 </div>

                 <!-- Quick Actions -->
                 <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6 flex flex-col justify-center space-y-4">
                    <a href="{{ route('tasks.create') }}" class="w-full inline-flex justify-center items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                        Create New Task
                    </a>
                    <a href="{{ route('projects.create') }}" class="w-full inline-flex justify-center items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                        Start New Project
                    </a>
                 </div>
            </div>

        </div>
    </div>
</x-app-layout>
