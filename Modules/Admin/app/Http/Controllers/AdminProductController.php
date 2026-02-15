<?php

namespace Modules\Admin\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\MediaFile;
use App\Services\ImageStorageService;
use Illuminate\Http\Request;
use Modules\Products\Models\CategoriaProduto;
use Modules\Products\Models\Produto;

class AdminProductController extends Controller
{
    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $produto = Produto::findOrFail($id);
        
        // Log the deletion for debugging
        \Log::info('Product deletion triggered', [
            'product_id' => $id,
            'product_name' => $produto->nome,
            'user_id' => auth()->id(),
            'request_url' => request()->fullUrl(),
            'request_method' => request()->method(),
            'referer' => request()->header('referer'),
            'trace' => debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 5)
        ]);
        
        // ProdutoObserver will automatically delete S3 images
        $produto->delete();

        return redirect()->route('admin.products.index')->with('success', 'Produto removido com sucesso!');
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Produto::query()->with('categoria', 'fotos');

        // Filter by text (name, description, brand, model)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nome', 'ilike', "%{$search}%")
                  ->orWhere('descricao', 'ilike', "%{$search}%")
                  ->orWhere('marca', 'ilike', "%{$search}%")
                  ->orWhere('modelo', 'ilike', "%{$search}%");
            });
        }

        // Filter by category
        if ($request->filled('categoria_id')) {
            $query->where('categoria_produto_id', $request->categoria_id);
        }

        // Filter by price range
        if ($request->filled('price_min')) {
            $query->where('preco', '>=', $request->price_min);
        }
        if ($request->filled('price_max')) {
            $query->where('preco', '<=', $request->price_max);
        }

        // Filter by date range
        if ($request->filled('date_start')) {
            $query->whereDate('created_at', '>=', $request->date_start);
        }
        if ($request->filled('date_end')) {
            $query->whereDate('created_at', '<=', $request->date_end);
        }

        // Sorting
        $sort = $request->get('sort', 'created_at');
        $direction = $request->get('direction', 'desc');

        // Validate sort column and direction
        if (!in_array($sort, ['preco', 'created_at'])) {
            $sort = 'created_at';
        }
        if (!in_array($direction, ['asc', 'desc'])) {
            $direction = 'desc';
        }

        $produtos = $query->orderBy($sort, $direction)->paginate(10);
        $categorias = CategoriaProduto::orderBy('nome')->get();

        return view('admin::products.index', compact('produtos', 'categorias'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categorias = CategoriaProduto::orderBy('nome')->get();
        return view('admin::products.create', compact('categorias'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nome' => 'required|string|max:255',
            'marca' => 'required|string|max:255',
            'modelo' => 'required|string|max:255',
            'cor' => 'required|string|max:255',
            'preco' => 'required|numeric|min:0',
            'categoria_produto_id' => 'required|exists:categorias_produtos,id',
            'descricao' => 'nullable|string',
            'descricao' => 'nullable|string',
            'fotos' => 'nullable|array|max:5',
            'fotos.*' => 'image|max:2048', // 2MB Max per image
        ]);

        $produto = Produto::create([
            'nome' => $validated['nome'],
            'marca' => $validated['marca'],
            'modelo' => $validated['modelo'],
            'cor' => $validated['cor'],
            'preco' => $validated['preco'],
            'categoria_produto_id' => $validated['categoria_produto_id'],
            'descricao' => $validated['descricao'] ?? null,
        ]);

        if ($request->hasFile('fotos')) {
            $imageStorage = app(ImageStorageService::class);
            foreach ($request->file('fotos') as $foto) {
                $imageStorage->uploadProductImage($produto, $foto);
            }
        }

        return redirect()->route('products.show', $produto->id)->with('success', 'Produto criado com sucesso!');
    }
    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $produto = Produto::findOrFail($id);
        $categorias = CategoriaProduto::orderBy('nome')->get();
        return view('admin::products.create', compact('produto', 'categorias'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        \Log::info('UPDATE method called', [
            'product_id' => $id,
            'request_method' => $request->method(),
            'has_fotos' => $request->hasFile('fotos'),
            'has_remove_fotos' => $request->has('remove_fotos'),
            'request_url' => $request->fullUrl(),
        ]);
        
        $produto = Produto::findOrFail($id);
        
        $validated = $request->validate([
            'nome' => 'required|string|max:255',
            'marca' => 'required|string|max:255',
            'modelo' => 'required|string|max:255',
            'cor' => 'required|string|max:255',
            'preco' => 'required|numeric|min:0',
            'categoria_produto_id' => 'required|exists:categorias_produtos,id',
            'descricao' => 'nullable|string',
            'fotos' => 'nullable|array|max:5',
            'fotos.*' => 'image|max:2048',
            'remove_fotos' => 'nullable|array',
            'remove_fotos.*' => 'exists:media_files,id',
        ]);

        $produto->update([
            'nome' => $validated['nome'],
            'marca' => $validated['marca'],
            'modelo' => $validated['modelo'],
            'cor' => $validated['cor'],
            'preco' => $validated['preco'],
            'categoria_produto_id' => $validated['categoria_produto_id'],
            'descricao' => $validated['descricao'] ?? null,
        ]);

        $imageStorage = app(ImageStorageService::class);

        // Remove photos
        if ($request->has('remove_fotos')) {
             $fotosToRemove = MediaFile::whereIn('id', $request->remove_fotos)
                 ->where('model_type', get_class($produto))
                 ->where('model_id', $produto->id)
                 ->get();
             foreach ($fotosToRemove as $foto) {
                 $imageStorage->deleteFile($foto);
             }
        }

        // Add new photos
        if ($request->hasFile('fotos')) {
            $currentCount = $produto->images()->count();
            $newCount = count($request->file('fotos'));
            
            if ($currentCount + $newCount > 5) {
                return back()->withErrors(['fotos' => 'O limite total é de 5 fotos por produto.']);
            }

            foreach ($request->file('fotos') as $foto) {
                $imageStorage->uploadProductImage($produto, $foto);
            }
            
            \Log::info('Images uploaded successfully in update', [
                'product_id' => $produto->id,
                'new_images_count' => $newCount,
                'total_images' => $produto->images()->count(),
            ]);
        }

        \Log::info('UPDATE completed successfully', [
            'product_id' => $produto->id,
            'redirect_to' => route('admin.products.index'),
        ]);

        return redirect()->route('products.show', $produto->id)->with('success', 'Produto atualizado com sucesso!');
    }
}
