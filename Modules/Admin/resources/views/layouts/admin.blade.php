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
<body class="font-sans antialiased bg-gray-100">
    <div class="flex min-h-screen">
        <!-- Sidebar -->
        <aside class="w-64 flex-shrink-0 bg-white shadow-md">
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
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 overflow-y-auto">
            <div class="py-6 px-4 sm:px-6 lg:px-8">
                <div class="max-w-7xl mx-auto">
                    @isset($header)
                        <div class="mb-6">
                            {{ $header }}
                        </div>
                    @endisset
                    
                    {{ $slot }}
                </div>
            </div>
        </main>
    </div>
</body>
</html>
