<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Modules\Products\Models\Produto;

class PublicProfileController extends Controller
{
    /**
     * Display the public profile of a user.
     */
    public function show($username)
    {
        $user = User::where('username', $username)->firstOrFail();

        // Get all products belonging to the user (either directly or via their stores)
        $storeIds = $user->stores->pluck('id');
        
        $products = Produto::where(function($query) use ($user, $storeIds) {
                $query->where('user_id', $user->id)
                      ->orWhereIn('store_id', $storeIds);
            })
            ->where('is_active', true)
            ->with(['fotos', 'categoria']) // Eager load some common relations
            ->orderBy('created_at', 'desc')
            ->paginate(12);

        $stats = [
            'total_ads' => $products->total(),
            'joined_at' => $user->created_at->format('M Y'), // e.g. "Jan 2024"
            'stores_count' => $user->stores->count(),
        ];

        return view('profiles.show', compact('user', 'products', 'stats'));
    }
}
