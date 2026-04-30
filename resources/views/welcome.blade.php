<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ config('app.name') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-gray-50 min-h-screen flex flex-col">
        <nav class="bg-white border-b border-gray-200 py-4">
            <div class="max-w-7xl mx-auto px-4 flex justify-between items-center">
                <div class="flex items-center gap-2">
                    <svg class="w-8 h-8 text-indigo-600" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M9 5H7C5.89543 5 5 5.89543 5 7V19C5 20.1046 5.89543 21 7 21H17C18.1046 21 19 20.1046 19 19V7C19 5.89543 18.1046 5 17 5H15" stroke="currentColor" stroke-width="2"/>
                        <path d="M9 14L11 16L15 12" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    <span class="text-xl font-bold text-gray-800">{{ config('app.name') }}</span>
                </div>
                <div class="flex gap-4">
                    @if (Route::has('login'))
                        @auth
                            <a href="{{ url('/dashboard') }}" class="text-indigo-600 hover:text-indigo-800 font-medium">Dashboard</a>
                        @else
                            <a href="{{ route('login') }}" class="text-gray-600 hover:text-gray-800 font-medium py-2">Log in</a>
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 font-medium">Register</a>
                            @endif
                        @endauth
                    @endif
                </div>
            </div>
        </nav>

        <main class="flex-1 flex items-center justify-center py-12">
            <div class="text-center max-w-lg px-4">
                <h1 class="text-4xl font-bold text-gray-900 mb-4">Welcome to {{ config('app.name') }}</h1>
                <p class="text-gray-600 text-lg mb-8">Organize your tasks and boost your productivity.</p>

                @if (Route::has('login'))
                    @auth
                        <a href="{{ url('/dashboard') }}" class="inline-block bg-indigo-600 text-white px-6 py-3 rounded-lg hover:bg-indigo-700 font-medium">
                            Go to Dashboard
                        </a>
                    @else
                        <div class="flex gap-4 justify-center">
                            <a href="{{ route('login') }}" class="border border-gray-300 text-gray-700 px-6 py-3 rounded-lg hover:bg-gray-100 font-medium">
                                Log in
                            </a>
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="bg-indigo-600 text-white px-6 py-3 rounded-lg hover:bg-indigo-700 font-medium">
                                    Register
                                </a>
                            @endif
                        </div>
                    @endauth
                @endif
            </div>
        </main>
    </body>
</html>