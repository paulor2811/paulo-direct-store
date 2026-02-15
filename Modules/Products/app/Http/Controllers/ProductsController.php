<?php

namespace Modules\Products\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Products\Models\Produto;
use Modules\Products\Models\CategoriaProduto;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class ProductsController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware(['auth', 'verified'], except: ['index', 'show']),
        ];
    }

    public function index(Request $request)
    {
        $query = Produto::with('fotos');

        if ($request->has('category') && $request->category) {
            $query->where('categoria_produto_id', $request->category);
        }

        $products = $query->paginate(12);

        return view('products::index', compact('products'));
    }

    public function create()
    {
        return view('products::create');
    }

    public function store(Request $request) {}

    public function show($id)
    {
        $product = Produto::with('fotos')->findOrFail($id);
        $relatedProducts = Produto::with('fotos')
            ->where('id', '!=', $id)
            ->inRandomOrder()
            ->take(4)
            ->get();
            
        return view('products::show', compact('product', 'relatedProducts'));
    }

    public function edit($id)
    {
        return view('products::edit');
    }

    public function update(Request $request, $id) {}

    public function destroy($id) {}
}
