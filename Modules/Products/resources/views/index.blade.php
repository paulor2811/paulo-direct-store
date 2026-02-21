<x-products::layouts.master>
    <section class="py-8 bg-gray-50 md:py-12 dark:bg-gray-900 min-h-screen">
        <div class="max-w-screen-xl px-4 mx-auto 2xl:px-0">
            <div class="mb-8 text-center sm:mb-12">
                <h1 class="text-3xl font-extrabold tracking-tight text-gray-900 dark:text-white sm:text-4xl">
                    Nossos Produtos
                </h1>
                <p class="mt-4 text-lg text-gray-500 dark:text-gray-400">
                    Confira as melhores ofertas
                </p>
            </div>

            <!-- Filter Bar -->
            <div class="mb-8 flex flex-col gap-6 p-6 bg-white dark:bg-gray-800 rounded-3xl border border-gray-100 dark:border-gray-700 shadow-sm">
                
                <div class="flex flex-col md:flex-row items-center justify-between gap-6">
                    <!-- Search Bar -->
                    <div class="w-full md:max-w-md">
                        <form action="{{ url()->current() }}" method="GET" class="relative group">
                            @foreach(request()->except('search', 'page') as $key => $value)
                                <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                            @endforeach
                            <input type="text" name="search" value="{{ request('search') }}" 
                                   placeholder="O que você está procurando? (ex: multimídia)"
                                   class="w-full bg-gray-50 dark:bg-gray-900 border-none rounded-2xl py-4 pl-12 pr-4 text-sm font-medium focus:ring-2 focus:ring-primary-500 transition-all">
                            <svg class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400 group-focus-within:text-primary-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                        </form>
                    </div>

                    <!-- Sorting -->
                    <div class="flex items-center gap-4 w-full md:w-auto">
                        <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest block whitespace-nowrap">Ordenar:</span>
                        <select onchange="window.location.href = this.value" 
                                class="w-full md:w-auto bg-gray-50 dark:bg-gray-900 border-none rounded-xl text-xs font-bold text-gray-700 dark:text-gray-300 focus:ring-primary-500 p-3 pr-10 transition-all cursor-pointer">
                            <option value="{{ request()->fullUrlWithQuery(['sort' => 'recente']) }}" {{ request('sort') == 'recente' ? 'selected' : '' }}>Mais Recentes</option>
                            <option value="{{ request()->fullUrlWithQuery(['sort' => 'preco_asc']) }}" {{ request('sort') == 'preco_asc' ? 'selected' : '' }}>Menor Preço</option>
                            <option value="{{ request()->fullUrlWithQuery(['sort' => 'preco_desc']) }}" {{ request('sort') == 'preco_desc' ? 'selected' : '' }}>Maior Preço</option>
                        </select>
                    </div>
                </div>

                <div class="flex flex-col lg:flex-row items-start lg:items-center justify-between gap-6 pt-6 border-t border-gray-50 dark:border-gray-700/50">
                    <!-- Conditions -->
                    <div class="flex flex-wrap items-center gap-2">
                        <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest mr-2">Estado:</span>
                        @php
                            $conditions = [
                                '' => 'Todos',
                                'novo' => 'Novo',
                                'seminovo' => 'Seminovo',
                                'usado' => 'Usado',
                                'sucata' => 'Sucata',
                            ];
                        @endphp

                        @foreach($conditions as $value => $label)
                            <a href="{{ request()->fullUrlWithQuery(['condition' => $value]) }}" 
                               class="px-4 py-2 rounded-xl text-[10px] font-bold transition-all {{ request('condition') == $value ? 'bg-primary-600 text-white shadow-lg shadow-primary-500/30' : 'bg-gray-50 dark:bg-gray-900 text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-850' }}">
                                {{ $label }}
                            </a>
                        @endforeach
                    </div>

                    <!-- Price Range -->
                    <form action="{{ url()->current() }}" method="GET" class="flex flex-wrap items-center gap-3 w-full lg:w-auto">
                        @foreach(request()->except(['min_price', 'max_price', 'page']) as $key => $value)
                            <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                        @endforeach
                        <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest mr-1">Faixa de Preço (R$):</span>
                        <div class="flex items-center gap-2">
                            <input type="number" name="min_price" value="{{ request('min_price') }}" placeholder="Min" 
                                   class="w-20 bg-gray-50 dark:bg-gray-900 border-none rounded-xl py-2 px-3 text-xs font-bold focus:ring-primary-500">
                            <span class="text-gray-300">/</span>
                            <input type="number" name="max_price" value="{{ request('max_price') }}" placeholder="Max" 
                                   class="w-20 bg-gray-50 dark:bg-gray-900 border-none rounded-xl py-2 px-3 text-xs font-bold focus:ring-primary-500">
                        </div>
                        <button type="submit" class="p-2 bg-primary-50 dark:bg-primary-900/10 text-primary-600 rounded-xl hover:bg-primary-100 transition-all">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
                        </button>
                    </form>
                </div>
            </div>

            @if($products->count() > 0)
                <div class="grid grid-cols-2 gap-4 sm:gap-6 lg:gap-8 lg:grid-cols-3 xl:grid-cols-4">
                    @foreach($products as $product)
                        <div x-data="{ activeSlide: 0, slides: {{ $product->images()->count() }} }" 
                             class="group relative bg-white border border-gray-100/80 rounded-[1.5rem] shadow-sm dark:bg-gray-800 dark:border-gray-700 hover:shadow-2xl hover:-translate-y-1 transition-all duration-500 flex flex-col h-full overflow-hidden">
                            
                            <!-- 1. IMAGE LAYER (z-0) -->
                            <div class="w-full relative bg-[#F9FAFB] dark:bg-gray-950 overflow-hidden aspect-[3/4] sm:h-[350px] md:h-[380px]">
                                <!-- Dynamic Condition Badge -->
                                <div class="absolute top-2 left-2 sm:top-4 sm:left-4 z-30">
                                    @php
                                        $badgeConfig = [
                                            'novo' => ['label' => 'Novo', 'bg' => 'bg-emerald-500/90', 'text' => 'text-white', 'border' => 'border-emerald-400/30'],
                                            'seminovo' => ['label' => 'Seminovo', 'bg' => 'bg-orange-500/90', 'text' => 'text-white', 'border' => 'border-orange-400/30'],
                                            'usado' => ['label' => 'Usado', 'bg' => 'bg-indigo-500/90', 'text' => 'text-white', 'border' => 'border-indigo-400/30'],
                                            'sucata' => ['label' => 'Sucata', 'bg' => 'bg-amber-500/90', 'text' => 'text-white', 'border' => 'border-amber-400/30'],
                                        ];
                                        $config = $badgeConfig[$product->condicao] ?? $badgeConfig['novo'];
                                    @endphp
                                    <span class="inline-flex items-center px-2 py-0.5 sm:px-3 sm:py-1 rounded-full text-[8px] sm:text-[10px] font-bold {{ $config['bg'] }} {{ $config['text'] }} backdrop-blur-md shadow-sm border {{ $config['border'] }}">
                                        {{ $config['label'] }}
                                    </span>
                                </div>
                                
                                <div class="relative w-full h-full group-hover:scale-105 transition-transform duration-700 overflow-hidden">
                                    @if($product->images()->isNotEmpty())
                                        @foreach($product->images() as $index => $foto)
                                            <div class="absolute inset-0 transition-opacity duration-700 ease-in-out"
                                                 x-show="activeSlide === {{ $index }}"
                                                 x-transition:enter="transition ease-out duration-500"
                                                 x-transition:enter-start="opacity-0 scale-95"
                                                 x-transition:enter-end="opacity-100 scale-100"
                                                 x-transition:leave="transition ease-in duration-500"
                                                 x-transition:leave-start="opacity-100 scale-100"
                                                 x-transition:leave-end="opacity-0 scale-105">
                                                @php
                                                    $url = Str::startsWith($foto->caminho_imagem, 'http') ? $foto->caminho_imagem : $foto->url;
                                                @endphp
                                                {{-- 1.1 Blurred Backdrop Layer --}}
                                                <div class="absolute inset-0 z-0">
                                                    <img src="{{ $url }}" class="h-full w-full object-cover blur-2xl opacity-50 dark:opacity-60 saturate-150 scale-125 transition-transform duration-700 group-hover:scale-110" aria-hidden="true">
                                                </div>
                                                
                                                {{-- 1.2 Main Product Image (Sharp) --}}
                                                <img src="{{ $url }}" alt="{{ $product->nome }}" 
                                                    class="relative z-10 h-full w-full object-contain p-2 sm:p-4 dark:mix-blend-normal" 
                                                    onerror="this.onerror=null; this.src='https://placehold.co/400x500?text=Sem+Imagem';">
                                            </div>
                                        @endforeach
                                    @else
                                        <div class="flex h-full w-full items-center justify-center bg-gray-50 dark:bg-gray-800 text-gray-200 dark:text-gray-600">
                                            <svg class="h-16 w-16 sm:h-24 sm:w-24" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="0.5">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <!-- 2. LINK LAYER (z-10) - Pure transparent overlay for the card navigation -->
                            <a href="{{ route('products.show', $product->id) }}" class="absolute inset-0 z-10 cursor-pointer" aria-label="Ver {{ $product->nome }}"></a>

                            <!-- 3. INTERACTIVE LAYER (z-20) - Buttons and controls -->
                            <!-- Carousel Controls -->
                            @if($product->images()->count() > 1)
                                <button @click.prevent.stop="activeSlide = activeSlide === 0 ? slides - 1 : activeSlide - 1" 
                                        class="absolute left-2 top-[35%] sm:left-3 p-1.5 sm:p-2 rounded-full bg-white/90 hover:bg-white text-gray-800 shadow-xl transition-all opacity-0 group-hover:opacity-100 -translate-x-2 group-hover:translate-x-0 z-20 backdrop-blur-sm cursor-pointer">
                                    <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path d="M15 19l-7-7 7-7"></path></svg>
                                </button>
                                <button @click.prevent.stop="activeSlide = activeSlide === slides - 1 ? 0 : activeSlide + 1" 
                                        class="absolute right-2 top-[35%] sm:right-3 p-1.5 sm:p-2 rounded-full bg-white/90 hover:bg-white text-gray-800 shadow-xl transition-all opacity-0 group-hover:opacity-100 translate-x-2 group-hover:translate-x-0 z-20 backdrop-blur-sm cursor-pointer">
                                    <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path d="M9 5l7 7-7 7"></path></svg>
                                </button>
                            @endif
                            
                            <!-- Content Area -->
                            <div class="p-4 sm:p-6 flex-1 flex flex-col relative z-20 pointer-events-none">
                                <div class="mb-2 sm:mb-4">
                                    <span class="text-[8px] sm:text-[10px] font-bold tracking-[0.15em] uppercase text-primary-500 mb-1 sm:mb-2 block">{{ $product->marca ?? 'Premium Collection' }}</span>
                                    <h3 class="text-sm sm:text-lg font-bold text-gray-900 dark:text-white line-clamp-1 mb-1 sm:mb-2 group-hover:text-primary-600 transition-colors">
                                        {{ $product->nome }}
                                    </h3>
                                    <p class="hidden sm:block text-xs text-gray-500 dark:text-gray-400 line-clamp-2 leading-relaxed">
                                        {{ Str::limit($product->descricao, 80) }}
                                    </p>
                                </div>
                                
                                <div class="mt-auto pt-4 sm:pt-6 flex flex-col sm:flex-row items-start sm:items-center justify-between border-t border-gray-50 dark:border-gray-700/50">
                                    <div class="mb-3 sm:mb-0">
                                        <span class="text-[8px] sm:text-[10px] text-gray-400 dark:text-gray-500 block mb-0.5">Preço à vista</span>
                                        <p class="text-lg sm:text-2xl font-black text-gray-900 dark:text-white tracking-tight">R$ {{ number_format($product->preco, 2, ',', '.') }}</p>
                                    </div>
                                    <form action="{{ route('cart.add', $product->id) }}" method="POST" class="relative z-20 pointer-events-auto w-full sm:w-auto">
                                        @csrf
                                        <button type="submit" class="relative group/btn flex items-center justify-center bg-primary-600 hover:bg-primary-700 text-white w-full sm:w-12 h-10 sm:h-12 rounded-xl sm:rounded-2xl shadow-lg shadow-primary-200 dark:shadow-none transition-all duration-300 hover:scale-110 active:scale-95" title="Adicionar ao Carrinho">
                                            <span class="sm:hidden mr-2 font-bold text-xs uppercase tracking-wider">Comprar</span>
                                            <svg class="w-5 h-5 sm:w-6 sm:h-6 transition-transform group-hover/btn:rotate-12" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                
                <div class="mt-12">
                    {{ $products->links() }}
                </div>
            @else
                <!-- Empty State -->
                <div class="text-center py-24 bg-white dark:bg-gray-800 rounded-3xl shadow-sm border border-gray-50 dark:border-gray-700 max-w-2xl mx-auto">
                    <div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-primary-50 dark:bg-primary-900/20 mb-6">
                        <svg class="w-10 h-10 text-primary-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                    <h3 class="mt-2 text-xl font-bold text-gray-900 dark:text-white">Nenhum produto encontrado</h3>
                    <p class="mt-2 text-gray-500 dark:text-gray-400 max-w-sm mx-auto">
                        Não encontramos produtos nesta categoria no momento. Tente novamente mais tarde.
                    </p>
                    <div class="mt-8">
                        <a href="{{ route('products.categories') }}" class="inline-flex items-center px-6 py-3 border border-transparent shadow-sm text-sm font-medium rounded-xl text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-colors">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                            Voltar para Categorias
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </section>
</x-products::layouts.master>
