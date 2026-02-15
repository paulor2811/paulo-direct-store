<x-admin-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 text-gray-900">
            {{ __("Bem-vindo ao Painel Administrativo!") }}
            <br>
            Use o menu lateral para gerenciar seus anúncios.
        </div>
    </div>
</x-admin-layout>
