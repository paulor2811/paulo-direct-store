<x-store-layout>
<div class="container mx-auto px-4 py-8">
    <h1 class="text-2xl font-bold mb-6">Seu Carrinho</h1>

    @if(count($items) > 0)
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="md:col-span-2 space-y-4">
                @foreach($items as $item)
                    <div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow flex gap-4 items-center">
                        <div class="w-20 h-20 flex-shrink-0">
                            @if($item->product->media_files?->isNotEmpty())
                                <img src="{{ $item->product->media_files->first()->url }}" alt="{{ $item->product->nome }}" class="w-full h-full object-contain rounded">
                            @else
                                <div class="w-full h-full bg-gray-200 flex items-center justify-center rounded">
                                    <span class="text-gray-400 text-xs">Sem foto</span>
                                </div>
                            @endif
                        </div>
                        
                        <div class="flex-grow">
                            <h3 class="font-medium text-lg">{{ $item->product->nome }}</h3>
                            <p class="text-gray-600 dark:text-gray-400">R$ {{ number_format($item->product->preco, 2, ',', '.') }}</p>
                        </div>

                        <div class="flex items-center gap-2">
                             <form action="{{ route('cart.update', $item->product->id) }}" method="POST" class="flex items-center">
                                @csrf
                                <input type="number" name="quantity" value="{{ $item->quantity }}" min="1" class="w-16 border rounded text-center" onchange="this.form.submit()">
                            </form>
                        </div>

                        <div class="text-right min-w-[100px]">
                            <p class="font-bold">R$ {{ number_format($item->subtotal, 2, ',', '.') }}</p>
                            <form action="{{ route('cart.remove', $item->product->id) }}" method="POST" class="mt-1">
                                @csrf
                                <button type="submit" class="text-red-500 text-sm hover:underline">Remover</button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="md:col-span-1">
                <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow sticky top-4">
                    <h2 class="text-xl font-bold mb-4">Resumo do Pedido</h2>
                    
                    <div class="flex justify-between mb-2">
                        <span>Subtotal</span>
                        <span>R$ {{ number_format($total, 2, ',', '.') }}</span>
                    </div>
                    
                    <div class="border-t my-4 pt-4 flex justify-between font-bold text-lg">
                        <span>Total</span>
                        <span>R$ {{ number_format($total, 2, ',', '.') }}</span>
                    </div>
                    
                    @php
                        $msg = "Olá! Gostaria de finalizar o seguinte pedido:\n\n";
                        foreach($items as $item) {
                            $msg .= "{$item->quantity}x {$item->product->nome} - R$ " . number_format($item->subtotal, 2, ',', '.') . "\n";
                        }
                        $msg .= "\nTotal: R$ " . number_format($total, 2, ',', '.') . "\n";
                        $msg .= "\nAguardo confirmação!";
                        $whatsappLink = "https://wa.me/5511999999999?text=" . urlencode($msg);
                    @endphp

                    <a href="{{ $whatsappLink }}" target="_blank" class="block w-full bg-green-500 hover:bg-green-600 text-white text-center font-bold py-3 rounded-lg transition">
                        <i class="fa-brands fa-whatsapp mr-2"></i> Finalizar no WhatsApp
                    </a>
                    
                    <div class="mt-4 text-center">
                        <a href="{{ route('products.index') }}" class="text-primary-600 hover:underline">Continuar comprando</a>
                    </div>
                </div>
            </div>
        </div>
    @else
        <div class="text-center py-12 bg-white dark:bg-gray-800 rounded-lg shadow">
            <svg class="w-16 h-16 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
            </svg>
            <h3 class="text-xl font-medium text-gray-900 dark:text-gray-100">Seu carrinho está vazio</h3>
            <p class="text-gray-500 mt-2 mb-6">Que tal adicionar alguns produtos?</p>
            <a href="{{ route('products.index') }}" class="inline-block bg-primary-600 hover:bg-primary-700 text-white font-bold py-2 px-6 rounded-lg transition">
                Ver Produtos
            </a>
        </div>
    @endif
</div>
</x-store-layout>
