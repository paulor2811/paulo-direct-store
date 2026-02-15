<x-app-layout>
    <div class="flex h-screen bg-gray-100">
        <!-- Sidebar -->
        <aside class="w-64 bg-white shadow-md">
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
            <div class="py-6">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 md:px-8">
                    {{ $slot }}
                </div>
            </div>
        </main>
    </div>
</x-app-layout>
