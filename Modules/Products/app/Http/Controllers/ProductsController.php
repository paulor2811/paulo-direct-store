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

        // Filter by category
        if ($request->has('category') && $request->category) {
            $query->where('categoria_produto_id', $request->category);
        }

        // Filter by condition
        if ($request->has('condition') && $request->condition) {
            $query->where('condicao', $request->condition);
        }

        // Filter by Price Range
        if ($request->filled('min_price')) {
            $query->where('preco', '>=', $request->min_price);
        }
        if ($request->filled('max_price')) {
            $query->where('preco', '<=', $request->max_price);
        }

        // Advanced Search (unaccent & ILIKE)
        if ($request->filled('search')) {
            $searchTerm = '%' . $request->search . '%';
            $query->whereRaw('unaccent(nome) ILIKE unaccent(?)', [$searchTerm]);
        }

        // Sorting
        $sort = $request->get('sort', 'recente');
        switch ($sort) {
            case 'preco_asc':
                $query->orderBy('preco', 'asc');
                break;
            case 'preco_desc':
                $query->orderBy('preco', 'desc');
                break;
            case 'recente':
            default:
                $query->orderBy('created_at', 'desc');
                break;
        }

        $products = $query->paginate(12)->withQueryString();

        return view('products::index', compact('products'));
    }

    public function create()
    {
        return view('products::create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nome' => 'required|string|max:255',
            'descricao' => 'required|string',
            'marca' => 'nullable|string|max:255',
            'modelo' => 'nullable|string|max:255',
            'cor' => 'nullable|string|max:100',
            'preco' => 'required|numeric|min:0',
            'condicao' => 'required|string|in:novo,seminovo,usado,sucata',
            'categoria_produto_id' => 'required|exists:categorias_produtos,id',
            'images' => 'nullable|array',
            'images.*' => 'image|mimes:jpeg,jpg,png,gif,webp|max:10240',
        ]);

        $data = $validated;
        $data['user_id'] = auth()->id();
        $data['store_id'] = session('active_store_id');

        $product = Produto::create($data);

        // Upload images to S3
        if ($request->hasFile('images')) {
            $imageStorage = app(\App\Services\ImageStorageService::class);
            foreach ($request->file('images') as $image) {
                $imageStorage->uploadProductImage($product, $image);
            }
        }

        return redirect()->route('products.show', $product->id)
            ->with('success', 'Produto criado com sucesso!');
    }

    public function show($id)
    {
        $product = Produto::with(['fotos', 'store', 'user'])->findOrFail($id);
        $relatedProducts = Produto::with('fotos')
            ->where('id', '!=', $id)
            ->inRandomOrder()
            ->take(4)
            ->get();
            
        return view('products::show', compact('product', 'relatedProducts'));
    }

    public function edit($id)
    {
        $product = Produto::findOrFail($id);
        $categories = CategoriaProduto::all();
        
        return view('products::edit', compact('product', 'categories'));
    }

    public function update(Request $request, $id)
    {
        $product = Produto::findOrFail($id);
        
        $validated = $request->validate([
            'nome' => 'required|string|max:255',
            'descricao' => 'required|string',
            'marca' => 'nullable|string|max:255',
            'modelo' => 'nullable|string|max:255',
            'cor' => 'nullable|string|max:100',
            'preco' => 'required|numeric|min:0',
            'condicao' => 'required|string|in:novo,seminovo,usado,sucata',
            'categoria_produto_id' => 'required|exists:categorias_produtos,id',
            'images' => 'nullable|array',
            'images.*' => 'image|mimes:jpeg,jpg,png,gif,webp|max:10240',
            'delete_images' => 'nullable|array',
            'delete_images.*' => 'exists:media_files,id',
        ]);

        $product->update($validated);

        $imageStorage = app(\App\Services\ImageStorageService::class);

        // Delete marked images
        if ($request->has('delete_images')) {
            foreach ($request->delete_images as $imageId) {
                $mediaFile = \App\Models\MediaFile::find($imageId);
                if ($mediaFile && $mediaFile->model_id === $product->id) {
                    $imageStorage->deleteFile($mediaFile);
                }
            }
        }

        // Upload new images
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $imageStorage->uploadProductImage($product, $image);
            }
        }

        return redirect()->route('products.show', $product->id)
            ->with('success', 'Produto atualizado com sucesso!');
    }

    public function destroy($id)
    {
        $product = Produto::findOrFail($id);
        $product->delete(); // Soft delete - observer will handle S3 cleanup
        
        return redirect()->route('products.index')
            ->with('success', 'Produto deletado com sucesso!');
    }

    /**
     * Delete a single product image
     */
    public function deleteImage(Request $request, $productId, $imageId)
    {
        $product = Produto::findOrFail($productId);
        $mediaFile = \App\Models\MediaFile::findOrFail($imageId);

        // Verify image belongs to this product
        if ($mediaFile->model_id !== $product->id) {
            abort(403, 'Unauthorized');
        }

        $imageStorage = app(\App\Services\ImageStorageService::class);
        $imageStorage->deleteFile($mediaFile);

        if ($request->expectsJson()) {
            return response()->json(['success' => true]);
        }

        return redirect()->back()->with('success', 'Imagem deletada com sucesso!');
    }
}
