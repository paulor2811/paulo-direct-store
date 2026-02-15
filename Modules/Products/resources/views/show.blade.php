<x-store-layout>
    <section class="py-8 bg-white md:py-16 dark:bg-gray-900 antialiased">
    <div class="max-w-screen-xl px-4 mx-auto 2xl:px-0">
      <!-- Breadcrumb -->
      <nav class="flex mb-8 justify-between" aria-label="Breadcrumb">
        <ol class="inline-flex items-center space-x-1 md:space-x-2 rtl:space-x-reverse">
          <li class="inline-flex items-center">
            <a href="{{ route('products.index') }}" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-primary-600 dark:text-gray-400 dark:hover:text-white">
              <svg class="w-3 h-3 me-2.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                <path d="m19.707 9.293-2-2-7-7a1 1 0 0 0-1.414 0l-7 7-2 2a1 1 0 0 0 1.414 1.414L2 10.414V18a2 2 0 0 0 2 2h3a1 1 0 0 0 1-1v-4a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v4a1 1 0 0 0 1 1h3a2 2 0 0 0 2-2v-7.586l.293.293a1 1 0 0 0 1.414-1.414Z"/>
              </svg>
              Vitrine
            </a>
          </li>
          @if($product->categoria)
          <li class="inline-flex items-center">
             <svg class="rtl:rotate-180 w-3 h-3 text-gray-400 mx-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/>
              </svg>
              <a href="{{ route('products.index', ['category' => $product->categoria->id]) }}" class="ms-1 text-sm font-medium text-gray-700 hover:text-primary-600 md:ms-2 dark:text-gray-400 dark:hover:text-white">
                  {{ $product->categoria->nome }}
              </a>
          </li>
          @endif
          <li aria-current="page">
            <div class="flex items-center">
              <svg class="rtl:rotate-180 w-3 h-3 text-gray-400 mx-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/>
              </svg>
              <span class="ms-1 text-sm font-medium text-gray-500 md:ms-2 dark:text-gray-400">{{ $product->nome }}</span>
            </div>
          </li>
        </ol>

        @if(auth()->check() && auth()->user()->is_admin)
            <a href="{{ route('admin.products.edit', $product->id) }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                Editar Anúncio
            </a>
        @endif
      </nav>

      <div class="lg:grid lg:grid-cols-2 lg:gap-8 xl:gap-16">
        <div class="shrink-0 w-full max-w-md lg:max-w-lg mx-auto">
            @if($product->images()->isNotEmpty())
                <div class="space-y-4">
                    <!-- Main Image Container -->
                    <div class="relative overflow-hidden rounded-lg bg-gray-100 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 group">
                        <!-- Main Image -->
                        <img id="main-image" class="w-auto h-[400px] mx-auto object-contain transition-transform duration-300" src="{{ $product->main_image_url ?? asset('images/placeholder.png') }}" alt="{{ $product->nome }}" />
                        
                        <!-- Prev Button -->
                        <button onclick="changeImage(-1)" class="absolute top-1/2 left-2 -translate-y-1/2 bg-white/80 dark:bg-gray-800/80 hover:bg-white dark:hover:bg-gray-800 text-gray-800 dark:text-white p-2 rounded-full shadow-md opacity-0 group-hover:opacity-100 transition-opacity focus:outline-none">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                        </button>
                        
                        <!-- Next Button -->
                        <button onclick="changeImage(1)" class="absolute top-1/2 right-2 -translate-y-1/2 bg-white/80 dark:bg-gray-800/80 hover:bg-white dark:hover:bg-gray-800 text-gray-800 dark:text-white p-2 rounded-full shadow-md opacity-0 group-hover:opacity-100 transition-opacity focus:outline-none">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                        </button>
                    </div>

                    <!-- Thumbnails -->
                    @if($product->images()->count() > 1)
                    <div class="grid grid-cols-4 gap-4">
                        @foreach($product->images() as $index => $foto)
                        <button onclick="selectImage({{ $index }})" 
                                class="thumbnail-btn border-2 {{ $index === 0 ? 'border-primary-600 ring-2 ring-primary-500' : 'border-transparent' }} hover:border-primary-600 rounded-lg overflow-hidden bg-gray-50 dark:bg-gray-800 h-20 flex items-center justify-center focus:outline-none transition-all"
                                data-index="{{ $index }}">
                            <img class="w-full h-full object-contain" src="{{ $foto->url }}" alt="Thumbnail">
                        </button>
                        @endforeach
                    </div>
                    @endif
                </div>

                <!-- Gallery Script -->
                <script>
                    const productImages = [
                        @foreach($product->images() as $foto)
                            "{{ $foto->url }}",
                        @endforeach
                    ];
                    let currentImageIndex = 0;

                    function updateGallery() {
                        const mainImage = document.getElementById('main-image');
                        const thumbnails = document.querySelectorAll('.thumbnail-btn');

                        // Update Main Image with fade effect
                        mainImage.style.opacity = '0';
                        setTimeout(() => {
                            mainImage.src = productImages[currentImageIndex];
                            mainImage.style.opacity = '1';
                        }, 150);

                        // Update Thumbnails style
                        thumbnails.forEach((thumb, index) => {
                            if (index === currentImageIndex) {
                                thumb.classList.add('border-primary-600', 'ring-2', 'ring-primary-500');
                                thumb.classList.remove('border-transparent');
                            } else {
                                thumb.classList.remove('border-primary-600', 'ring-2', 'ring-primary-500');
                                thumb.classList.add('border-transparent');
                            }
                        });
                    }

                    function changeImage(step) {
                        currentImageIndex += step;
                        if (currentImageIndex < 0) currentImageIndex = productImages.length - 1;
                        if (currentImageIndex >= productImages.length) currentImageIndex = 0;
                        updateGallery();
                    }

                    function selectImage(index) {
                        currentImageIndex = index;
                        updateGallery();
                    }
                </script>
            @else
                <div class="w-full h-[400px] bg-gray-100 dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 flex flex-col items-center justify-center text-gray-400">
                    <svg class="w-16 h-16 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    <span class="text-sm font-medium">Sem imagem disponível</span>
                </div>
            @endif
        </div>

        <div class="mt-6 sm:mt-8 lg:mt-0">
          <h1
            class="text-xl font-semibold text-gray-900 sm:text-2xl dark:text-white"
          >
            {{ $product->nome }}
          </h1>
          <div class="mt-4 sm:items-center sm:gap-4 sm:flex">
            <p
              class="text-2xl font-extrabold text-gray-900 sm:text-3xl dark:text-white"
            >
              R$ {{ number_format($product->preco, 2, ',', '.') }}
            </p>

            <div class="flex items-center gap-2 mt-2 sm:mt-0">
               <!-- Stars/Reviews section kept static as placeholder since we don't have reviews logic yet -->
               <div class="flex items-center gap-1">
                   @for($i=0; $i<5; $i++)
                    <svg class="w-4 h-4 text-yellow-300" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                      <path d="M13.849 4.22c-.684-1.626-3.014-1.626-3.698 0L8.397 8.387l-4.552.361c-1.775.14-2.495 2.331-1.142 3.477l3.468 2.937-1.06 4.392c-.413 1.713 1.472 3.067 2.992 2.149L12 19.35l3.897 2.354c1.52.918 3.405-.436 2.992-2.15l-1.06-4.39 3.468-2.938c1.353-1.146.633-3.336-1.142-3.477l-4.552-.36-1.754-4.17Z"/>
                    </svg>
                   @endfor
               </div>
               <p class="text-sm font-medium leading-none text-gray-500 dark:text-gray-400">(5.0)</p>
               <a href="#" class="text-sm font-medium leading-none text-gray-900 underline hover:no-underline dark:text-white">345 Reviews</a>
            </div>
          </div>

          <div class="mt-6 sm:gap-4 sm:items-center sm:flex sm:mt-8">
            @php
                $whatsappMessage = 'Olá, tenho interesse no produto: ' . $product->nome . "\n" . 'ID: ' . $product->id;
                if ($product->main_image_url) {
                     $whatsappMessage .= "\n" . 'Foto: ' . $product->main_image_url;
                } else {
                     $whatsappMessage .= "\n(Sem foto disponível)";
                }
            @endphp
            <a
              href="https://wa.me/5543999628432?text={{ urlencode($whatsappMessage) }}"
              target="_blank"
              title="Conversar no WhatsApp"
              class="text-white mt-4 sm:mt-0 bg-[#25D366] hover:bg-[#20bd5a] focus:ring-4 focus:ring-[#25D366]/50 font-medium rounded-lg text-sm px-5 py-2.5 flex items-center justify-center w-full sm:w-auto transition-colors duration-200"
              role="button"
            >
              <svg class="w-6 h-6 -ms-2 me-2" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24">
                <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/>
              </svg>
              Tenho Interesse via WhatsApp
            </a>
          </div>

          <!-- How to Buy Instructions -->
          <div class="mt-8 p-4 bg-gray-50 dark:bg-gray-800 rounded-lg border border-gray-100 dark:border-gray-700">
              <h3 class="font-semibold text-lg mb-3 text-gray-900 dark:text-white">Como Comprar:</h3>
              <ul class="space-y-3 text-gray-600 dark:text-gray-400 text-sm">
                  <li class="flex items-start gap-3">
                      <div class="mt-0.5 w-6 h-6 flex items-center justify-center rounded-full bg-primary-100 dark:bg-primary-900 text-primary-600 dark:text-primary-300 font-bold text-xs">1</div>
                      <span>Clique no botão <strong>"Tenho Interesse"</strong> acima.</span>
                  </li>
                  <li class="flex items-start gap-3">
                      <div class="mt-0.5 w-6 h-6 flex items-center justify-center rounded-full bg-primary-100 dark:bg-primary-900 text-primary-600 dark:text-primary-300 font-bold text-xs">2</div>
                      <span>Nossa equipe receberá sua mensagem no WhatsApp com a foto e detalhes do produto.</span>
                  </li>
                  <li class="flex items-start gap-3">
                      <div class="mt-0.5 w-6 h-6 flex items-center justify-center rounded-full bg-primary-100 dark:bg-primary-900 text-primary-600 dark:text-primary-300 font-bold text-xs">3</div>
                      <span>Nós combinamos a forma de pagamento e entrega diretamente com você!</span>
                  </li>
              </ul>
              
              <!-- Shipping Calculator -->
              <div class="mt-6 pt-6 border-t border-gray-200 dark:border-gray-700">
                  <h4 class="font-medium text-gray-900 dark:text-white mb-2">Simular Frete (Londrina e Região)</h4>
                  <div class="flex items-center gap-2">
                      <input type="text" id="cep-input" maxlength="8" placeholder="Digite seu CEP" class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" />
                      <button onclick="calculateShipping()" class="text-white bg-primary-600 hover:bg-primary-700 focus:ring-4 focus:outline-none focus:ring-primary-300 font-medium rounded-lg text-sm px-4 py-2.5 dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800">
                          Calcular
                      </button>
                  </div>
                  <div id="shipping-result" class="mt-2 text-sm hidden font-medium"></div>
              </div>
          </div>
          
          <script>
            async function calculateShipping() {
                const cep = document.getElementById('cep-input').value.replace(/\D/g, '');
                const resultDiv = document.getElementById('shipping-result');
                
                if (cep.length !== 8) {
                    resultDiv.innerHTML = '<span class="text-red-500">CEP inválido. Digite 8 números.</span>';
                    resultDiv.classList.remove('hidden');
                    return;
                }

                resultDiv.innerHTML = '<span class="text-gray-500">Calculando...</span>';
                resultDiv.classList.remove('hidden');

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

                    if (!response.ok) {
                        throw new Error(data.error || 'Erro ao calcular');
                    }

                    const priceFormatted = new Intl.NumberFormat('pt-BR', { style: 'currency', currency: 'BRL' }).format(data.price);
                    
                    resultDiv.innerHTML = `
                        <div class="flex flex-col text-gray-700 dark:text-gray-300 mt-2 p-3 bg-white dark:bg-gray-900 rounded border border-gray-200 dark:border-gray-600">
                            <span><strong>Cidade:</strong> ${data.city}/${data.state}</span>
                            <span><strong>Distância:</strong> ${data.distance_km} km</span>
                            <span class="text-green-600 dark:text-green-400 mt-1"><strong>Frete Estimado: ${priceFormatted}</strong></span>
                        </div>
                    `;
                } catch (error) {
                    console.error(error);
                    resultDiv.innerHTML = `<span class="text-red-500">${error.message}</span>`;
                }
            }
          </script>
          
          <hr class="my-6 md:my-8 border-gray-200 dark:border-gray-800" />

          @if($product->descricao)
          <p class="mb-6 text-gray-500 dark:text-gray-400">
            {{ $product->descricao }}
          </p>
          @endif

          <p class="text-gray-500 dark:text-gray-400 mb-8">
             @if($product->marca) <strong>Marca:</strong> {{ $product->marca }}<br> @endif
             @if($product->modelo) <strong>Modelo:</strong> {{ $product->modelo }}<br> @endif
             @if($product->cor) <strong>Cor:</strong> {{ $product->cor }} @endif
          </p>
        </div>
      </div>
      
      <!-- Related Products -->
      @if($relatedProducts->isNotEmpty())
      <div class="mt-16">
          <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-8">Produtos Relacionados</h2>
          <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
              @foreach($relatedProducts as $related)
              <div class="bg-white border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700">
                  <a href="{{ route('products.show', $related->id) }}">
                      @if($related->images()->isNotEmpty())
                        <img class="p-4 rounded-t-lg w-full h-48 object-contain" src="{{ $related->main_image_url ?? asset('images/placeholder.png') }}" alt="{{ $related->nome }}" />
                      @else
                        <div class="p-4 w-full h-48 bg-gray-100 flex items-center justify-center text-gray-400">Sem Imagem</div>
                      @endif
                  </a>
                  <div class="px-5 pb-5">
                      <a href="{{ route('products.show', $related->id) }}">
                          <h5 class="text-lg font-semibold tracking-tight text-gray-900 dark:text-white overflow-hidden text-ellipsis whitespace-nowrap">{{ $related->nome }}</h5>
                      </a>
                      <div class="flex items-center justify-between mt-3">
                          <span class="text-xl font-bold text-gray-900 dark:text-white">R$ {{ number_format($related->preco, 2, ',', '.') }}</span>
                          <a href="{{ route('products.show', $related->id) }}" class="text-white bg-primary-700 hover:bg-primary-800 focus:ring-4 focus:outline-none focus:ring-primary-300 font-medium rounded-lg text-sm px-4 py-2 text-center dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800">Ver</a>
                      </div>
                  </div>
              </div>
              @endforeach
          </div>
      </div>
      @endif

    </div>
  </section>
</x-store-layout>
