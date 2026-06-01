<?php
namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index() {
        $cart = session('cart', []);
        $ids  = array_keys($cart);
        $products = Product::whereIn('id', $ids)->get()->keyBy('id');
        $rows = [];
        $total = 0;
        foreach ($cart as $id => $qty) {
            if (!isset($products[$id])) continue;
            $p = $products[$id];
            $line = $p->price * $qty;
            $total += $line;
            $rows[] = compact('p','qty','line');
        }
        return view('customer.cart', compact('rows','total'));
    }

    public function add(Request $r, Product $product) {
        $qty = max(1, (int) $r->get('qty', 1));
        $cart = session('cart', []);
        $cart[$product->id] = min(($cart[$product->id] ?? 0) + $qty, $product->stock);
        session(['cart' => $cart]);
        return back()->with('ok', "Added {$product->name} to cart.");
    }

    public function update(Request $r) {
        $items = (array) $r->get('items', []);
        $cart = [];
        foreach ($items as $id => $qty) {
            $qty = (int) $qty;
            if ($qty > 0) $cart[(int)$id] = $qty;
        }
        session(['cart' => $cart]);
        return back()->with('ok','Cart updated.');
    }

    public function remove(Product $product) {
        $cart = session('cart', []);
        unset($cart[$product->id]);
        session(['cart' => $cart]);
        return back();
    }
}
