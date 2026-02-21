<?php

namespace Modules\Stores\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class StoresController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $stores = auth()->user()->stores()->orderBy('created_at', 'desc')->get();
        return view('stores::index', compact('stores'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('stores::create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nome' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:stores,username',
            'endereco' => 'nullable|string|max:255',
            'telefone_fixo' => 'nullable|string|max:20',
            'whatsapp' => 'nullable|string|max:20',
            'logo' => 'nullable|image|mimes:jpeg,jpg,png,gif,webp|max:5120',
        ]);

        $store = auth()->user()->stores()->create($validated);

        if ($request->hasFile('logo')) {
            $imageStorage = app(\App\Services\ImageStorageService::class);
            $imageStorage->uploadStoreLogo($store, $request->file('logo'));
        }

        return redirect()->route('stores.index')->with('success', 'Loja criada com sucesso!');
    }

    /**
     * Show the specified resource.
     */
    public function show($id)
    {
        $store = \Modules\Stores\Models\Store::where('username', $id)->firstOrFail();
        $products = $store->produtos()->where('is_active', true)->orderBy('created_at', 'desc')->paginate(12);
        
        return view('stores::show', compact('store', 'products'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $store = auth()->user()->stores()->findOrFail($id);
        return view('stores::edit', compact('store'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $store = auth()->user()->stores()->findOrFail($id);

        $validated = $request->validate([
            'nome' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:stores,username,' . $store->id,
            'endereco' => 'nullable|string|max:255',
            'telefone_fixo' => 'nullable|string|max:20',
            'whatsapp' => 'nullable|string|max:20',
            'is_active' => 'boolean',
            'logo' => 'nullable|image|mimes:jpeg,jpg,png,gif,webp|max:5120',
        ]);

        $store->update($validated);

        if ($request->hasFile('logo')) {
            $imageStorage = app(\App\Services\ImageStorageService::class);
            $imageStorage->uploadStoreLogo($store, $request->file('logo'));
        }

        return redirect()->route('stores.index')->with('success', 'Loja atualizada com sucesso!');
    }

    /**
     * Set the current active store for the session.
     */
    public function setActive(Request $request)
    {
        $storeId = $request->input('store_id');
        
        if ($storeId === 'none') {
            session()->forget('active_store_id');
            return back()->with('success', 'Agora você está postando como Pessoa Física.');
        }

        $store = auth()->user()->stores()->findOrFail($storeId);
        session(['active_store_id' => $store->id]);

        return back()->with('success', "Você agora está operando como: {$store->nome}");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $store = auth()->user()->stores()->findOrFail($id);
        $store->delete();

        if (session('active_store_id') === $id) {
            session()->forget('active_store_id');
        }

        return redirect()->route('stores.index')->with('success', 'Loja removida com sucesso!');
    }
}
