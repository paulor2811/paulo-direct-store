<x-products::layouts.master>
    <div class="min-h-screen bg-gray-50 dark:bg-gray-900 py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- Profile Header Card -->
            <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-xl overflow-hidden mb-8 border border-gray-100 dark:border-gray-700">
                <div class="h-32 bg-gradient-to-r from-primary-600 to-indigo-600"></div>
                <div class="px-8 pb-8">
                    <div class="relative flex flex-col sm:flex-row items-center sm:items-end -mt-16 sm:space-x-8">
                        <!-- Profile Photo -->
                        <div class="relative">
                            @if($user->profile_photo_url)
                                <img src="{{ $user->profile_photo_url }}" class="h-32 w-32 rounded-3xl border-4 border-white dark:border-gray-800 shadow-2xl object-cover">
                            @else
                                <div class="h-32 w-32 rounded-3xl border-4 border-white dark:border-gray-800 shadow-2xl bg-gray-200 dark:bg-gray-700 flex items-center justify-center text-gray-400">
                                    <svg class="w-16 h-16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                </div>
                            @endif
                            <div class="absolute -bottom-2 -right-2 bg-emerald-500 w-6 h-6 rounded-full border-4 border-white dark:border-gray-800" title="Online"></div>
                        </div>

                        <!-- User Info Labels -->
                        <div class="mt-6 sm:mt-0 flex-1 text-center sm:text-left">
                            <h1 class="text-3xl font-black text-gray-900 dark:text-white tracking-tight">{{ $user->name }}</h1>
                            <p class="text-primary-500 font-bold tracking-wider uppercase text-xs mt-1">@<span>{{ $user->username }}</span></p>
                            
                            <div class="mt-4 flex flex-wrap justify-center sm:justify-start gap-4">
                                <div class="flex items-center text-gray-500 dark:text-gray-400 text-sm">
                                    <svg class="w-4 h-4 mr-1.5 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                    Membro desde {{ $stats['joined_at'] }}
                                </div>
                                <div class="flex items-center text-gray-500 dark:text-gray-400 text-sm">
                                    <svg class="w-4 h-4 mr-1.5 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                                    {{ $stats['stores_count'] }} {{ Str::plural('Loja', $stats['stores_count']) }}
                                </div>
                            </div>
                        </div>

                        <!-- Action/Contact Button -->
                        <div class="mt-8 sm:mt-0">
                            <button class="bg-gray-900 border border-gray-800 text-white px-8 py-3 rounded-2xl font-bold hover:bg-black transition-all shadow-lg active:scale-95 text-sm uppercase tracking-widest">
                                Contatar
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Stats Bar -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-12">
                <div class="bg-white dark:bg-gray-800 p-6 rounded-3xl border border-gray-100 dark:border-gray-700 shadow-sm flex items-center space-x-4">
                    <div class="bg-primary-50 dark:bg-primary-900/30 p-4 rounded-2xl">
                        <svg class="w-8 h-8 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                    </div>
                    <div>
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Anúncios Ativos</p>
                        <p class="text-3xl font-black text-gray-900 dark:text-white">{{ $stats['total_ads'] }}</p>
                    </div>
                </div>
                <!-- Add more stat blocks as needed -->
            </div>

            <!-- User Ads Section -->
            <div class="space-y-8">
                <div class="flex items-center justify-between">
                    <h2 class="text-2xl font-black text-gray-900 dark:text-white tracking-tight">Vitrine do Usuário</h2>
                    <div class="flex bg-white dark:bg-gray-800 p-1 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700">
                        <button class="px-4 py-1.5 text-xs font-bold text-white bg-primary-600 rounded-lg shadow-md">Todos</button>
                        <button class="px-4 py-1.5 text-xs font-bold text-gray-500 hover:text-gray-900 dark:hover:text-white">Destaques</button>
                    </div>
                </div>

                @if($products->count() > 0)
                    <!-- Recycle the existing product grid partial if available, otherwise implementation here -->
                    <div class="grid grid-cols-2 gap-4 sm:gap-6 lg:gap-8 lg:grid-cols-3 xl:grid-cols-4">
                        @foreach($products as $product)
                            {{-- Reuse the same UI logic as Modules/Products/resources/views/index.blade.php if possible --}}
                            {{-- For now, a simplified version or similar structure --}}
                            <div x-data="{ activeSlide: 0, slides: {{ $product->images() ? $product->images()->count() : 0 }} }" 
                                 class="group relative bg-white border border-gray-100/80 rounded-[1.5rem] shadow-sm dark:bg-gray-800 dark:border-gray-700 hover:shadow-2xl hover:-translate-y-1 transition-all duration-500 flex flex-col h-full overflow-hidden">
                                
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
                        <h3 class="mt-2 text-sm font-bold text-gray-900 dark:text-white uppercase tracking-widest">Nenhum anúncio</h3>
                        <p class="mt-1 text-sm text-gray-500">Este usuário ainda não postou nada.</p>
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-products::layouts.master>
