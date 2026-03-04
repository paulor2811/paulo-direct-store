<x-products::layouts.master>
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-900">Editar Produto</h1>
        <p class="mt-2 text-sm text-gray-600">Atualize as informações do produto</p>
    </div>

    <form action="{{ route('products.update', $product->id) }}" method="POST" enctype="multipart/form-data" class="bg-white shadow-sm rounded-lg p-6 space-y-6" id="productForm">
        @csrf
        @method('PUT')

        {{-- Product Name --}}
        <div>
            <label for="nome" class="block text-sm font-medium text-gray-700">Nome do Produto *</label>
            <input type="text" name="nome" id="nome" required value="{{ old('nome', $product->nome) }}"
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('nome') border-red-500 @enderror">
            @error('nome')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        {{-- Description --}}
        <div>
            <label for="descricao" class="block text-sm font-medium text-gray-700">Descrição *</label>
            <textarea name="descricao" id="descricao" rows="4" required
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('descricao') border-red-500 @enderror">{{ old('descricao', $product->descricao) }}</textarea>
            @error('descricao')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            {{-- Brand --}}
            <div>
                <label for="marca" class="block text-sm font-medium text-gray-700">Marca</label>
                <input type="text" name="marca" id="marca" value="{{ old('marca', $product->marca) }}"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
            </div>

            {{-- Model --}}
            <div>
                <label for="modelo" class="block text-sm font-medium text-gray-700">Modelo</label>
                <input type="text" name="modelo" id="modelo" value="{{ old('modelo', $product->modelo) }}"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
            </div>

            {{-- Color --}}
            <div>
                <label for="cor" class="block text-sm font-medium text-gray-700">Cor</label>
                <input type="text" name="cor" id="cor" value="{{ old('cor', $product->cor) }}"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            {{-- Price --}}
            <div>
                <label for="preco" class="block text-sm font-medium text-gray-700">Preço (R$) *</label>
                <input type="number" step="0.01" min="0" name="preco" id="preco" required value="{{ old('preco', $product->preco) }}"
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
                    <option value="novo" {{ old('condicao', $product->condicao) == 'novo' ? 'selected' : '' }}>Novo</option>
                    <option value="seminovo" {{ old('condicao', $product->condicao) == 'seminovo' ? 'selected' : '' }}>Seminovo</option>
                    <option value="usado" {{ old('condicao', $product->condicao) == 'usado' ? 'selected' : '' }}>Usado</option>
                    <option value="sucata" {{ old('condicao', $product->condicao) == 'sucata' ? 'selected' : '' }}>Sucata</option>
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
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ old('categoria_produto_id', $product->categoria_produto_id) == $category->id ? 'selected' : '' }}>
                            {{ $category->nome }}
                        </option>
                    @endforeach
                </select>
                @error('categoria_produto_id')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>

        {{-- Dynamic Shipping Configuration --}}
        <div class="bg-gray-50 border border-gray-200 rounded-lg p-5">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h3 class="text-sm font-bold text-gray-900">Oferecer Entrega Própria</h3>
                    <p class="text-xs text-gray-500 mt-0.5">Permitir que compradores simulem o frete até eles baseado no custo por Km de distância.</p>
                </div>
                <label class="relative inline-flex items-center cursor-pointer">
                    <input type="checkbox" id="toggle_shipping" class="sr-only peer" onchange="toggleShippingFields()" {{ old('shipping_price_per_km', $product->shipping_price_per_km) ? 'checked' : '' }}>
                    <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-indigo-300 dark:peer-focus:ring-indigo-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-indigo-600"></div>
                </label>
            </div>

            <div id="shipping_config_panel" class="{{ old('shipping_price_per_km', $product->shipping_price_per_km) ? '' : 'hidden' }} grid grid-cols-1 sm:grid-cols-2 gap-4 pt-3 border-t border-gray-200">
                <div>
                    <label for="shipping_price_per_km" class="block text-xs font-bold text-gray-700 uppercase tracking-widest">Preço por Km (R$) *</label>
                    <input type="number" step="0.01" min="0" name="shipping_price_per_km" id="shipping_price_per_km" value="{{ old('shipping_price_per_km', $product->shipping_price_per_km) }}" placeholder="Ex: 2.50"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-widest">Ponto de Partida *</label>
                    <button type="button" onclick="captureLocation()" id="btn_capture_location" class="mt-1 w-full inline-flex justify-center items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 {{ old('shipping_origin_lat', $product->shipping_origin_lat) ? 'bg-green-50 text-green-700 border-green-200' : 'bg-white hover:bg-gray-50' }} focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors">
                        <svg class="w-4 h-4 mr-2 {{ old('shipping_origin_lat', $product->shipping_origin_lat) ? 'text-green-500' : 'text-indigo-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                        <span id="txt_capture_location">{{ old('shipping_origin_lat', $product->shipping_origin_lat) ? 'Atualizar Localização' : 'Capturar Minha Localização' }}</span>
                    </button>
                    <input type="hidden" name="shipping_origin_lat" id="shipping_origin_lat" value="{{ old('shipping_origin_lat', $product->shipping_origin_lat) }}">
                    <input type="hidden" name="shipping_origin_lon" id="shipping_origin_lon" value="{{ old('shipping_origin_lon', $product->shipping_origin_lon) }}">
                    <p id="location_status" class="mt-1 text-xs text-green-600 {{ old('shipping_origin_lat', $product->shipping_origin_lat) ? '' : 'hidden' }} font-semibold">✓ Localização capturada com sucesso!</p>
                </div>
            </div>
        </div>

        {{-- Current Images --}}
        @php
            $currentImages = $product->images();
        @endphp
        @if($currentImages->count() > 0)
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Imagens Atuais</label>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4" id="current-images">
                @foreach($currentImages as $image)
                <div class="relative group" id="image-{{ $image->id }}">
                    <img src="{{ $image->url }}" class="h-32 w-full object-cover rounded-lg shadow-sm" alt="{{ $product->nome }}">
                    <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-60 transition rounded-lg flex items-center justify-center">
                        <button type="button" onclick="markForDeletion('{{ $image->id }}')" 
                            class="opacity-0 group-hover:opacity-100 bg-red-600 text-white px-3 py-1 rounded text-sm font-medium hover:bg-red-700 transition">
                            Remover
                        </button>
                    </div>
                    <div class="hidden absolute inset-0 bg-red-900 bg-opacity-80 rounded-lg items-center justify-center" id="deleted-overlay-{{ $image->id }}">
                        <div class="text-center text-white">
                            <svg class="w-8 h-8 mx-auto mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                            <p class="text-xs">Será removida</p>
                            <button type="button" onclick="unmarkForDeletion('{{ $image->id }}')" class="text-xs underline mt-1">Desfazer</button>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        {{-- New Images Upload --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Adicionar Novas Imagens (Até 5 fotos totais)</label>
            <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md hover:border-indigo-400 transition" id="dropzone">
                <div class="space-y-1 text-center">
                    <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                        <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                    <div class="flex text-sm text-gray-600">
                        <label for="images" class="relative cursor-pointer bg-white rounded-md font-medium text-indigo-600 hover:text-indigo-500">
                            <span>Upload de imagens</span>
                            <input id="images" name="images[]" type="file" class="sr-only" multiple accept="image/*" onchange="addNewImages(event)">
                        </label>
                        <p class="pl-1">ou arraste e solte</p>
                    </div>
                    <p class="text-xs text-gray-500">PNG, JPG, GIF, WEBP até 10MB cada</p>
                </div>
            </div>
            @error('images.*')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror

            {{-- New Images Preview --}}
            <div id="new-image-preview" class="mt-4 grid grid-cols-2 md:grid-cols-5 gap-4"></div>
        </div>

        {{-- Submit Buttons --}}
        <div class="flex items-center justify-end space-x-4 pt-4 border-t">
            <a href="{{ route('products.show', $product->id) }}" class="px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                Cancelar
            </a>
            <button type="submit" class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                Salvar Alterações
            </button>
        </div>
    </form>
</div>

<script>
// Track images to delete
const imagesToDelete = new Set();
let newFiles = [];
const maxImages = 5;
const currentImagesCount = {{ $currentImages->count() }};

function markForDeletion(imageId) {
    imagesToDelete.add(imageId);
    document.getElementById('image-' + imageId).querySelector('img').style.opacity = '0.3';
    document.getElementById('deleted-overlay-' + imageId).classList.remove('hidden');
    document.getElementById('deleted-overlay-' + imageId).classList.add('flex');
    
    // Add hidden input
    const input = document.createElement('input');
    input.type = 'hidden';
    input.name = 'delete_images[]';
    input.value = imageId;
    input.id = 'delete-input-' + imageId;
    document.getElementById('productForm').appendChild(input);
}

function unmarkForDeletion(imageId) {
    imagesToDelete.delete(imageId);
    document.getElementById('image-' + imageId).querySelector('img').style.opacity = '1';
    document.getElementById('deleted-overlay-' + imageId).classList.add('hidden');
    document.getElementById('deleted-overlay-' + imageId).classList.remove('flex');
    
    // Remove hidden input
    const input = document.getElementById('delete-input-' + imageId);
    if (input) input.remove();
}

function addNewImages(event) {
    const files = Array.from(event.target.files);
    const totalCurrent = currentImagesCount - imagesToDelete.size + newFiles.length;
    
    if (totalCurrent + files.length > maxImages) {
        alert(`O produto pode ter no máximo ${maxImages} imagens totais.`);
        event.target.value = '';
        return;
    }

    files.forEach(file => {
        if (!newFiles.some(f => f.name === file.name && f.size === file.size)) {
            newFiles.push(file);
        }
    });

    renderNewPreviews();
    updateNewInputFiles();
}

function removeNewImage(index) {
    newFiles.splice(index, 1);
    renderNewPreviews();
    updateNewInputFiles();
}

function updateNewInputFiles() {
    const dataTransfer = new DataTransfer();
    newFiles.forEach(file => dataTransfer.items.add(file));
    document.getElementById('images').files = dataTransfer.files;
}

function renderNewPreviews() {
    const previewContainer = document.getElementById('new-image-preview');
    previewContainer.innerHTML = '';

    newFiles.forEach((file, index) => {
        const reader = new FileReader();
        reader.onload = function(e) {
            const div = document.createElement('div');
            div.className = 'relative group';
            div.innerHTML = `
                <img src="${e.target.result}" class="h-32 w-full object-cover rounded-lg shadow-sm border-2 border-green-500" alt="Nova imagem ${index + 1}">
                <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-40 transition rounded-lg flex items-center justify-center">
                    <button type="button" onclick="removeNewImage(${index})" class="bg-red-600 text-white p-1 rounded-full opacity-0 group-hover:opacity-100 transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                    <div class="absolute top-1 right-1 bg-green-500 text-white text-[10px] px-2 py-0.5 rounded">Nova</div>
                </div>
            `;
            previewContainer.appendChild(div);
        }
        reader.readAsDataURL(file);
    });
}



// Simple Drag & Drop support
const dropzone = document.getElementById('dropzone');
dropzone.addEventListener('dragover', (e) => {
    e.preventDefault();
    dropzone.classList.add('border-indigo-500', 'bg-indigo-50');
});
dropzone.addEventListener('dragleave', () => {
    dropzone.classList.remove('border-indigo-500', 'bg-indigo-50');
});
dropzone.addEventListener('drop', (e) => {
    e.preventDefault();
    dropzone.classList.remove('border-indigo-500', 'bg-indigo-50');
    
    const dt = e.dataTransfer;
    const files = dt.files;
    
    const event = { target: { files: files } };
    addNewImages(event);
});

// Dynamic Shipping Scripts
function toggleShippingFields() {
    const toggle = document.getElementById('toggle_shipping');
    const panel = document.getElementById('shipping_config_panel');
    const priceInput = document.getElementById('shipping_price_per_km');
    const latInput = document.getElementById('shipping_origin_lat');
    const lonInput = document.getElementById('shipping_origin_lon');

    if (toggle.checked) {
        panel.classList.remove('hidden');
        priceInput.required = true;
    } else {
        panel.classList.add('hidden');
        priceInput.required = false;
        priceInput.value = '';
        latInput.value = '';
        lonInput.value = '';
        document.getElementById('location_status').classList.add('hidden');
        document.getElementById('txt_capture_location').innerText = 'Capturar Minha Localização';
        const btnCapture = document.getElementById('btn_capture_location');
        btnCapture.classList.remove('bg-green-50', 'text-green-700', 'border-green-200');
        btnCapture.classList.add('bg-white', 'hover:bg-gray-50');
        btnCapture.querySelector('svg').classList.remove('text-green-500');
        btnCapture.querySelector('svg').classList.add('text-indigo-500');
    }
}

function captureLocation() {
    const btnText = document.getElementById('txt_capture_location');
    const btn = document.getElementById('btn_capture_location');
    const statusMsg = document.getElementById('location_status');
    const svgIcon = btn.querySelector('svg');

    if (!navigator.geolocation) {
        alert("Geolocalização não é suportada pelo seu navegador.");
        return;
    }

    btnText.innerText = "Capturando...";
    btn.disabled = true;

    navigator.geolocation.getCurrentPosition(
        function(position) {
            document.getElementById('shipping_origin_lat').value = position.coords.latitude;
            document.getElementById('shipping_origin_lon').value = position.coords.longitude;
            
            btnText.innerText = "Atualizar Localização";
            btn.classList.remove('bg-white', 'hover:bg-gray-50');
            btn.classList.add('bg-green-50', 'text-green-700', 'border-green-200');
            svgIcon.classList.remove('text-indigo-500');
            svgIcon.classList.add('text-green-500');
            statusMsg.classList.remove('hidden');
            btn.disabled = false;
        },
        function(error) {
            console.warn(error);
            const fallbackCep = prompt("Acesso ao GPS bloqueado (HTTP local ou negado). Digite seu CEP para capturar a coordenada da sua cidade (Somente números):");
            if (fallbackCep && fallbackCep.replace(/\D/g, '').length === 8) {
                btnText.innerText = "Buscando CEP...";
                fetch('https://brasilapi.com.br/api/cep/v2/' + fallbackCep.replace(/\D/g, ''))
                    .then(res => res.json())
                    .then(data => {
                        if (data.location && data.location.coordinates && data.location.coordinates.latitude) {
                            document.getElementById('shipping_origin_lat').value = data.location.coordinates.latitude;
                            document.getElementById('shipping_origin_lon').value = data.location.coordinates.longitude;
                            btnText.innerText = "Atualizar Localização";
                            btn.classList.remove('bg-white', 'hover:bg-gray-50');
                            btn.classList.add('bg-green-50', 'text-green-700', 'border-green-200');
                            svgIcon.classList.remove('text-indigo-500');
                            svgIcon.classList.add('text-green-500');
                            statusMsg.innerText = "✓ Coordenadas do CEP salvas com sucesso!";
                            statusMsg.classList.remove('hidden');
                        } else {
                            alert("Não encontramos coordenadas exatas para este CEP na base dos Correios.");
                            btnText.innerText = "Tentar Novamente";
                        }
                    })
                    .catch(e => {
                        alert("Erro ao buscar o CEP na BrasilAPI.");
                        btnText.innerText = "Tentar Novamente";
                    })
                    .finally(() => { btn.disabled = false; });
            } else {
                btnText.innerText = "Tentar Novamente";
                btn.disabled = false;
            }
        },
        { enableHighAccuracy: true, timeout: 5000 }
    );
}

document.addEventListener('DOMContentLoaded', () => {
    // Basic state enforcement in case browser overrides caching mechanism
    const toggle = document.getElementById('toggle_shipping');
    const latVal = document.getElementById('shipping_origin_lat').value;
    
    if (toggle.checked) {
        document.getElementById('shipping_price_per_km').required = true;
    }
});
</script>
</x-products::layouts.master>
