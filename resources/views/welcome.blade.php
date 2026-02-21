<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('DirectMarketplaceBrazil') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <section class="py-8 bg-white md:py-12 dark:bg-gray-900 overflow-hidden shadow-sm sm:rounded-lg">
              <div class="max-w-screen-xl px-4 mx-auto 2xl:px-0">
                <div class="mb-4 items-end justify-between space-y-4 sm:flex sm:space-y-0 md:mb-8">
                  <div>
                    <h2 class="text-xl font-semibold text-gray-900 dark:text-white sm:text-2xl">Lançamentos DirectMarketplaceBrazil</h2>
                  </div>
                </div>

                <div class="grid gap-4 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4">
                  <div class="rounded-lg border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-gray-800">
                    <div class="h-56 w-full bg-gray-100 rounded-md flex items-center justify-center">
                        <span class="text-gray-400">Imagem do Produto</span>
                    </div>
                    <div class="pt-6">
                      <a href="#" class="text-lg font-semibold leading-tight text-gray-900 hover:underline dark:text-white">Smartphone Premium X</a>
                      <div class="mt-4 flex items-center justify-between gap-4">
                        <p class="text-2xl font-extrabold leading-tight text-gray-900 dark:text-white">R$ 2.499,00</p>
                        <button type="button" class="inline-flex items-center rounded-lg bg-primary-700 px-5 py-2.5 text-sm font-medium text-white hover:bg-primary-800 focus:outline-none focus:ring-4 focus:ring-primary-300 dark:bg-primary-600 dark:hover:bg-primary-700">
                          Comprar
                        </button>
                      </div>
                    </div>
                  </div>
                  </div>
              </div>
            </section>
        </div>
    </div>
</x-app-layout>