<x-admin-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Gerenciamento de Usuários
        </h2>
    </x-slot>

    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 text-gray-900">
            <!-- Search and Filter -->
            <div class="mb-6 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <form action="{{ route('admin.users.index') }}" method="GET" class="flex-1 flex gap-2">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Buscar por nome, email ou CPF..." class="flex-1 rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                    <button type="submit" class="bg-primary-600 text-white px-4 py-2 rounded-md hover:bg-primary-700 transition">
                        Buscar
                    </button>
                    @if(request('search'))
                        <a href="{{ route('admin.users.index') }}" class="bg-gray-200 text-gray-700 px-4 py-2 rounded-md hover:bg-gray-300 transition">
                            Limpar
                        </a>
                    @endif
                </form>
            </div>

            @if(session('success'))
                <div class="mb-4 p-4 text-sm text-green-800 rounded-lg bg-green-50" role="alert">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="mb-4 p-4 text-sm text-red-800 rounded-lg bg-red-50" role="alert">
                    {{ session('error') }}
                </div>
            @endif

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Usuário</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email/CPF</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ações</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($users as $user)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        @if($user->profile_photo_url)
                                            <img src="{{ $user->profile_photo_url }}" class="h-10 w-10 rounded-full object-cover mr-3">
                                        @else
                                            <div class="h-10 w-10 rounded-full bg-gray-200 flex items-center justify-center mr-3 text-gray-500">
                                                {{ substr($user->name, 0, 1) }}
                                            </div>
                                        @endif
                                        <div>
                                            <div class="text-sm font-medium text-gray-900">{{ $user->name }}</div>
                                            <div class="text-xs text-gray-500">Membro desde {{ $user->created_at->format('d/m/Y') }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $user->email }}</div>
                                    <div class="text-xs text-gray-500">{{ $user->cpf ?? 'Sem CPF' }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($user->is_banned)
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                            Banido
                                        </span>
                                    @else
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                            Ativo
                                        </span>
                                    @endif
                                    @if($user->is_admin)
                                        <span class="ml-1 px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                            Admin
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    @if($user->id !== auth()->id())
                                        <div class="flex flex-col gap-2">
                                            <form action="{{ route('admin.users.toggle-ban', $user->id) }}" method="POST" class="inline">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="{{ $user->is_banned ? 'text-green-600 hover:text-green-900' : 'text-red-600 hover:text-red-900' }} transition-colors" onclick="return confirm('Tem certeza que deseja {{ $user->is_banned ? 'desbanir' : 'banir' }} este usuário?')">
                                                    {{ $user->is_banned ? 'Desbanir' : 'Banir' }}
                                                </button>
                                            </form>

                                            <div class="border-t pt-2 mt-1">
                                                <form action="{{ route('admin.users.silence', $user->id) }}" method="POST" class="flex items-center gap-1">
                                                    @csrf
                                                    @method('PATCH')
                                                    <input type="number" name="hours" placeholder="Hrs" class="w-16 rounded text-xs border-gray-300" min="0">
                                                    <button type="submit" class="text-xs bg-orange-100 text-orange-700 px-2 py-1 rounded hover:bg-orange-200">
                                                        {{ $user->is_silenced() ? 'Atualizar' : 'Silenciar' }}
                                                    </button>
                                                </form>
                                                @if($user->is_silenced())
                                                    <p class="text-[10px] text-orange-600 mt-1">Até: {{ $user->silenced_until->format('d/m H:i') }}</p>
                                                @endif
                                            </div>
                                        </div>
                                    @else
                                        <span class="text-gray-400 cursor-not-allowed">Sua Conta</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $users->links() }}
            </div>
        </div>
    </div>
</x-admin-layout>
