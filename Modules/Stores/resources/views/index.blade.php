<x-products::layouts.master>
    <div class="min-h-screen bg-gray-50 dark:bg-gray-900 py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-12 gap-6">
                <div>
                    <h1 class="text-4xl font-black text-gray-900 dark:text-white tracking-tight">Minhas Lojas</h1>
                    <p class="text-gray-500 dark:text-gray-400 mt-2">Gerencie suas identidades profissionais e escolha onde postar.</p>
                </div>
                <a href="{{ route('stores.create') }}" class="inline-flex items-center justify-center px-6 py-3 border border-transparent text-base font-bold rounded-2xl text-white bg-primary-600 hover:bg-primary-700 shadow-lg transition-all active:scale-95 uppercase tracking-widest">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    Nova Loja
                </a>
            </div>

            <!-- Active Store Context Switcher -->
            <div class="bg-white dark:bg-gray-800 rounded-3xl p-8 shadow-xl border border-gray-100 dark:border-gray-700 mb-12">
                <div class="flex items-center space-x-4 mb-6">
                    <div class="bg-indigo-100 dark:bg-indigo-900/30 p-3 rounded-2xl">
                        <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                    </div>
                    <h2 class="text-xl font-black text-gray-900 dark:text-white tracking-tight">Identidade de Postagem</h2>
                </div>
                
                <form action="{{ route('stores.setActive') }}" method="POST" class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    @csrf
                    <div class="col-span-1 md:col-span-2">
                        <select name="store_id" class="w-full rounded-2xl border-gray-200 dark:border-gray-700 dark:bg-gray-900 text-gray-700 dark:text-gray-300 font-medium focus:ring-primary-500 focus:border-primary-500 transition-all p-4">
                            <option value="none" {{ !session('active_store_id') ? 'selected' : '' }}>Pessoa Física (Anúncio Genérico)</option>
                            @foreach($stores as $store)
                                <option value="{{ $store->id }}" {{ session('active_store_id') == $store->id ? 'selected' : '' }}>
                                    Loja: {{ $store->nome }} ({{ '@' . $store->username }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="bg-gray-900 text-white font-bold py-4 px-6 rounded-2xl hover:bg-black transition-all shadow-md uppercase tracking-widest text-xs">
                        Trocar Perfil
                    </button>
                </form>
                
                <p class="mt-4 text-xs text-gray-400 font-medium italic">
                    * Qualquer anúncio que você criar será vinculado ao perfil selecionado acima.
                </p>
            </div>

            <!-- Stores Grid -->
            @if($stores->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    @foreach($stores as $store)
                        <div class="bg-white dark:bg-gray-800 rounded-[2rem] border border-gray-100 dark:border-gray-700 shadow-sm hover:shadow-2xl hover:-translate-y-1 transition-all duration-500 overflow-hidden flex flex-col">
                            <div class="h-24 bg-gradient-to-br from-gray-50 to-gray-100 dark:from-gray-900 dark:to-gray-800 p-6 flex items-center">
                                <div class="bg-white dark:bg-gray-800 w-12 h-12 rounded-2xl shadow-sm flex items-center justify-center text-primary-600">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                                </div>
                                <div class="ml-4">
                                    <h3 class="font-black text-gray-900 dark:text-white leading-tight">{{ $store->nome }}</h3>
                                    <p class="text-primary-500 text-[10px] font-bold tracking-widest uppercase">@<span>{{ $store->username }}</span></p>
                                </div>
                            </div>
                            
                            <div class="p-6 flex-1 space-y-4">
                                @if($store->endereco)
                                    <div class="flex items-start text-sm text-gray-500 dark:text-gray-400">
                                        <svg class="w-4 h-4 mr-2 mt-0.5 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                        {{ $store->endereco }}
                                    </div>
                                @endif
                                
                                <div class="flex flex-wrap gap-4 pt-2">
                                    @if($store->telefone_fixo)
                                        <div class="flex items-center text-xs font-bold text-gray-400">
                                            <svg class="w-3.5 h-3.5 mr-1.5 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                                            {{ $store->telefone_fixo }}
                                        </div>
                                    @endif
                                    @if($store->whatsapp)
                                        <div class="flex items-center text-xs font-bold text-emerald-500">
                                            <svg class="w-3.5 h-3.5 mr-1.5 opacity-80" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L0 24l6.335-1.662c1.72.94 3.659 1.437 5.63 1.438h.004c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                                            {{ $store->whatsapp }}
                                        </div>
                                    @endif
                                </div>
                            </div>
                            
                            <div class="p-6 bg-gray-50 dark:bg-gray-800/50 flex items-center justify-between border-t border-gray-100 dark:border-gray-700">
                                <a href="{{ route('stores.edit', $store->id) }}" class="text-xs font-bold text-gray-400 hover:text-primary-600 transition-colors uppercase tracking-widest">
                                    Editar
                                </a>
                                <div class="flex items-center space-x-2">
                                    <form action="{{ route('stores.destroy', $store->id) }}" method="POST" onsubmit="return confirm('Tem certeza que deseja excluir esta loja? Todos os anúncios vinculados serão mantidos, mas a loja será removida.')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-2 text-gray-300 hover:text-red-500 transition-colors">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        </button>
                                    </form>
                                    <a href="/loja/{{ $store->username }}" target="_blank" class="p-2 text-gray-300 hover:text-indigo-500 transition-colors">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-24 bg-white dark:bg-gray-800 rounded-[3rem] border-2 border-dashed border-gray-100 dark:border-gray-700 shadow-sm">
                    <div class="bg-gray-50 dark:bg-gray-900 w-20 h-20 rounded-3xl flex items-center justify-center mx-auto mb-6">
                        <svg class="w-10 h-10 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                    </div>
                    <h3 class="text-xl font-black text-gray-900 dark:text-white uppercase tracking-wider">Nenhuma loja cadastrada</h3>
                    <p class="mt-2 text-gray-500 dark:text-gray-400">Comece a profissionalizar seus anúncios criando sua primeira loja.</p>
                    <a href="{{ route('stores.create') }}" class="mt-8 inline-flex items-center px-8 py-4 bg-gray-900 dark:bg-white text-white dark:text-gray-900 font-black rounded-2xl hover:scale-105 transition-all shadow-xl uppercase tracking-widest text-sm">
                        Criar Minha Primeira Loja
                    </a>
                </div>
            @endif

        </div>
    </div>
</x-products::layouts.master>
