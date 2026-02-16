{{-- Reviews Section --}}
<div id="reviews-section" class="max-w-screen-xl px-4 py-8 mx-auto lg:py-16 lg:px-6">
    <div class="max-w-screen-md">
        <h2 class="mb-4 text-4xl tracking-tight font-extrabold text-gray-900 dark:text-white">Avaliações</h2>
        
        @if(session('success'))
            <div class="p-4 mb-4 text-sm text-green-800 rounded-lg bg-green-50" role="alert">
                {{ session('success') }}
            </div>
        @endif
        
        @if(session('error'))
            <div class="p-4 mb-4 text-sm text-red-800 rounded-lg bg-red-50" role="alert">
                {{ session('error') }}
            </div>
        @endif

        @auth
            {{-- Review Form --}}
            @php
                $userReview = $product->reviews()->where('user_id', auth()->id())->first();
            @endphp
            
            @if(!$userReview)
                <div class="mb-8 p-6 bg-white border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700">
                    <h3 class="mb-4 text-xl font-semibold text-gray-900 dark:text-white">Avaliar este produto</h3>
                    <form action="{{ route('products.reviews.store', $product->id) }}" method="POST">
                        @csrf
                        
                        {{-- Star Rating Input --}}
                        <div class="mb-4">
                            <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Sua avaliação</label>
                            <div class="flex items-center gap-2">
                                <div class="flex" id="star-rating">
                                    @for($i = 1; $i <= 5; $i++)
                                        <svg data-rating="{{ $i }}" class="star-input w-8 h-8 cursor-pointer text-gray-300 hover:text-yellow-300 transition" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M13.849 4.22c-.684-1.626-3.014-1.626-3.698 0L8.397 8.387l-4.552.361c-1.775.14-2.495 2.331-1.142 3.477l3.468 2.937-1.06 4.392c-.413 1.713 1.472 3.067 2.992 2.149L12 19.35l3.897 2.354c1.52.918 3.405-.436 2.992-2.15l-1.06-4.39 3.468-2.938c1.353-1.146.633-3.336-1.142-3.477l-4.552-.36-1.754-4.17Z"/>
                                        </svg>
                                    @endfor
                                </div>
                                <span id="rating-text" class="text-sm text-gray-600 dark:text-gray-400"></span>
                            </div>
                            <input type="hidden" name="rating" id="rating-value" required>
                            @error('rating')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Comment --}}
                        <div class="mb-4">
                            <label for="comment" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Comentário (opcional)</label>
                            <textarea id="comment" name="comment" rows="4" class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white" placeholder="Conte sua experiência...">{{ old('comment') }}</textarea>
                            @error('comment')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <button type="submit" class="text-white bg-primary-700 hover:bg-primary-800 focus:ring-4 focus:outline-none focus:ring-primary-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800">
                            Enviar Avaliação
                        </button>
                    </form>
                </div>
            @endif
        @else
            <div class="mb-8 p-4 text-sm text-gray-800 rounded-lg bg-gray-50" role="alert">
                <a href="{{ route('login') }}" class="font-medium text-primary-600 hover:underline">Faça login</a> para avaliar este produto.
            </div>
        @endauth

        {{-- Reviews List --}}
        <div class="space-y-4">
            @forelse($product->reviews()->with('user')->latest()->get() as $review)
                <article class="p-6 bg-white border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700">
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center space-x-4">
                            <div>
                                @if(auth()->check() && auth()->user()->is_admin)
                                    <button onclick="openModerationModal({{ $review->user_id }}, '{{ addslashes($review->user->name) }}', {{ $review->user->is_banned ? 'true' : 'false' }}, '{{ $review->user->silenced_until ? $review->user->silenced_until->format('Y-m-d H:i') : '' }}')" class="text-lg font-semibold text-primary-600 hover:underline dark:text-primary-400">
                                        {{ $review->user->name }}
                                    </button>
                                @else
                                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">{{ $review->user->name }}</h3>
                                @endif
                                <div class="flex items-center gap-2">
                                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ $review->created_at->format('d/m/Y') }}</p>
                                    @if($review->user->is_silenced())
                                        <span class="bg-gray-100 text-gray-800 text-[10px] font-medium px-2 py-0.5 rounded dark:bg-gray-700 dark:text-gray-300">SILENCIADO</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        
                        {{-- Admin or User's own review actions --}}
                        @if(auth()->check() && (auth()->id() === $review->user_id || auth()->user()->is_admin))
                            <div class="flex items-center gap-4">
                                @if(auth()->user()->is_admin)
                                    <form action="{{ route('reviews.toggle-visibility', $review->id) }}" method="POST" class="m-0 p-0 inline-flex items-center">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="text-sm font-medium {{ $review->is_visible ? 'text-orange-600 hover:text-orange-700' : 'text-green-600 hover:text-green-700' }} hover:underline transition-colors leading-tight">
                                            {{ $review->is_visible ? 'Ocultar' : 'Mostrar' }}
                                        </button>
                                    </form>
                                @endif

                                @if(auth()->id() === $review->user_id)
                                    <button onclick="editReview({{ $review->id }}, {{ $review->rating }}, '{{ addslashes($review->comment) }}')" class="text-sm font-medium text-primary-600 hover:text-primary-700 hover:underline transition-colors leading-tight">
                                        Editar
                                    </button>
                                @endif
                                
                                <form action="{{ route('reviews.destroy', $review->id) }}" method="POST" class="m-0 p-0 inline-flex items-center" onsubmit="return confirm('Tem certeza que deseja excluir esta avaliação?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-sm font-medium text-red-600 hover:text-red-700 hover:underline transition-colors leading-tight">
                                        Excluir
                                    </button>
                                </form>
                            </div>
                        @endif
                    </div>

                    {{-- Rating Stars & Status --}}
                    <div class="flex items-center gap-2 mb-2">
                        <div class="flex items-center">
                            @for($i = 1; $i <= 5; $i++)
                                <svg class="w-4 h-4 {{ $i <= $review->rating ? 'text-yellow-300' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M13.849 4.22c-.684-1.626-3.014-1.626-3.698 0L8.397 8.387l-4.552.361c-1.775.14-2.495 2.331-1.142 3.477l3.468 2.937-1.06 4.392c-.413 1.713 1.472 3.067 2.992 2.149L12 19.35l3.897 2.354c1.52.918 3.405-.436 2.992-2.15l-1.06-4.39 3.468-2.938c1.353-1.146.633-3.336-1.142-3.477l-4.552-.36-1.754-4.17Z"/>
                                </svg>
                            @endfor
                        </div>
                        @if(!$review->is_visible && auth()->check() && auth()->user()->is_admin)
                            <span class="bg-orange-100 text-orange-800 text-[10px] font-medium px-2 py-0.5 rounded dark:bg-orange-900 dark:text-orange-300">OCULTO</span>
                        @endif
                    </div>

                    {{-- Comment --}}
                    @if($review->comment)
                        <p class="text-gray-700 dark:text-gray-400">{{ $review->comment }}</p>
                    @endif
                </article>
            @empty
                <p class="text-gray-500 dark:text-gray-400">Nenhuma avaliação ainda. Seja o primeiro a avaliar!</p>
            @endforelse
        </div>
    </div>
</div>

@if(auth()->check() && auth()->user()->is_admin)
    {{-- Moderation Modal --}}
    <div id="moderationModal" class="hidden fixed inset-0 bg-gray-900 bg-opacity-50 flex items-center justify-center z-[60]">
        <div class="bg-white rounded-lg shadow-xl p-6 max-w-md w-full dark:bg-gray-800">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-xl font-bold text-gray-900 dark:text-white">Moderação: <span id="mod-user-name"></span></h3>
                <button onclick="closeModerationModal()" class="text-gray-400 hover:text-gray-600">&times;</button>
            </div>

            <div class="space-y-6">
                {{-- Ban/Unban section --}}
                <div class="p-4 bg-red-50 rounded-lg dark:bg-red-900/20">
                    <h4 class="text-sm font-bold text-red-800 dark:text-red-400 uppercase mb-2">Banimento</h4>
                    <p class="text-xs text-red-600 dark:text-red-300 mb-3">Usuários banidos são desconectados e perdem acesso à conta.</p>
                    <form id="mod-ban-form" method="POST">
                        @csrf
                        @method('PATCH')
                        <button type="submit" id="mod-ban-btn" class="w-full py-2 px-4 rounded font-medium text-white transition-colors">
                            Banir Usuário
                        </button>
                    </form>
                </div>

                {{-- Silence section --}}
                <div class="p-4 bg-orange-50 rounded-lg dark:bg-orange-900/20">
                    <h4 class="text-sm font-bold text-orange-800 dark:text-orange-400 uppercase mb-2">Silenciamento</h4>
                    <p class="text-xs text-orange-600 dark:text-orange-300 mb-3">O usuário não poderá comentar pelo tempo determinado.</p>
                    
                    <div id="current-silence-info" class="hidden mb-3 p-2 bg-orange-100 rounded text-xs text-orange-800">
                        Atualmente silenciado até: <span id="silence-expiry" class="font-bold"></span>
                    </div>

                    <form id="mod-silence-form" method="POST" class="space-y-3">
                        @csrf
                        @method('PATCH')
                        <div>
                            <label class="block text-xs font-medium text-orange-700 dark:text-orange-400 mb-1">Duração (em horas - 0 para remover)</label>
                            <div class="flex gap-2">
                                <input type="number" name="hours" value="1" min="0" class="flex-1 rounded border-orange-200 text-sm focus:ring-orange-500">
                                <button type="submit" class="bg-orange-600 text-white px-4 py-2 rounded text-sm font-medium hover:bg-orange-700 transition">
                                    Aplicar
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <button onclick="closeModerationModal()" class="mt-6 w-full py-2 text-sm text-gray-500 hover:text-gray-700 font-medium">Cancelar</button>
        </div>
    </div>
@endif

{{-- Edit Review Modal --}}
<div id="editReviewModal" class="hidden fixed inset-0 bg-gray-900 bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white rounded-lg shadow p-6 max-w-md w-full dark:bg-gray-800">
        <h3 class="mb-4 text-xl font-semibold text-gray-900 dark:text-white">Editar Avaliação</h3>
        <form id="editReviewForm" method="POST">
            @csrf
            @method('PUT')
            
            <div class="mb-4">
                <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Sua avaliação</label>
                <div class="flex items-center gap-2">
                    <div class="flex" id="edit-star-rating">
                        @for($i = 1; $i <= 5; $i++)
                            <svg data-rating="{{ $i }}" class="edit-star-input w-8 h-8 cursor-pointer text-gray-300 hover:text-yellow-300 transition" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M13.849 4.22c-.684-1.626-3.014-1.626-3.698 0L8.397 8.387l-4.552.361c-1.775.14-2.495 2.331-1.142 3.477l3.468 2.937-1.06 4.392c-.413 1.713 1.472 3.067 2.992 2.149L12 19.35l3.897 2.354c1.52.918 3.405-.436 2.992-2.15l-1.06-4.39 3.468-2.938c1.353-1.146.633-3.336-1.142-3.477l-4.552-.36-1.754-4.17Z"/>
                            </svg>
                        @endfor
                    </div>
                </div>
                <input type="hidden" name="rating" id="edit-rating-value" required>
            </div>

            <div class="mb-4">
                <label for="edit-comment" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Comentário</label>
                <textarea id="edit-comment" name="comment" rows="4" class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300"></textarea>
            </div>

            <div class="flex gap-2">
                <button type="submit" class="text-white bg-primary-700 hover:bg-primary-800 font-medium rounded-lg text-sm px-5 py-2.5">Salvar</button>
                <button type="button" onclick="closeEditModal()" class="text-gray-900 bg-white border border-gray-300 hover:bg-gray-100 font-medium rounded-lg text-sm px-5 py-2.5">Cancelar</button>
            </div>
        </form>
    </div>
</div>

<script>
// Star rating functionality
function initStarRating(containerSelector, valueInputSelector, isEdit = false) {
    const stars = document.querySelectorAll(`${containerSelector} .${isEdit ? 'edit-star-input' : 'star-input'}`);
    const ratingInput = document.querySelector(valueInputSelector);
    const ratingText = document.querySelector('#rating-text');
    
    stars.forEach(star => {
        star.addEventListener('click', function() {
            const rating = this.dataset.rating;
            ratingInput.value = rating;
            updateStars(stars, rating);
            if (ratingText) ratingText.textContent = `${rating} estrela${rating > 1 ? 's' : ''}`;
        });
        
        star.addEventListener('mouseenter', function() {
            updateStars(stars, this.dataset.rating);
        });
    });
    
    const container = document.querySelector(containerSelector);
    container.addEventListener('mouseleave', function() {
        updateStars(stars, ratingInput.value || 0);
    });
}

function updateStars(stars, rating) {
    stars.forEach((star, index) => {
        if (index < rating) {
            star.classList.remove('text-gray-300');
            star.classList.add('text-yellow-300');
        } else {
            star.classList.remove('text-yellow-300');
            star.classList.add('text-gray-300');
        }
    });
}

// Initialize rating inputs
initStarRating('#star-rating', '#rating-value');
initStarRating('#edit-star-rating', '#edit-rating-value', true);

// Edit review modal
function editReview(reviewId, rating, comment) {
    const modal = document.getElementById('editReviewModal');
    const form = document.getElementById('editReviewForm');
    const commentField = document.getElementById('edit-comment');
    const ratingInput = document.getElementById('edit-rating-value');
    
    form.action = `/reviews/${reviewId}`;
    commentField.value = comment || '';
    ratingInput.value = rating;
    
    const stars = document.querySelectorAll('#edit-star-rating .edit-star-input');
    updateStars(stars, rating);
    
    modal.classList.remove('hidden');
}

function closeEditModal() {
    document.getElementById('editReviewModal').classList.add('hidden');
}

// Moderation Modal functionality
function openModerationModal(userId, userName, isBanned, silencedUntil) {
    const modal = document.getElementById('moderationModal');
    if (!modal) return;

    document.getElementById('mod-user-name').textContent = userName;
    
    // Setup Ban form
    const banForm = document.getElementById('mod-ban-form');
    const banBtn = document.getElementById('mod-ban-btn');
    banForm.action = `/admin/users/${userId}/toggle-ban`;
    
    if (isBanned) {
        banBtn.textContent = 'Desbanir Usuário';
        banBtn.className = 'w-full py-2 px-4 rounded font-medium text-white bg-green-600 hover:bg-green-700 transition-colors';
    } else {
        banBtn.textContent = 'Banir Usuário';
        banBtn.className = 'w-full py-2 px-4 rounded font-medium text-white bg-red-600 hover:bg-red-700 transition-colors';
    }

    // Setup Silence form
    const silenceForm = document.getElementById('mod-silence-form');
    silenceForm.action = `/admin/users/${userId}/silence`;
    
    const silenceInfo = document.getElementById('current-silence-info');
    const silenceExpiry = document.getElementById('silence-expiry');
    
    if (silencedUntil) {
        silenceInfo.classList.remove('hidden');
        silenceExpiry.textContent = new Date(silencedUntil).toLocaleString('pt-BR');
    } else {
        silenceInfo.classList.add('hidden');
    }

    modal.classList.remove('hidden');
}

function closeModerationModal() {
    const modal = document.getElementById('moderationModal');
    if (modal) modal.classList.add('hidden');
}
</script>
