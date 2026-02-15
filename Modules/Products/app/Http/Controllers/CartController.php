<?php

namespace Modules\Products\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Products\Models\Produto;
use Illuminate\Support\Facades\Session;

class CartController extends Controller
{
    public function index()
    {
        $cart = Session::get('cart', []);
        $total = 0;
        $items = [];

        if (!empty($cart)) {
            $products = Produto::with('fotos')->whereIn('id', array_keys($cart))->get();
            
            foreach ($products as $product) {
                $qty = $cart[$product->id];
                $subtotal = $product->preco * $qty;
                $total += $subtotal;
                
                $items[] = (object) [
                    'product' => $product,
                    'quantity' => $qty,
                    'subtotal' => $subtotal
                ];
            }
        }

        return view('products::cart.index', compact('items', 'total'));
    }

    public function add(Request $request, $id)
    {
        $product = Produto::findOrFail($id);
        $cart = Session::get('cart', []);
        
        if (isset($cart[$id])) {
            $cart[$id]++;
        } else {
            $cart[$id] = 1;
        }
        
        Session::put('cart', $cart);
        
        return redirect()->back()->with('success', 'Produto adicionado ao carrinho!');
    }

    public function remove($id)
    {
        $cart = Session::get('cart', []);
        
        if (isset($cart[$id])) {
            unset($cart[$id]);
            Session::put('cart', $cart);
        }
        
        return redirect()->route('cart.index')->with('success', 'Produto removido do carrinho.');
    }

    public function update(Request $request, $id)
    {
        $qty = max(1, intval($request->quantity));
        $cart = Session::get('cart', []);
        
        if (isset($cart[$id])) {
            $cart[$id] = $qty;
            Session::put('cart', $cart);
        }
        
        return redirect()->route('cart.index');
    }
}
