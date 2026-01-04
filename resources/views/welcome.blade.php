<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Task Tracker</title>
        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="antialiased bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-gray-100 min-h-screen flex flex-col justify-center items-center selection:bg-indigo-500 selection:text-white">
        
        <div class="max-w-7xl mx-auto p-6 lg:p-8 text-center">
            
            <h1 class="text-5xl font-bold tracking-tight text-indigo-600 dark:text-indigo-400 mb-6 animate-pulse">
                Task Tracker
            </h1>
            
            <p class="mt-4 text-xl text-gray-600 dark:text-gray-400 max-w-2xl mx-auto mb-12">
                Stay organized, collaborate seamlessly, and track your progress with ease. 
                The ultimate minimal tool for managing your personal and team projects.
            </p>

            <div class="flex justify-center gap-6">
                @if (Route::has('login'))
                    <nav class="flex gap-4">
                        @auth
                            <a
                                href="{{ url('/dashboard') }}"
                                class="px-6 py-3 bg-indigo-600 text-white font-semibold rounded-lg shadow-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-900 transition-all duration-200 transform hover:scale-105"
                            >
                                Go to Dashboard
                            </a>
                        @else
                            <a
                                href="{{ route('login') }}"
                                class="px-6 py-3 bg-white dark:bg-gray-800 text-gray-900 dark:text-white font-semibold rounded-lg shadow-md border border-gray-200 dark:border-gray-700 hover:border-indigo-500 dark:hover:border-indigo-500 hover:text-indigo-600 dark:hover:text-indigo-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-900 transition-all duration-200"
                            >
                                Log in
                            </a>

                            @if (Route::has('register'))
                                <a
                                    href="{{ route('register') }}"
                                    class="px-6 py-3 bg-indigo-600 text-white font-semibold rounded-lg shadow-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-900 transition-all duration-200 transform hover:scale-105"
                                >
                                    Register
                                </a>
                            @endif
                        @endauth
                    </nav>
                @endif
            </div>

            <div class="mt-16 text-sm text-gray-500 dark:text-gray-500">
                &copy; {{ date('Y') }} Task Tracker. All rights reserved.
            </div>
        </div>
    </body>
</html>
