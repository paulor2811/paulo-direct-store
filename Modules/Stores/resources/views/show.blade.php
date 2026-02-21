<x-products::layouts.master>
    <div class="min-h-screen bg-gray-50 dark:bg-gray-900 py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- Store Header Card -->
            <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-xl overflow-hidden mb-8 border border-gray-100 dark:border-gray-700">
                <div class="h-32 bg-gradient-to-r from-indigo-600 to-primary-600"></div>
                <div class="px-8 pb-8">
                    <div class="relative flex flex-col sm:flex-row items-center sm:items-end -mt-16 sm:space-x-8">
                        <!-- Store Icon -->
                        <div class="relative">
                            <div class="h-32 w-32 rounded-3xl border-4 border-white dark:border-gray-800 shadow-2xl bg-gray-900 dark:bg-gray-700 flex items-center justify-center text-white">
                                <svg class="w-16 h-16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                            </div>
                        </div>

                        <!-- Store Info -->
                        <div class="mt-6 sm:mt-0 flex-1 text-center sm:text-left">
                            <h1 class="text-3xl font-black text-gray-900 dark:text-white tracking-tight">{{ $store->nome }}</h1>
                            <p class="text-primary-500 font-bold tracking-wider uppercase text-xs mt-1">@<span>{{ $store->username }}</span></p>
                            
                            <div class="mt-4 flex flex-wrap justify-center sm:justify-start gap-4">
                                @if($store->endereco)
                                    <div class="flex items-center text-gray-500 dark:text-gray-400 text-sm">
                                        <svg class="w-4 h-4 mr-1.5 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                        {{ $store->endereco }}
                                    </div>
                                @endif
                                <div class="flex items-center text-gray-500 dark:text-gray-400 text-sm">
                                    <svg class="w-4 h-4 mr-1.5 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                                    {{ $products->total() }} Itens
                                </div>
                            </div>
                        </div>

                        <!-- Contact Actions -->
                        <div class="mt-8 sm:mt-0 flex space-x-3">
                            @if($store->whatsapp)
                                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $store->whatsapp) }}" target="_blank" class="bg-emerald-500 text-white px-6 py-3 rounded-2xl font-bold hover:bg-emerald-600 transition-all shadow-lg active:scale-95 text-xs uppercase tracking-widest flex items-center">
                                    <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L0 24l6.335-1.662c1.72.94 3.659 1.437 5.63 1.438h.004c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                                    WhatsApp
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Store Catalog Section -->
            <div class="space-y-8">
                <div class="flex items-center justify-between">
                    <h2 class="text-2xl font-black text-gray-900 dark:text-white tracking-tight">Produtos da Loja</h2>
                </div>

                @if($products->count() > 0)
                    <div class="grid grid-cols-2 gap-4 sm:gap-6 lg:gap-8 lg:grid-cols-3 xl:grid-cols-4">
                        @foreach($products as $product)
                            <div class="group relative bg-white border border-gray-100/80 rounded-[1.5rem] shadow-sm dark:bg-gray-800 dark:border-gray-700 hover:shadow-2xl hover:-translate-y-1 transition-all duration-500 flex flex-col h-full overflow-hidden">
                                
                                <div class="w-full relative bg-[#F9FAFB] dark:bg-gray-950 overflow-hidden aspect-[3/4] sm:h-[350px]">
                                    <div class="absolute top-2 left-2 z-30">
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-[10px] font-bold bg-emerald-500 text-white backdrop-blur-md shadow-sm">
                                            {{ ucfirst($product->condicao) }}
                                        </span>
                                    </div>
                                    <a href="{{ route('products.show', $product->id) }}" class="block h-full w-full">
                                        @php $img = $product->main_image_url ?: 'https://placehold.co/400x500?text=Sem+Imagem'; @endphp
                                        <div class="absolute inset-0 z-0">
                                            <img src="{{ $img }}" class="h-full w-full object-cover blur-2xl opacity-50 saturate-150 scale-125" aria-hidden="true">
                                        </div>
                                        <img src="{{ $img }}" class="relative z-10 h-full w-full object-contain p-4" alt="{{ $product->nome }}">
                                    </a>
                                </div>
                                <div class="p-4 flex-1 flex flex-col">
                                    <span class="text-[8px] font-bold tracking-[0.15em] uppercase text-primary-500 mb-1 block">{{ $product->marca ?: 'Premium' }}</span>
                                    <h3 class="text-sm font-bold text-gray-900 dark:text-white line-clamp-1 mb-2">{{ $product->nome }}</h3>
                                    <div class="mt-auto pt-4 flex items-center justify-between border-t border-gray-50">
                                        <p class="text-lg font-black text-gray-900 dark:text-white">R$ {{ number_format($product->preco, 2, ',', '.') }}</p>
                                        <button class="bg-primary-600 text-white p-2 rounded-lg shadow-md hover:scale-110 transition-transform">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="mt-12">
                        {{ $products->links() }}
                    </div>
                @else
                    <div class="text-center py-20 bg-white dark:bg-gray-800 rounded-3xl border-2 border-dashed border-gray-100 dark:border-gray-700">
                        <svg class="mx-auto h-12 w-12 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                        <h3 class="mt-2 text-sm font-bold text-gray-900 dark:text-white uppercase tracking-widest">Nenhum produto</h3>
                        <p class="mt-1 text-sm text-gray-500">Esta loja ainda não cadastrou produtos.</p>
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-products::layouts.master>
