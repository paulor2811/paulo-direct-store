<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.site_name', 'Laravel') }} - Admin</title>
    
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-gray-100" x-data="{ sidebarOpen: false }">
    @include('layouts.navigation')
    
    <div class="flex overflow-hidden relative" style="height: calc(100vh - 65px);">
        <!-- Mobile sidebar backdrop -->
        <div x-show="sidebarOpen" x-transition.opacity class="fixed inset-0 z-20 bg-black bg-opacity-50 lg:hidden" @click="sidebarOpen = false"></div>

        <!-- Sidebar -->
        <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'" class="absolute z-30 inset-y-0 left-0 w-64 lg:static lg:translate-x-0 transform transition-transform duration-300 ease-in-out flex-shrink-0 bg-white shadow-md overflow-y-auto">
            <div class="p-6">
                <h2 class="text-2xl font-bold text-gray-800">Painel Admin</h2>
            </div>
            <nav class="mt-6">
                <a href="{{ route('admin.dashboard') }}" class="block px-4 py-2 text-gray-600 hover:bg-gray-200 hover:text-gray-800">
                    Dashboard
                </a>
                <div x-data="{ open: true }">
                    <button @click="open = !open" class="flex items-center justify-between w-full px-4 py-2 text-gray-600 hover:bg-gray-200 hover:text-gray-800 focus:outline-none">
                        <span>Anúncios</span>
                        <svg class="w-4 h-4 transition-transform transform" :class="{ 'rotate-180': open }" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    <div x-show="open" class="bg-gray-50">
                        <a href="{{ route('admin.products.create') }}" class="block px-8 py-2 text-sm text-gray-600 hover:text-gray-800 hover:bg-gray-200">
                            Novo Anúncio
                        </a>
                        <a href="{{ route('admin.products.index') }}" class="block px-8 py-2 text-sm text-gray-600 hover:text-gray-800 hover:bg-gray-200">
                            Ver Todos
                        </a>
                    </div>
                </div>
                <a href="{{ route('admin.users.index') }}" class="block px-4 py-2 text-gray-600 hover:bg-gray-200 hover:text-gray-800">
                    Usuários
                </a>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 overflow-y-auto bg-gray-100 min-w-0">
            <div class="py-6 px-4 sm:px-6 lg:px-8">
                <div class="max-w-7xl mx-auto">
                    <div class="flex items-center mb-6">
                        <button @click="sidebarOpen = true" class="lg:hidden text-gray-500 hover:text-gray-700 focus:outline-none mr-3">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                            </svg>
                        </button>
                        @isset($header)
                            <div class="flex-1 min-w-0 truncate">
                                {{ $header }}
                            </div>
                        @endisset
                    </div>
                    
                    {{ $slot }}
                </div>
            </div>
        </main>
    </div>
</body>
</html>
