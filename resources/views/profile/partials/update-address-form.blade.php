<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Address Information') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __("Update your address information.") }}
        </p>
    </header>

    <form method="post" action="{{ route('profile.address.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('patch')

        <div>
            <x-input-label for="zip_code" :value="__('Zip Code')" />
            <x-text-input id="zip_code" name="zip_code" type="text" class="mt-1 block w-full" :value="old('zip_code', $address->zip_code ?? '')" required autofocus autocomplete="postal-code" />
            <x-input-error class="mt-2" :messages="$errors->get('zip_code')" />
        </div>

        <div>
            <x-input-label for="street" :value="__('Street')" />
            <x-text-input id="street" name="street" type="text" class="mt-1 block w-full" :value="old('street', $address->street ?? '')" required autocomplete="street-address" />
            <x-input-error class="mt-2" :messages="$errors->get('street')" />
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <x-input-label for="number" :value="__('Number')" />
                <x-text-input id="number" name="number" type="text" class="mt-1 block w-full" :value="old('number', $address->number ?? '')" required />
                <x-input-error class="mt-2" :messages="$errors->get('number')" />
            </div>

            <div>
                <x-input-label for="complement" :value="__('Complement')" />
                <x-text-input id="complement" name="complement" type="text" class="mt-1 block w-full" :value="old('complement', $address->complement ?? '')" />
                <x-input-error class="mt-2" :messages="$errors->get('complement')" />
            </div>
        </div>

        <div>
            <x-input-label for="neighborhood" :value="__('Neighborhood')" />
            <x-text-input id="neighborhood" name="neighborhood" type="text" class="mt-1 block w-full" :value="old('neighborhood', $address->neighborhood ?? '')" required />
            <x-input-error class="mt-2" :messages="$errors->get('neighborhood')" />
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <x-input-label for="city" :value="__('City')" />
                <x-text-input id="city" name="city" type="text" class="mt-1 block w-full" :value="old('city', $address->city ?? '')" required />
                <x-input-error class="mt-2" :messages="$errors->get('city')" />
            </div>

            <div>
                <x-input-label for="state" :value="__('State')" />
                <x-text-input id="state" name="state" type="text" class="mt-1 block w-full" :value="old('state', $address->state ?? '')" required maxlength="2" />
                <x-input-error class="mt-2" :messages="$errors->get('state')" />
            </div>
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Save') }}</x-primary-button>

            @if (session('status') === 'address-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-gray-600"
                >{{ __('Saved.') }}</p>
            @endif

            <span id="cep-loading" class="hidden text-sm text-primary-600 animate-pulse">
                {{ __('Buscando CEP...') }}
            </span>
        </div>
    </form>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const zipInput = document.getElementById('zip_code');
            const streetInput = document.getElementById('street');
            const neighborhoodInput = document.getElementById('neighborhood');
            const cityInput = document.getElementById('city');
            const stateInput = document.getElementById('state');
            const loadingIndicator = document.getElementById('cep-loading');

            const handleCepInput = async () => {
                const cep = zipInput.value.replace(/\D/g, '');
                
                if (cep.length === 8) {
                    loadingIndicator.classList.remove('hidden');
                    console.log('Buscando endereço para o CEP:', cep);
                    
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
                        console.log('Dados recebidos:', data);

                        if (response.ok) {
                            if (data.street) streetInput.value = data.street;
                            if (data.neighborhood) neighborhoodInput.value = data.neighborhood;
                            if (data.city) cityInput.value = data.city;
                            if (data.state) stateInput.value = data.state;
                            
                            // Foca no número após preencher
                            const numberInput = document.getElementById('number');
                            if (numberInput) numberInput.focus();
                        }
                    } catch (error) {
                        console.error('Erro ao buscar CEP:', error);
                    } finally {
                        loadingIndicator.classList.add('hidden');
                    }
                }
            };

            zipInput.addEventListener('input', handleCepInput);
            zipInput.addEventListener('change', handleCepInput);
        });
    </script>
</section>
