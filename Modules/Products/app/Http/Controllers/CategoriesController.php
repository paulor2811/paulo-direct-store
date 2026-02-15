<?php

namespace Modules\Products\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Products\Models\CategoriaProduto;

class CategoriesController extends Controller
{
    public function index()
    {
        $categories = CategoriaProduto::all();
        if ($categories->isEmpty()) {
             // Fallback seed inside controller for immediate fix
             CategoriaProduto::create([
                 'nome' => 'Elétrica e Eletrônica', 
                 'descricao' => 'Componentes, ferramentas e muito mais.'
             ]);
             $categories = CategoriaProduto::all();
        }
        return view('products::categories.index', compact('categories'));
    }

    public function create() { return view('products::create'); }
    public function store(Request $request) {}
    public function show($id) { return view('products::show'); }
    public function edit($id) { return view('products::edit'); }
    public function update(Request $request, $id) {}
    public function destroy($id) {}
}
