<?php

namespace Modules\Admin\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class AdminUserController extends Controller
{
    /**
     * Display a listing of users.
     */
    public function index(Request $request)
    {
        $query = User::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'ilike', "%{$search}%")
                  ->orWhere('email', 'ilike', "%{$search}%")
                  ->orWhere('cpf', 'ilike', "%{$search}%");
            });
        }

        $users = $query->latest()->paginate(20);

        return view('admin::users.index', compact('users'));
    }

    /**
     * Toggle user ban status.
     */
    public function toggleBan($id)
    {
        $user = User::findOrFail($id);

        // Prevent admin from banning themselves
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Você não pode banir sua própria conta.');
        }

        $user->is_banned = !$user->is_banned;
        $user->save();

        $status = $user->is_banned ? 'banido' : 'desbanido';
        return back()->with('success', "Usuário {$status} com sucesso!");
    }

    /**
     * Silence user for a specific duration.
     */
    public function silence(Request $request, $id)
    {
        $user = User::findOrFail($id);

        if ($user->id === auth()->id()) {
            return back()->with('error', 'Você não pode silenciar sua própria conta.');
        }

        $request->validate([
            'hours' => 'required|integer|min:0',
        ]);

        if ($request->hours == 0) {
            $user->silenced_until = null;
            $message = "Silenciamento removido para {$user->name}.";
        } else {
            $user->silenced_until = now()->addHours($request->hours);
            $message = "Usuário {$user->name} silenciado por {$request->hours} hora(s).";
        }

        $user->save();

        return back()->with('success', $message);
    }

    /**
     * Toggle admin status for a user.
     */
    public function toggleAdmin($id)
    {
        $user = User::findOrFail($id);

        if ($user->id === auth()->id()) {
            return back()->with('error', 'Você não pode remover seu próprio acesso administrativo.');
        }

        $user->is_admin = !$user->is_admin;
        $user->save();

        $status = $user->is_admin ? 'promovido a administrador' : 'rebaixado a usuário comum';
        return back()->with('success', "Usuário {$user->name} {$status} com sucesso!");
    }
}
