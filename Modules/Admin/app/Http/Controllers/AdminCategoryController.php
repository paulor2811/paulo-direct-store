<?php

namespace Modules\Admin\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Products\Models\CategoriaProduto;

class AdminCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categorias = CategoriaProduto::orderBy('nome')->get();
        return view('admin::categories.index', compact('categorias'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin::categories.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nome' => 'required|string|max:255',
            'descricao' => 'nullable|string',
        ]);

        CategoriaProduto::create($validated);

        return redirect()->route('admin.categories.index')->with('success', 'Categoria criada com sucesso!');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $categoria = CategoriaProduto::findOrFail($id);
        return view('admin::categories.create', compact('categoria'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $categoria = CategoriaProduto::findOrFail($id);

        $validated = $request->validate([
            'nome' => 'required|string|max:255',
            'descricao' => 'nullable|string',
        ]);

        $categoria->update($validated);

        return redirect()->route('admin.categories.index')->with('success', 'Categoria atualizada com sucesso!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $categoria = CategoriaProduto::findOrFail($id);
        
        // Check if there are products in this category
        if ($categoria->produtos()->exists()) {
            return back()->with('error', 'Não é possível excluir uma categoria que possui produtos vinculados.');
        }

        $categoria->delete();

        return redirect()->route('admin.categories.index')->with('success', 'Categoria excluída com sucesso!');
    }
}
