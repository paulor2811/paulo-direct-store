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

        {{-- Dynamic Shipping Configuration --}}
        <div class="bg-gray-50 border border-gray-200 rounded-lg p-5">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h3 class="text-sm font-bold text-gray-900">Oferecer Entrega Própria</h3>
                    <p class="text-xs text-gray-500 mt-0.5">Permitir que compradores simulem o frete até eles baseado no custo por Km de distância.</p>
                </div>
                <label class="relative inline-flex items-center cursor-pointer">
                    <input type="checkbox" id="toggle_shipping" class="sr-only peer" onchange="toggleShippingFields()">
                    <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-indigo-300 dark:peer-focus:ring-indigo-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-indigo-600"></div>
                </label>
            </div>

            <div id="shipping_config_panel" class="hidden grid grid-cols-1 sm:grid-cols-2 gap-4 pt-3 border-t border-gray-200">
                <div>
                    <label for="shipping_price_per_km" class="block text-xs font-bold text-gray-700 uppercase tracking-widest">Preço por Km (R$) *</label>
                    <input type="number" step="0.01" min="0" name="shipping_price_per_km" id="shipping_price_per_km" value="{{ old('shipping_price_per_km') }}" placeholder="Ex: 2.50"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-widest">Ponto de Partida *</label>
                    <button type="button" onclick="captureLocation()" id="btn_capture_location" class="mt-1 w-full inline-flex justify-center items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors">
                        <svg class="w-4 h-4 mr-2 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                        <span id="txt_capture_location">Capturar Minha Localização</span>
                    </button>
                    <input type="hidden" name="shipping_origin_lat" id="shipping_origin_lat" value="{{ old('shipping_origin_lat') }}">
                    <input type="hidden" name="shipping_origin_lon" id="shipping_origin_lon" value="{{ old('shipping_origin_lon') }}">
                    <p id="location_status" class="mt-1 text-xs text-green-600 hidden font-semibold">✓ Localização capturada com sucesso!</p>
                </div>
            </div>
        </div>

        {{-- Images Upload --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Imagens do Produto (Até 5)</label>
            <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md hover:border-indigo-400 transition" id="dropzone">
                <div class="space-y-1 text-center">
                    <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                        <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                    <div class="flex text-sm text-gray-600">
                        <label for="images" class="relative cursor-pointer bg-white rounded-md font-medium text-indigo-600 hover:text-indigo-500 focus-within:outline-none">
                            <span>Upload de imagens</span>
                            <input id="images" name="images[]" type="file" class="sr-only" multiple accept="image/*" onchange="addImages(event)">
                        </label>
                        <p class="pl-1">ou arraste e solte</p>
                    </div>
                    <p class="text-xs text-gray-500">PNG, JPG, GIF, WEBP até 10MB cada. Máximo 5 fotos.</p>
                </div>
            </div>
            @error('images.*')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror

            {{-- Image Preview --}}
            <div id="image-preview" class="mt-4 grid grid-cols-2 md:grid-cols-5 gap-4"></div>
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
let allFiles = [];
const maxImages = 5;

function addImages(event) {
    const files = Array.from(event.target.files);
    
    if (allFiles.length + files.length > maxImages) {
        alert(`Você pode subir no máximo ${maxImages} imagens.`);
        event.target.value = '';
        return;
    }

    files.forEach(file => {
        if (!allFiles.some(f => f.name === file.name && f.size === file.size)) {
            allFiles.push(file);
        }
    });

    renderPreviews();
    updateInputFiles();
}

function removeImage(index) {
    allFiles.splice(index, 1);
    renderPreviews();
    updateInputFiles();
}

function updateInputFiles() {
    const dataTransfer = new DataTransfer();
    allFiles.forEach(file => dataTransfer.items.add(file));
    document.getElementById('images').files = dataTransfer.files;
}

function renderPreviews() {
    const previewContainer = document.getElementById('image-preview');
    previewContainer.innerHTML = '';

    allFiles.forEach((file, index) => {
        const reader = new FileReader();
        reader.onload = function(e) {
            const div = document.createElement('div');
            div.className = 'relative group';
            div.innerHTML = `
                <img src="${e.target.result}" class="h-32 w-full object-cover rounded-lg shadow-sm" alt="Preview ${index + 1}">
                <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-40 transition rounded-lg flex items-center justify-center">
                    <button type="button" onclick="removeImage(${index})" class="bg-red-600 text-white p-1 rounded-full opacity-0 group-hover:opacity-100 transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                    <span class="absolute bottom-1 right-1 bg-black/50 text-white text-[10px] px-1 rounded">${index + 1}</span>
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
    addImages(event);
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
        
        // If they checked it but haven't captured location yet, make sure the backend knows it's required (handled in validation)
    } else {
        panel.classList.add('hidden');
        priceInput.required = false;
        priceInput.value = '';
        latInput.value = '';
        lonInput.value = '';
        document.getElementById('location_status').classList.add('hidden');
        document.getElementById('txt_capture_location').innerText = 'Capturar Minha Localização';
        document.getElementById('btn_capture_location').classList.remove('bg-green-50', 'text-green-700', 'border-green-200');
    }
}

function captureLocation() {
    const btnText = document.getElementById('txt_capture_location');
    const btn = document.getElementById('btn_capture_location');
    const statusMsg = document.getElementById('location_status');

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
            
            btnText.innerText = "Capturado!";
            btn.classList.add('bg-green-50', 'text-green-700', 'border-green-200');
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
                            btnText.innerText = "Capturado via CEP!";
                            btn.classList.add('bg-green-50', 'text-green-700', 'border-green-200');
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

// Retain state on validation error
document.addEventListener('DOMContentLoaded', () => {
    if (document.getElementById('shipping_price_per_km').value || document.getElementById('shipping_origin_lat').value) {
        document.getElementById('toggle_shipping').checked = true;
        toggleShippingFields();
        
        if (document.getElementById('shipping_origin_lat').value) {
            document.getElementById('txt_capture_location').innerText = "Capturado!";
            document.getElementById('btn_capture_location').classList.add('bg-green-50', 'text-green-700', 'border-green-200');
            document.getElementById('location_status').classList.remove('hidden');
        }
    }
});
</script>

{{-- QUICK SETUP MODAL --}}
@if($missingPhone || $missingAddress)
<div id="quick-setup-modal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-gray-900/80 backdrop-blur-sm">
    <div class="bg-white dark:bg-gray-800 rounded-2xl w-full max-w-2xl shadow-2xl overflow-hidden flex flex-col max-h-[90vh]">
        <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 flex justify-between items-center">
            <div>
                <h3 class="text-lg font-bold text-gray-900 dark:text-white">Complete seu Cadastro</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400">Precisamos de mais alguns dados antes de você publicar seu primeiro anúncio.</p>
            </div>
        </div>

        <div class="p-6 overflow-y-auto">
            <form action="{{ route('profile.quick-setup') }}" method="POST" id="quickSetupForm" class="space-y-6">
                @csrf
                
                {{-- Contato --}}
                <div class="bg-indigo-50/50 dark:bg-indigo-900/10 p-4 rounded-xl border border-indigo-100 dark:border-indigo-800/30">
                    <h4 class="text-sm font-semibold text-indigo-900 dark:text-indigo-300 mb-4 flex items-center">
                        <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                        </svg>
                        Contato
                    </h4>
                    <div>
                        <label for="qs_phone" class="block text-sm font-medium text-gray-700 dark:text-gray-300">WhatsApp / Telefone *</label>
                        <input type="text" name="phone" id="qs_phone" required value="{{ auth()->user()->phone }}" placeholder="(11) 99999-9999"
                            class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        @error('phone') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>
                </div>

                {{-- Endereço --}}
                <div class="bg-gray-50 dark:bg-gray-800/50 p-4 rounded-xl border border-gray-100 dark:border-gray-700">
                    <div class="flex justify-between items-center mb-4">
                        <h4 class="text-sm font-semibold text-gray-900 dark:text-white flex items-center">
                            <svg class="w-4 h-4 mr-2 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            Endereço de Origem
                        </h4>
                        <span id="qs-cep-loading" class="hidden text-xs text-indigo-600 font-medium animate-pulse">Buscando CEP...</span>
                    </div>

                    <div class="mb-5 p-3 bg-blue-50 dark:bg-blue-900/20 text-blue-700 dark:text-blue-300 rounded-lg text-sm flex items-start border border-blue-100 dark:border-blue-800">
                        <svg class="w-5 h-5 mr-2 shrink-0 mt-0.5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                        <span class="leading-relaxed leading-tight text-[13px]"><strong>Privacidade Garantida:</strong> Fique tranquilo, o seu endereço completo não será exibido para ninguém. Nos seus anúncios, sempre aparecerá apenas a sua <strong>Cidade e Estado</strong> para o comprador saber de onde vem a mercadoria.</span>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="sm:col-span-2">
                            <label for="qs_cep" class="block text-sm font-medium text-gray-700 dark:text-gray-300">CEP *</label>
                            <input type="text" name="cep" id="qs_cep" required placeholder="00000-000" maxlength="9"
                                class="mt-1 block w-1/2 rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            @error('cep') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <div class="sm:col-span-2">
                            <label for="qs_street" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Rua *</label>
                            <input type="text" name="street" id="qs_street" required
                                class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm bg-gray-50">
                        </div>

                        <div>
                            <label for="qs_number" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Número</label>
                            <input type="text" name="number" id="qs_number"
                                class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        </div>

                        <div>
                            <label for="qs_complement" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Complemento</label>
                            <input type="text" name="complement" id="qs_complement"
                                class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        </div>

                        <div class="sm:col-span-2">
                            <label for="qs_neighborhood" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Bairro *</label>
                            <input type="text" name="neighborhood" id="qs_neighborhood" required
                                class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm bg-gray-50">
                        </div>

                        <div>
                            <label for="qs_city" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Cidade *</label>
                            <input type="text" name="city" id="qs_city" required
                                class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm bg-gray-50">
                        </div>

                        <div>
                            <label for="qs_state" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Estado (UF) *</label>
                            <input type="text" name="state" id="qs_state" required maxlength="2"
                                class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm bg-gray-50">
                        </div>
                    </div>
                </div>

                <div class="pt-4 flex justify-end">
                    <button type="submit" class="w-full sm:w-auto px-6 py-3 border border-transparent rounded-xl shadow-sm text-sm font-bold text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors">
                        Salvar e Continuar Anunciando
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Prevent background scrolling
        document.body.style.overflow = 'hidden';

        const zipInput = document.getElementById('qs_cep');
        const streetInput = document.getElementById('qs_street');
        const neighborhoodInput = document.getElementById('qs_neighborhood');
        const cityInput = document.getElementById('qs_city');
        const stateInput = document.getElementById('qs_state');
        const loadingIndicator = document.getElementById('qs-cep-loading');

        const handleCepInput = async () => {
            const cep = zipInput.value.replace(/\D/g, '');
            
            if (cep.length === 8) {
                loadingIndicator.classList.remove('hidden');
                
                try {
                    const response = await fetch('/api/geolocation/calculate-shipping', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({ cep: cep })
                    });

                    const data = await response.json();

                    if (response.ok) {
                        if (data.street) streetInput.value = data.street;
                        if (data.neighborhood) neighborhoodInput.value = data.neighborhood;
                        if (data.city) cityInput.value = data.city;
                        if (data.state) stateInput.value = data.state;
                        
                        document.getElementById('qs_number').focus();
                    }
                } catch (error) {
                    console.error('Erro ao buscar CEP:', error);
                } finally {
                    loadingIndicator.classList.add('hidden');
                }
            }
        };

        zipInput.addEventListener('input', handleCepInput);
        zipInput.addEventListener('change', handleCepInput);
    });
</script>
@endif

</x-products::layouts.master>
