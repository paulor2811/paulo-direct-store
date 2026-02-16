<?php

namespace Modules\Products\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\Products\Models\Produto;
use Modules\Products\Models\ProductReview;

class ReviewController extends Controller
{
    /**
     * Store a new review
     */
    public function store(Request $request, $productId)
    {
        $user = Auth::user();

        if ($user->is_silenced()) {
            $remaining = $user->silenced_until->diffForHumans();
            return back()->with('error', "Você está silenciado e não pode comentar. Restam {$remaining}.");
        }

        $validated = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
        ]);

        $product = Produto::findOrFail($productId);

        // Check if user already reviewed this product
        $existingReview = ProductReview::where('user_id', Auth::id())
            ->where('product_id', $productId)
            ->first();

        if ($existingReview) {
            return back()->with('error', 'Você já avaliou este produto. Use a opção de editar sua avaliação.');
        }

        ProductReview::create([
            'user_id' => Auth::id(),
            'product_id' => $productId,
            'rating' => $request->rating,
            'comment' => $request->comment,
        ]);

        return back()->with('success', 'Avaliação enviada com sucesso!');
    }

    /**
     * Update an existing review
     */
    public function update(Request $request, $reviewId)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
        ]);

        $review = ProductReview::findOrFail($reviewId);

        // Check ownership (admins can edit anything)
        if ($review->user_id !== Auth::id() && !Auth::user()->is_admin) {
            abort(403, 'Você não tem permissão para editar esta avaliação.');
        }

        $review->update([
            'rating' => $request->rating,
            'comment' => $request->comment,
        ]);

        return back()->with('success', 'Avaliação atualizada com sucesso!');
    }

    /**
     * Delete a review (soft delete)
     */
    public function destroy($reviewId)
    {
        $review = ProductReview::findOrFail($reviewId);

        // Check ownership (admins can delete anything)
        if ($review->user_id !== Auth::id() && !Auth::user()->is_admin) {
            abort(403, 'Você não tem permissão para excluir esta avaliação.');
        }

        $review->delete();

        return back()->with('success', 'Avaliação removida com sucesso!');
    }
    /**
     * Toggle review visibility (Admin only)
     */
    public function toggleVisibility($reviewId)
    {
        if (!Auth::user()->is_admin) {
            abort(403);
        }

        $review = ProductReview::findOrFail($reviewId);
        $review->is_visible = !$review->is_visible;
        $review->save();

        $status = $review->is_visible ? 'visível' : 'oculta';
        return back()->with('success', "Avaliação marcada como {$status}.");
    }
}
