<x-admin-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ isset($produto) ? __('Editar Anúncio') : __('Novo Anúncio') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    
                    @if(session('success'))
                        <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                            <span class="block sm:inline">{{ session('success') }}</span>
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ isset($produto) ? route('admin.products.update', $produto->id) : route('admin.products.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                        @csrf
                        @if(isset($produto))
                            @method('PUT')
                        @endif

                        <!-- Nome -->
                        <div>
                            <x-input-label for="nome" :value="__('Nome do Produto')" />
                            <x-text-input id="nome" name="nome" type="text" class="mt-1 block w-full" :value="old('nome', $produto->nome ?? '')" required autofocus />
                        </div>

                        <!-- Descrição -->
                        <div>
                            <x-input-label for="descricao" :value="__('Descrição')" />
                            <textarea id="descricao" name="descricao" rows="4" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>{{ old('descricao', $produto->descricao ?? '') }}</textarea>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <!-- Marca -->
                            <div>
                                <x-input-label for="marca" :value="__('Marca')" />
                                <x-text-input id="marca" name="marca" type="text" class="mt-1 block w-full" :value="old('marca', $produto->marca ?? '')" required />
                            </div>

                            <!-- Modelo -->
                            <div>
                                <x-input-label for="modelo" :value="__('Modelo')" />
                                <x-text-input id="modelo" name="modelo" type="text" class="mt-1 block w-full" :value="old('modelo', $produto->modelo ?? '')" required />
                            </div>

                            <!-- Cor -->
                            <div>
                                <x-input-label for="cor" :value="__('Cor')" />
                                <x-text-input id="cor" name="cor" type="text" class="mt-1 block w-full" :value="old('cor', $produto->cor ?? '')" required />
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <!-- Preço -->
                            <div>
                                <x-input-label for="preco" :value="__('Preço')" />
                                <x-text-input id="preco" name="preco" type="number" step="0.01" class="mt-1 block w-full" :value="old('preco', $produto->preco ?? '')" required />
                            </div>

                            <!-- Condição -->
                            <div>
                                <x-input-label for="condicao" :value="__('Condição')" />
                                <select id="condicao" name="condicao" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                                    <option value="novo" {{ old('condicao', $produto->condicao ?? 'novo') == 'novo' ? 'selected' : '' }}>Novo</option>
                                    <option value="usado" {{ old('condicao', $produto->condicao ?? '') == 'usado' ? 'selected' : '' }}>Usado</option>
                                    <option value="sucata" {{ old('condicao', $produto->condicao ?? '') == 'sucata' ? 'selected' : '' }}>Sucata</option>
                                </select>
                            </div>

                            <!-- Categoria -->
                            <div>
                                <x-input-label for="categoria_produto_id" :value="__('Categoria')" />
                                <select id="categoria_produto_id" name="categoria_produto_id" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                                    <option value="">Selecione uma categoria</option>
                                    @foreach($categorias as $categoria)
                                        <option value="{{ $categoria->id }}" {{ old('categoria_produto_id', $produto->categoria_produto_id ?? '') == $categoria->id ? 'selected' : '' }}>
                                            {{ $categoria->nome }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <!-- Fotos -->
                        <div>
                            <x-input-label for="fotos" :value="__('Fotos do Produto (Máx 5)')" />
                            
                            <!-- Existing Photos -->
                            @if(isset($produto))
                                @php
                                    $existingImages = $produto->images();
                                @endphp
                                @if($existingImages->count() > 0)
                                    <div class="mb-4 grid grid-cols-2 md:grid-cols-5 gap-4">
                                        @foreach($existingImages as $foto)
                                            <div class="relative group" id="foto-{{ $foto->id }}">
                                                <img src="{{ $foto->url }}" class="h-24 w-full object-cover rounded-lg shadow-md">
                                                <button type="button" onclick="markForRemoval('{{ $foto->id }}')" class="absolute top-1 right-1 bg-red-500 text-white rounded-full p-1 opacity-0 group-hover:opacity-100 transition-opacity">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                                </button>
                                            </div>
                                        @endforeach
                                    </div>
                                    <div id="remove-photos-container"></div>
                                @endif
                            @endif

                            <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md hover:border-indigo-500 transition-colors cursor-pointer" onclick="document.getElementById('fotos').click()">
                                <div class="space-y-1 text-center">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                                        <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                    <div class="flex text-sm text-gray-600">
                                        <label for="fotos" class="relative cursor-pointer bg-white rounded-md font-medium text-indigo-600 hover:text-indigo-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-indigo-500">
                                            <span>Clique para selecionar</span>
                                            <input id="fotos" name="fotos[]" type="file" multiple class="sr-only" accept="image/*" onchange="handleFileSelect(event)">
                                        </label>
                                        <p class="pl-1">ou arraste e solte</p>
                                    </div>
                                    <p class="text-xs text-gray-500">PNG, JPG, GIF até 2MB (Máx 5 fotos)</p>
                                </div>
                            </div>
                            
                            <!-- Preview Area -->
                            <div id="preview-area" class="grid grid-cols-2 md:grid-cols-5 gap-4 mt-4"></div>

                            <script>
                                const fileInput = document.getElementById('fotos');
                                const previewArea = document.getElementById('preview-area');
                                let dataTransfer = new DataTransfer();

                                function handleFileSelect(event) {
                                    const files = event.target.files;
                                    
                                    for (let i = 0; i < files.length; i++) {
                                        if (dataTransfer.items.length >= 5) {
                                            alert('Máximo de 5 fotos permitido.');
                                            break;
                                        }
                                        const file = files[i];
                                        dataTransfer.items.add(file);
                                        
                                        // Create Preview
                                        const reader = new FileReader();
                                        reader.onload = function(e) {
                                            const div = document.createElement('div');
                                            div.className = 'relative group';
                                            div.innerHTML = `
                                                <img src="${e.target.result}" class="h-24 w-full object-cover rounded-lg shadow-md">
                                                <button type="button" onclick="removeFile('${file.name}', this)" class="absolute top-1 right-1 bg-red-500 text-white rounded-full p-1 opacity-0 group-hover:opacity-100 transition-opacity">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                                </button>
                                            `;
                                            previewArea.appendChild(div);
                                        };
                                        reader.readAsDataURL(file);
                                    }
                                    
                                    fileInput.files = dataTransfer.files;
                                }

                                function removeFile(fileName, button) {
                                    const newDataTransfer = new DataTransfer();
                                    for (let i = 0; i < dataTransfer.files.length; i++) {
                                        const file = dataTransfer.files[i];
                                        if (file.name !== fileName) {
                                            newDataTransfer.items.add(file);
                                        }
                                    }
                                    dataTransfer = newDataTransfer;
                                    fileInput.files = dataTransfer.files;
                                    button.parentElement.remove();
                                }

                                function markForRemoval(id) {
                                    // Removed confirm dialog to ensure smooth interaction
                                    const container = document.getElementById('remove-photos-container');
                                    const input = document.createElement('input');
                                    input.type = 'hidden';
                                    input.name = 'remove_fotos[]';
                                    input.value = id;
                                    container.appendChild(input);
                                    
                                    const element = document.getElementById('foto-' + id);
                                    if (element) {
                                        element.remove();
                                        console.log('Imagem marcada para remoção: ' + id);
                                    } else {
                                        console.error('Elemento não encontrado: foto-' + id);
                                    }
                                }
                            </script>
                        </div>
                        <div class="flex items-center gap-4">
                            <x-primary-button>{{ isset($produto) ? __('Salvar Alterações') : __('Criar Anúncio') }}</x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- Delete button completely separated from form to avoid any submission conflicts --}}
    @if(isset($produto))
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 mt-4">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 border-t-2 border-red-100">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-lg font-medium text-gray-900">Zona de Perigo</h3>
                            <p class="mt-1 text-sm text-gray-600">Esta ação irá remover permanentemente o produto.</p>
                        </div>
                        <x-modal-delete-confirmation action="{{ route('admin.products.destroy', $produto->id) }}" title="Excluir produto {{ $produto->nome }}?">
                            <x-slot name="trigger">
                                <button type="button" class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-500 active:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                    {{ __('Excluir Anúncio') }}
                                </button>
                            </x-slot>
                        </x-modal-delete-confirmation>
                    </div>
                </div>
            </div>
        </div>
    @endif
</x-admin-layout>
