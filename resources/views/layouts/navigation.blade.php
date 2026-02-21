<nav x-data="{ open: false }" class='bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700 relative'>
  <div class='max-w-7xl mx-auto px-4 sm:px-6 lg:px-8'>
    <div class='flex justify-between h-16'>
        <div class='flex'>
            <!-- Logo -->
            <div class='shrink-0 flex items-center'>
                <a href='{{ route('products.index') }}' class='flex items-center gap-2 group'>
                    <div class='bg-primary-600 text-white p-1.5 rounded-lg shadow-sm group-hover:bg-primary-700 transition-colors'>
                        <svg class='w-6 h-6' fill='none' stroke='currentColor' viewBox='0 0 24 24' xmlns='http://www.w3.org/2000/svg'><path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z'></path></svg>
                    </div>
                    <div class='flex flex-col'>
                        <span class='self-center text-xl font-extrabold whitespace-nowrap tracking-wide text-gray-900 dark:text-white uppercase leading-none'>Direct<span class='text-primary-600'>Marketplace</span></span>
                        <span class='text-[0.6rem] text-gray-500 uppercase tracking-[0.2em] leading-none text-right'>Brazil</span>
                    </div>
                </a>
            </div>
        </div>

        <!-- Desktop Menu -->
        <div class='hidden sm:flex items-center'>
          <ul class='font-medium flex flex-row space-x-6 items-center'>
            <li>
              <a href='{{ route('cart.index') }}' class='text-sm font-medium text-gray-700 hover:text-primary-600 dark:text-gray-400 dark:hover:text-primary-500 transition-colors flex items-center gap-1'>
                  <svg class='w-5 h-5' fill='none' stroke='currentColor' viewBox='0 0 24 24'><path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z'></path></svg>
                  Carrinho
                  @if(session('cart') && count(session('cart')) > 0)
                    <span class='inline-flex items-center justify-center w-4 h-4 text-xs font-semibold text-white bg-red-500 rounded-full'>
                        {{ count(session('cart')) }}
                    </span>
                  @endif
              </a>
            </li>
            <li>
              <a href='{{ route('products.index') }}' class='text-sm font-medium text-gray-900 hover:text-primary-600 dark:text-white dark:hover:text-primary-500 transition-colors'>Vitrine</a>
            </li>
        @auth
            <li class="hidden sm:flex sm:items-center sm:ms-6">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button type="button" class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                            @if (Auth::user()->profile_photo_url)
                                <img src="{{ Auth::user()->profile_photo_url }}" alt="{{ Auth::user()->name }}" class="h-8 w-8 rounded-full object-cover me-2">
                            @endif
                            <div>{{ Auth::user()->name }}</div>

                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        @if(Auth::user()->is_admin)
                            <x-dropdown-link :href="route('admin.dashboard')">
                                {{ __('Painel Administrativo') }}
                            </x-dropdown-link>
                        @endif

                        <x-dropdown-link :href="route('profile.edit')">
                            {{ __('Perfil') }}
                        </x-dropdown-link>
                        <x-dropdown-link :href="route('stores.index')">
                            {{ __('Minhas Lojas') }}
                        </x-dropdown-link>
                        <x-dropdown-link :href="route('profile.account')">
                            {{ __('Minha Conta') }}
                        </x-dropdown-link>

                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf

                            <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                {{ __('Sair') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </li>
        @else
            <li class="flex items-center space-x-4">
                <a href="{{ route('login') }}" class="text-sm font-medium text-gray-500 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white transition-colors">Entrar</a>
                <a href="{{ route('register') }}" class="text-sm font-medium text-white bg-primary-600 hover:bg-primary-700 px-3 py-1.5 rounded-lg transition-colors">Criar Conta</a>
            </li>
        @endauth
        @auth
            <!-- Active Store Indicator (Desktop) -->
            @php $activeStoreId = session('active_store_id'); @endphp
            @if($activeStoreId)
                @php $activeStore = \Modules\Stores\Models\Store::find($activeStoreId); @endphp
                @if($activeStore)
                    <li class="hidden lg:flex items-center px-3 py-1 bg-indigo-50 dark:bg-indigo-900/20 rounded-xl border border-indigo-100 dark:border-indigo-800">
                        <span class="flex h-2 w-2 rounded-full bg-indigo-500 animate-pulse mr-2"></span>
                        <span class="text-[10px] font-black text-indigo-600 dark:text-indigo-400 uppercase tracking-widest">Loja: {{ $activeStore->nome }}</span>
                    </li>
                @endif
            @endif
            
            <!-- Create Ad Button (Desktop) -->
            <li class="hidden sm:block">
                <a href="{{ route('products.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-xs font-black rounded-xl text-white bg-primary-600 hover:bg-primary-700 shadow-md shadow-primary-500/20 transition-all active:scale-95 uppercase tracking-widest">
                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    Anunciar
                </a>
            </li>
        @endauth
          </ul>
        </div>

        <!-- Hamburger -->
        <div class="-me-2 flex items-center sm:hidden">
            <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                    <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
    </div>
  </div>

  <!-- Responsive Navigation Menu -->
  <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden bg-white dark:bg-gray-800 border-t border-gray-200 dark:border-gray-700">
    <div class="pt-2 pb-3 space-y-1">
        @auth
            <!-- Mobile Active Store & Create Ad -->
            <div class="px-4 py-3 mb-2 bg-gray-50 dark:bg-gray-900/50 rounded-xl mx-2 border border-gray-100 dark:border-gray-800">
                @php $activeStoreId = session('active_store_id'); @endphp
                @if($activeStoreId)
                    @php $activeStore = \Modules\Stores\Models\Store::find($activeStoreId); @endphp
                    @if($activeStore)
                        <div class="flex items-center mb-3">
                            <span class="flex h-2 w-2 rounded-full bg-indigo-500 mr-2"></span>
                            <span class="text-[10px] font-black text-indigo-600 dark:text-indigo-400 uppercase tracking-widest">Loja: {{ $activeStore->nome }}</span>
                        </div>
                    @endif
                @endif
                <a href="{{ route('products.create') }}" class="flex items-center justify-center w-full py-3 bg-primary-600 text-white rounded-xl font-black text-xs uppercase tracking-[0.2em] shadow-lg shadow-primary-500/20">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    Anunciar Agora
                </a>
            </div>
        @endauth
        <a href="{{ route('products.index') }}" class="block w-full ps-3 pe-4 py-2 border-l-4 border-transparent text-start text-base font-medium text-gray-600 hover:text-gray-800 hover:bg-gray-50 hover:border-gray-300 focus:outline-none focus:text-gray-800 focus:bg-gray-50 focus:border-gray-300 transition duration-150 ease-in-out">
            Vitrine
        </a>
        <a href="{{ route('cart.index') }}" class="block w-full ps-3 pe-4 py-2 border-l-4 border-transparent text-start text-base font-medium text-gray-600 hover:text-gray-800 hover:bg-gray-50 hover:border-gray-300 focus:outline-none focus:text-gray-800 focus:bg-gray-50 focus:border-gray-300 transition duration-150 ease-in-out">
            Carrinho
            @if(session('cart') && count(session('cart')) > 0)
                <span class="ml-2 inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-white bg-red-600 rounded-full">
                    {{ count(session('cart')) }}
                </span>
            @endif
        </a>
        @auth
            <div class="pt-4 pb-1 border-t border-gray-200 dark:border-gray-600">
                <div class="px-4">
                    <div class="font-medium text-base text-gray-800 dark:text-gray-200">{{ Auth::user()->name }}</div>
                    <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
                </div>

                <div class="mt-3 space-y-1">
                    @if(Auth::user()->is_admin)
                        <x-responsive-nav-link :href="route('admin.dashboard')">
                            {{ __('Painel Administrativo') }}
                        </x-responsive-nav-link>
                    @endif

                    <x-responsive-nav-link :href="route('profile.edit')">
                        {{ __('Perfil') }}
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('stores.index')">
                        {{ __('Minhas Lojas') }}
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('profile.account')">
                        {{ __('Minha Conta') }}
                    </x-responsive-nav-link>

                    <!-- Authentication -->
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf

                        <x-responsive-nav-link :href="route('logout')"
                                onclick="event.preventDefault();
                                            this.closest('form').submit();">
                            {{ __('Sair') }}
                        </x-responsive-nav-link>
                    </form>
                </div>
            </div>
        @else
            <a href="{{ route('login') }}" class="block w-full ps-3 pe-4 py-2 border-l-4 border-transparent text-start text-base font-medium text-gray-600 hover:text-gray-800 hover:bg-gray-50 hover:border-gray-300 focus:outline-none focus:text-gray-800 focus:bg-gray-50 focus:border-gray-300 transition duration-150 ease-in-out">
                Entrar
            </a>
            <a href="{{ route('register') }}" class="block w-full ps-3 pe-4 py-2 border-l-4 border-transparent text-start text-base font-medium text-gray-600 hover:text-gray-800 hover:bg-gray-50 hover:border-gray-300 focus:outline-none focus:text-gray-800 focus:bg-gray-50 focus:border-gray-300 transition duration-150 ease-in-out">
                Criar Conta
            </a>
        @endauth
    </div>
  </div>
</nav>
