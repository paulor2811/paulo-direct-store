<x-products::layouts.master>
    <div class="min-h-screen bg-gray-50 dark:bg-gray-900 py-12">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <div class="mb-12 text-center sm:text-left">
                <a href="{{ route('stores.index') }}" class="text-sm font-bold text-primary-600 hover:text-primary-700 inline-flex items-center uppercase tracking-widest transition-colors mb-4">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    Voltar para Lojas
                </a>
                <h1 class="text-4xl font-black text-gray-900 dark:text-white tracking-tight">Editar Loja</h1>
                <p class="text-gray-500 dark:text-gray-400 mt-2">Atualize as informações da loja: <strong>{{ $store->nome }}</strong></p>
            </div>

            <form action="{{ route('stores.update', $store->id) }}" method="POST" enctype="multipart/form-data" class="space-y-8">
                @csrf
                @method('PUT')
                
                <div class="bg-white dark:bg-gray-800 rounded-[2.5rem] shadow-xl overflow-hidden border border-gray-100 dark:border-gray-700 p-8 sm:p-12">
                    <div class="grid grid-cols-1 gap-8">
                        
                        <!-- Store Logo -->
                        <div class="flex flex-col items-center sm:items-start mb-4">
                            <label class="block text-xs font-black text-gray-400 uppercase tracking-[0.2em] mb-4">Logotipo da Loja</label>
                            <div class="relative group">
                                <div id="logo-preview-wrapper" class="w-32 h-32 rounded-[2rem] bg-gray-50 dark:bg-gray-900 border-2 border-gray-100 dark:border-gray-700 flex items-center justify-center overflow-hidden transition-all group-hover:border-primary-500/50">
                                    @if($store->logo_url)
                                        <img id="current-logo" src="{{ $store->logo_url }}" alt="{{ $store->nome }}" class="w-full h-full object-cover">
                                    @else
                                        <svg id="logo-placeholder" class="w-8 h-8 text-gray-300 group-hover:text-primary-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                    @endif
                                </div>
                                <input type="file" name="logo" id="logo-input" class="absolute inset-0 opacity-0 cursor-pointer" accept="image/*" onchange="previewLogo(this)">
                            </div>
                            <p class="mt-3 text-[10px] text-gray-400 font-medium uppercase tracking-wider">Clique para alterar (Max 5MB)</p>
                            @error('logo') <p class="mt-2 text-xs text-red-500 font-bold uppercase tracking-wider">{{ $message }}</p> @enderror
                        </div>

                        <script>
                            function previewLogo(input) {
                                if (input.files && input.files[0]) {
                                    const reader = new FileReader();
                                    reader.onload = function(e) {
                                        const wrapper = document.getElementById('logo-preview-wrapper');
                                        
                                        // Update preview
                                        wrapper.innerHTML = '';
                                        const img = document.createElement('img');
                                        img.src = e.target.result;
                                        img.className = 'w-full h-full object-cover';
                                        wrapper.appendChild(img);
                                    }
                                    reader.readAsDataURL(input.files[0]);
                                }
                            }
                        </script>

                        <!-- Store Name -->
                        <div>
                            <label for="nome" class="block text-xs font-black text-gray-400 uppercase tracking-[0.2em] mb-3">Nome da Loja <span class="text-red-500">*</span></label>
                            <input type="text" name="nome" id="nome" required value="{{ old('nome', $store->nome) }}" 
                                   class="w-full rounded-2xl border-gray-100 dark:border-gray-700 dark:bg-gray-900 text-gray-900 dark:text-white font-bold focus:ring-primary-500 focus:border-primary-500 p-4 transition-all"
                                   placeholder="Ex: Paulo Auto Peças">
                            @error('nome') <p class="mt-2 text-xs text-red-500 font-bold uppercase tracking-wider">{{ $message }}</p> @enderror
                        </div>

                        <!-- Store Username -->
                        <div>
                            <label for="username" class="block text-xs font-black text-gray-400 uppercase tracking-[0.2em] mb-3">Username único (URL) <span class="text-red-500">*</span></label>
                            <div class="relative flex items-center">
                                <span class="absolute left-4 text-gray-400 font-bold">@</span>
                                <input type="text" name="username" id="username" required value="{{ old('username', $store->username) }}" 
                                       class="w-full rounded-2xl border-gray-100 dark:border-gray-700 dark:bg-gray-900 text-gray-900 dark:text-white font-bold focus:ring-primary-500 focus:border-primary-500 pl-10 p-4 transition-all"
                                       placeholder="pauloautopecas">
                            </div>
                            <p class="mt-2 text-[10px] text-gray-400 font-medium">Sua loja é acessível em: <strong>paulodirect.local/loja/{{ $store->username }}</strong></p>
                            @error('username') <p class="mt-2 text-xs text-red-500 font-bold uppercase tracking-wider">{{ $message }}</p> @enderror
                        </div>

                        <!-- Address -->
                        <div>
                            <label for="endereco" class="block text-xs font-black text-gray-400 uppercase tracking-[0.2em] mb-3">Endereço Físico</label>
                            <input type="text" name="endereco" id="endereco" value="{{ old('endereco', $store->endereco) }}" 
                                   class="w-full rounded-2xl border-gray-100 dark:border-gray-700 dark:bg-gray-900 text-gray-900 dark:text-white font-bold focus:ring-primary-500 focus:border-primary-500 p-4 transition-all"
                                   placeholder="Rua, Número, Bairro, Cidade - UF">
                            @error('endereco') <p class="mt-2 text-xs text-red-500 font-bold uppercase tracking-wider">{{ $message }}</p> @enderror
                        </div>

                        <!-- Contact Grid -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <div>
                                <label for="telefone_fixo" class="block text-xs font-black text-gray-400 uppercase tracking-[0.2em] mb-3">Telefone Fixo</label>
                                <input type="text" name="telefone_fixo" id="telefone_fixo" value="{{ old('telefone_fixo', $store->telefone_fixo) }}" 
                                       class="w-full rounded-2xl border-gray-100 dark:border-gray-700 dark:bg-gray-900 text-gray-900 dark:text-white font-bold focus:ring-primary-500 focus:border-primary-500 p-4 transition-all"
                                       placeholder="(00) 0000-0000">
                                @error('telefone_fixo') <p class="mt-2 text-xs text-red-500 font-bold uppercase tracking-wider">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label for="whatsapp" class="block text-xs font-black text-gray-400 uppercase tracking-[0.2em] mb-3">WhatsApp / Celular</label>
                                <input type="text" name="whatsapp" id="whatsapp" value="{{ old('whatsapp', $store->whatsapp) }}" 
                                       class="w-full rounded-2xl border-gray-100 dark:border-gray-700 dark:bg-gray-900 text-gray-900 dark:text-white font-bold focus:ring-primary-500 focus:border-primary-500 p-4 transition-all"
                                       placeholder="(00) 00000-0000">
                                @error('whatsapp') <p class="mt-2 text-xs text-red-500 font-bold uppercase tracking-wider">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        <!-- Active Toggle -->
                        <div class="flex items-center space-x-3 pt-4">
                            <input type="hidden" name="is_active" value="0">
                            <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', $store->is_active) ? 'checked' : '' }}
                                   class="w-5 h-5 text-primary-600 border-gray-200 rounded focus:ring-primary-500 transition-all">
                            <label for="is_active" class="text-sm font-bold text-gray-700 dark:text-gray-300 uppercase tracking-widest">Loja Ativa</label>
                        </div>

                    </div>
                    
                    <div class="mt-12 flex items-center justify-between gap-4">
                        <button type="button" onclick="if(confirm('Tem certeza?')) document.getElementById('delete-form').submit()" class="text-xs font-black text-red-500 uppercase tracking-widest hover:text-red-700 transition-colors">
                            Excluir Loja
                        </button>
                        <button type="submit" class="bg-primary-600 text-white font-black py-5 px-12 rounded-3xl hover:bg-primary-700 transition-all shadow-2xl shadow-primary-500/30 active:scale-95 uppercase tracking-[0.2em] text-xs">
                            Salvar Alterações
                        </button>
                    </div>
                </div>

            </form>

            <form id="delete-form" action="{{ route('stores.destroy', $store->id) }}" method="POST" class="hidden">
                @csrf
                @method('DELETE')
            </form>

        </div>
    </div>
</x-products::layouts.master>
