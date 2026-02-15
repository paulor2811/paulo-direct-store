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

            @if($products->count() > 0)
                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
                    @foreach($products as $product)
                        <div class="group relative bg-white border border-gray-200 rounded-2xl shadow-sm dark:bg-gray-800 dark:border-gray-700 hover:shadow-xl transition-all duration-300 flex flex-col h-full overflow-hidden">
                            <!-- Image / Carousel Container -->
                            <div class="w-full relative bg-white dark:bg-gray-800" style="height: 280px;">
                                @if($product->images()->isNotEmpty())
                                    <div class="carousel relative w-full h-full overflow-hidden" x-data="{ activeSlide: 0, slides: {{ $product->images()->count() }} }">
                                        <!-- Slides -->
                                        @foreach($product->images() as $index => $foto)
                                            <div class="absolute inset-0 transition-opacity duration-500 ease-in-out bg-white dark:bg-gray-800"
                                                 x-show="activeSlide === {{ $index }}"
                                                 x-transition:enter="transition ease-out duration-300"
                                                 x-transition:enter-start="opacity-0"
                                                 x-transition:enter-end="opacity-100"
                                                 x-transition:leave="transition ease-in duration-300"
                                                 x-transition:leave-start="opacity-100"
                                                 x-transition:leave-end="opacity-0">
                                                @php
                                                    $url = Str::startsWith($foto->caminho_imagem, 'http') ? $foto->caminho_imagem : $foto->url;
                                                @endphp
                                                <img src="{{ $url }}" alt="{{ $product->nome }}" class="h-full w-full object-contain p-3" onerror="this.onerror=null; this.src='https://placehold.co/400x400?text=Sem+Imagem';">
                                            </div>
                                        @endforeach

                                        <!-- Controls (only if > 1 image) -->
                                        @if($product->images()->count() > 1)
                                            <button @click.prevent="activeSlide = activeSlide === 0 ? slides - 1 : activeSlide - 1" class="absolute left-2 top-1/2 -translate-y-1/2 p-1.5 rounded-full bg-black/20 hover:bg-black/40 text-white transition-colors backdrop-blur-sm z-10">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                                            </button>
                                            <button @click.prevent="activeSlide = activeSlide === slides - 1 ? 0 : activeSlide + 1" class="absolute right-2 top-1/2 -translate-y-1/2 p-1.5 rounded-full bg-black/20 hover:bg-black/40 text-white transition-colors backdrop-blur-sm z-10">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                                            </button>
                                            
                                            <!-- Dots -->
                                            <div class="absolute bottom-2 left-1/2 transform -translate-x-1/2 flex space-x-1.5 z-10">
                                                <template x-for="i in slides">
                                                    <button @click.prevent="activeSlide = i - 1" class="w-2 h-2 rounded-full transition-colors shadow-sm" :class="activeSlide === i - 1 ? 'bg-primary-600' : 'bg-gray-300/80 hover:bg-gray-400'"></button>
                                                </template>
                                            </div>
                                        @endif
                                    </div>
                                @else
                                    <div class="flex h-full w-full items-center justify-center bg-gray-50 dark:bg-gray-800 text-gray-300 dark:text-gray-600">
                                        <svg class="h-20 w-20" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                    </div>
                                @endif
                            </div>
                            
                            <div class="p-5 flex-1 flex flex-col">
                                <div class="mb-4">
                                    <h3 class="text-base font-bold text-gray-900 dark:text-white line-clamp-2 mb-1 group-hover:text-primary-600 transition-colors">
                                        <a href="{{ route('products.show', $product->id) }}">
                                            <span aria-hidden="true" class="absolute inset-0"></span>
                                            {{ $product->nome }}
                                        </a>
                                    </h3>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ $product->marca ?? 'Genérico' }}</p>
                                </div>
                                <div class="mt-auto flex items-center justify-between">
                                    <p class="text-xl font-bold text-gray-900 dark:text-white">R$ {{ number_format($product->preco, 2, ',', '.') }}</p>
                                    <form action="{{ route('cart.add', $product->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="text-primary-600 hover:bg-primary-50 dark:hover:bg-primary-900/30 p-2 rounded-lg transition-colors z-20 relative" title="Adicionar ao Carrinho">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
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
