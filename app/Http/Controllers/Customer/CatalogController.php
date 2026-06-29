<?php
namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\{Category, Product};
use Illuminate\Http\Request;

class CatalogController extends Controller
{
    public function index(Request $r) {
        $q        = trim((string) $r->get('q'));
        $gender   = $r->get('gender');
        $catSlug  = $r->get('category');

        $products = Product::with('categories')->where('is_active', true)
            ->when($q !== '', fn($qq) => $qq->where('name','like',"%$q%"))
            ->when(in_array($gender, ['men','women','unisex']), fn($qq) => $qq->where('gender', $gender))
            ->when($catSlug, fn($qq) => $qq->whereHas('categories', fn($c) => $c->where('slug', $catSlug)))
            ->latest()->paginate(12)->withQueryString();

        $categories = Category::orderBy('name')->get();
        return view('customer.catalog', compact('products','categories','q','gender','catSlug'));
    }

    public function show(Product $product) {
        abort_unless($product->is_active, 404);
        $related = Product::where('id','!=',$product->id)->where('gender',$product->gender)
            ->where('is_active',true)->limit(4)->get();

        $prev = Product::where('is_active', true)
            ->where('id', '<', $product->id)
            ->orderBy('id', 'desc')
            ->first();

        $next = Product::where('is_active', true)
            ->where('id', '>', $product->id)
            ->orderBy('id', 'asc')
            ->first();

        return view('customer.product', compact('product','related','prev','next'));
    }
}
