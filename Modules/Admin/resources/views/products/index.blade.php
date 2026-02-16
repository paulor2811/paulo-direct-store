<x-admin-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Gerenciar Anúncios') }}
        </h2>
    </x-slot>

    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 text-gray-900">
            
            <!-- Filters -->
            <form method="GET" action="{{ route('admin.products.index') }}" class="mb-6 bg-gray-50 p-4 rounded-lg border border-gray-200">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <!-- Search -->
                    <div class="col-span-1 md:col-span-4 lg:col-span-1">
                        <label for="search" class="block text-sm font-medium text-gray-700">Buscar</label>
                        <input type="text" name="search" id="search" value="{{ request('search') }}" placeholder="Nome, marca, modelo..." class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    </div>

                    <!-- Category -->
                    <div>
                        <label for="categoria_id" class="block text-sm font-medium text-gray-700">Categoria</label>
                        <select name="categoria_id" id="categoria_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            <option value="">Todas</option>
                            @foreach($categorias as $categoria)
                                <option value="{{ $categoria->id }}" {{ request('categoria_id') == $categoria->id ? 'selected' : '' }}>
                                    {{ $categoria->nome }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Price Range -->
                    <div class="flex space-x-2">
                        <div class="w-1/2">
                            <label for="price_min" class="block text-sm font-medium text-gray-700">Preço Mín</label>
                            <input type="number" step="0.01" name="price_min" id="price_min" value="{{ request('price_min') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        </div>
                        <div class="w-1/2">
                            <label for="price_max" class="block text-sm font-medium text-gray-700">Preço Máx</label>
                            <input type="number" step="0.01" name="price_max" id="price_max" value="{{ request('price_max') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        </div>
                    </div>

                    <!-- Date Range -->
                    <div class="flex space-x-2">
                        <div class="w-1/2">
                            <label for="date_start" class="block text-sm font-medium text-gray-700">De</label>
                            <input type="date" name="date_start" id="date_start" value="{{ request('date_start') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        </div>
                        <div class="w-1/2">
                            <label for="date_end" class="block text-sm font-medium text-gray-700">Até</label>
                            <input type="date" name="date_end" id="date_end" value="{{ request('date_end') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        </div>
                    </div>
                </div>

                <div class="mt-4 flex justify-end">
                    <a href="{{ route('admin.products.index') }}" class="mr-3 inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Limpar
                    </a>
                    <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Filtrar
                    </button>
                </div>
            </form>

            <!-- Table -->
            <div class="mt-6">
                <div class="shadow overflow-hidden border border-gray-200 rounded-lg">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Produto
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Categoria
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:text-gray-700"
                                            onclick="window.location.href='{{ route('admin.products.index', array_merge(request()->query(), ['sort' => 'preco', 'direction' => request('direction') == 'asc' ? 'desc' : 'asc'])) }}'">
                                            Preço
                                            @if(request('sort') == 'preco')
                                                @if(request('direction') == 'asc')
                                                    <span class="ml-1">▲</span>
                                                @else
                                                    <span class="ml-1">▼</span>
                                                @endif
                                            @endif
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:text-gray-700"
                                            onclick="window.location.href='{{ route('admin.products.index', array_merge(request()->query(), ['sort' => 'created_at', 'direction' => request('direction') == 'asc' ? 'desc' : 'asc'])) }}'">
                                            Criado em
                                            @if(request('sort') == 'created_at' || !request()->has('sort'))
                                                @if(request('direction') == 'asc')
                                                    <span class="ml-1">▲</span>
                                                @else
                                                    <span class="ml-1">▼</span>
                                                @endif
                                            @endif
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Status
                                        </th>
                                        <th scope="col" class="relative px-6 py-3">
                                            <span class="sr-only">Ações</span>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($produtos as $produto)
                                        <tr>
                                            <td class="px-6 py-4">
                                                <div class="flex items-center">
                                                    <div class="flex-shrink-0 h-16 w-16">
                                                        @if($produto->images()->count() > 0)
                                                            <img class="h-16 w-16 rounded object-cover" src="{{ $produto->main_image_url ?? asset('images/placeholder.png') }}" alt="">
                                                        @else
                                                            <div class="h-16 w-16 rounded bg-gray-200 flex items-center justify-center">
                                                                <span class="text-xs text-gray-500">N/A</span>
                                                            </div>
                                                        @endif
                                                    </div>
                                                    <div class="ml-4">
                                                        <div class="text-sm font-medium text-gray-900">
                                                            {{ $produto->nome }}
                                                        </div>
                                                        <div class="text-sm text-gray-500">
                                                            {{ $produto->marca }} - {{ $produto->modelo }}
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4">
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                    {{ $produto->categoria->nome ?? 'Sem Categoria' }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 text-sm text-gray-500">
                                                R$ {{ number_format($produto->preco, 2, ',', '.') }}
                                            </td>
                                            <td class="px-6 py-4 text-sm text-gray-500">
                                                {{ \Carbon\Carbon::parse($produto->created_at)->format('d/m/Y') }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                @if($produto->is_active)
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                        Ativo
                                                    </span>
                                                @else
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                                        Inativo
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 text-right text-sm font-medium">
                                                <a href="{{ route('admin.products.edit', $produto->id) }}" class="text-indigo-600 hover:text-indigo-900 mr-3">Editar</a>
                                                
                                                <form action="{{ route('admin.products.toggle-status', $produto->id) }}" method="POST" class="inline mr-3">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" class="{{ $produto->is_active ? 'text-orange-600 hover:text-orange-900' : 'text-green-600 hover:text-green-900' }}">
                                                        {{ $produto->is_active ? 'Ocultar' : 'Mostrar' }}
                                                    </button>
                                                </form>

                                                <x-modal-delete-confirmation action="{{ route('admin.products.destroy', $produto->id) }}" title="Excluir produto {{ $produto->nome }}?">
                                                    <x-slot name="trigger">
                                                        <button class="text-red-600 hover:text-red-900">Excluir</button>
                                                    </x-slot>
                                                </x-modal-delete-confirmation>
                                                <a href="{{ route('products.show', $produto->id) }}" class="text-gray-600 hover:text-gray-900 ml-3" target="_blank">Ver</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
            </div>

            <!-- Pagination -->
            <div class="mt-4">
                {{ $produtos->appends(request()->query())->links() }}
            </div>

        </div>
    </div>
</x-admin-layout>
