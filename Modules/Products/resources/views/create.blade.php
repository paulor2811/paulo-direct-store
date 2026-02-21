<x-products::layouts.master>
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-900">Criar Novo Produto</h1>
        <p class="mt-2 text-sm text-gray-600">Adicione um novo produto ao marketplace</p>
        
        @auth
            <div class="mt-4 inline-flex items-center px-4 py-2 bg-indigo-50 dark:bg-indigo-900/20 rounded-2xl border border-indigo-100 dark:border-indigo-800">
                <span class="flex h-2 w-2 rounded-full bg-indigo-500 mr-2"></span>
                <span class="text-xs font-bold text-indigo-700 dark:text-indigo-300 uppercase tracking-widest">
                    Postando como: 
                    @if(session('active_store_id'))
                        @php $activeStore = \Modules\Stores\Models\Store::find(session('active_store_id')); @endphp
                        {{ $activeStore ? $activeStore->nome : 'Pessoa Física' }}
                    @else
                        Pessoa Física
                    @endif
                </span>
                <a href="{{ route('stores.index') }}" class="ml-4 text-[10px] font-black text-primary-600 hover:underline uppercase tracking-tighter">Trocar</a>
            </div>
        @endauth
    </div>

    <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data" class="bg-white shadow-sm rounded-lg p-6 space-y-6">
        @csrf

        {{-- Product Name --}}
        <div>
            <label for="nome" class="block text-sm font-medium text-gray-700">Nome do Produto *</label>
            <input type="text" name="nome" id="nome" required value="{{ old('nome') }}"
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('nome') border-red-500 @enderror">
            @error('nome')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        {{-- Description --}}
        <div>
            <label for="descricao" class="block text-sm font-medium text-gray-700">Descrição *</label>
            <textarea name="descricao" id="descricao" rows="4" required
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('descricao') border-red-500 @enderror">{{ old('descricao') }}</textarea>
            @error('descricao')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            {{-- Brand --}}
            <div>
                <label for="marca" class="block text-sm font-medium text-gray-700">Marca</label>
                <input type="text" name="marca" id="marca" value="{{ old('marca') }}"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
            </div>

            {{-- Model --}}
            <div>
                <label for="modelo" class="block text-sm font-medium text-gray-700">Modelo</label>
                <input type="text" name="modelo" id="modelo" value="{{ old('modelo') }}"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
            </div>

            {{-- Color --}}
            <div>
                <label for="cor" class="block text-sm font-medium text-gray-700">Cor</label>
                <input type="text" name="cor" id="cor" value="{{ old('cor') }}"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            {{-- Price --}}
            <div>
                <label for="preco" class="block text-sm font-medium text-gray-700">Preço (R$) *</label>
                <input type="number" step="0.01" min="0" name="preco" id="preco" required value="{{ old('preco') }}"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('preco') border-red-500 @enderror">
                @error('preco')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Condition --}}
            <div>
                <label for="condicao" class="block text-sm font-medium text-gray-700">Condição *</label>
                <select name="condicao" id="condicao" required
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('condicao') border-red-500 @enderror">
                    <option value="novo" {{ old('condicao') == 'novo' ? 'selected' : '' }}>Novo</option>
                    <option value="seminovo" {{ old('condicao') == 'seminovo' ? 'selected' : '' }}>Seminovo</option>
                    <option value="usado" {{ old('condicao') == 'usado' ? 'selected' : '' }}>Usado</option>
                    <option value="sucata" {{ old('condicao') == 'sucata' ? 'selected' : '' }}>Sucata</option>
                </select>
                @error('condicao')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Category --}}
            <div>
                <label for="categoria_produto_id" class="block text-sm font-medium text-gray-700">Categoria *</label>
                <select name="categoria_produto_id" id="categoria_produto_id" required
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('categoria_produto_id') border-red-500 @enderror">
                    <option value="">Selecione uma categoria</option>
                    @foreach(\Modules\Products\Models\CategoriaProduto::all() as $category)
                        <option value="{{ $category->id }}" {{ old('categoria_produto_id') == $category->id ? 'selected' : '' }}>
                            {{ $category->nome }}
                        </option>
                    @endforeach
                </select>
                @error('categoria_produto_id')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>

        {{-- Images Upload --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Imagens do Produto</label>
            <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md hover:border-indigo-400 transition">
                <div class="space-y-1 text-center">
                    <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                        <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                    <div class="flex text-sm text-gray-600">
                        <label for="images" class="relative cursor-pointer bg-white rounded-md font-medium text-indigo-600 hover:text-indigo-500 focus-within:outline-none">
                            <span>Upload de imagens</span>
                            <input id="images" name="images[]" type="file" class="sr-only" multiple accept="image/*" onchange="previewImages(event)">
                        </label>
                        <p class="pl-1">ou arraste e solte</p>
                    </div>
                    <p class="text-xs text-gray-500">PNG, JPG, GIF, WEBP até 10MB cada</p>
                </div>
            </div>
            @error('images.*')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror

            {{-- Image Preview --}}
            <div id="image-preview" class="mt-4 grid grid-cols-2 md:grid-cols-4 gap-4"></div>
        </div>

        {{-- Submit Buttons --}}
        <div class="flex items-center justify-end space-x-4 pt-4 border-t">
            <a href="{{ route('products.index') }}" class="px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                Cancelar
            </a>
            <button type="submit" class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                Criar Produto
            </button>
        </div>
    </form>
</div>

<script>
function previewImages(event) {
    const preview = document.getElementById('image-preview');
    preview.innerHTML = '';
    const files = event.target.files;

    Array.from(files).forEach((file, index) => {
        const reader = new FileReader();
        reader.onload = function(e) {
            const div = document.createElement('div');
            div.className = 'relative group';
            div.innerHTML = `
                <img src="${e.target.result}" class="h-32 w-full object-cover rounded-lg shadow-sm" alt="Preview ${index + 1}">
                <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-40 transition rounded-lg flex items-center justify-center">
                    <span class="text-white text-sm font-medium opacity-0 group-hover:opacity-100">Imagem ${index + 1}</span>
                </div>
            `;
            preview.appendChild(div);
        }
        reader.readAsDataURL(file);
    });
}
</script>
</x-products::layouts.master>
