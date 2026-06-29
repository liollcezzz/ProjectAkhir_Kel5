<?php
namespace App\Http\Controllers\Warehouse;

use App\Http\Controllers\Controller;
use App\Models\{Category, Product, StockMovement};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class InventoryController extends Controller
{
    public function index(Request $r) {
        $q = trim((string) $r->get('q'));
        $products = Product::with('categories')
            ->when($q !== '', fn($qq) => $qq->where(function($s) use ($q){
                $s->where('name','like',"%$q%")->orWhere('sku','like',"%$q%");
            }))->orderBy('name')->paginate(20)->withQueryString();
        return view('warehouse.inventory.index', compact('products','q'));
    }

    public function restockForm(Product $product) {
        return view('warehouse.inventory.restock', compact('product'));
    }

    public function restock(Request $r, Product $product) {
        $data = $r->validate([
            'quantity' => 'required|integer|min:1',
            'notes'    => 'nullable|string|max:500',
        ]);
        DB::transaction(function () use ($product, $data) {
            $product->increment('stock', $data['quantity']);
            StockMovement::create([
                'product_id'      => $product->id,
                'user_id'         => auth()->id(),
                'type'            => 'restock',
                'quantity_change' => $data['quantity'],
                'stock_after'     => $product->fresh()->stock,
                'notes'           => $data['notes'] ?? null,
            ]);
        });
        return redirect()->route('warehouse.inventory.index')->with('ok','Stock added.');
    }

    public function createProduct() {
        $categories = Category::orderBy('name')->get();
        return view('warehouse.inventory.create', ['product'=>new Product(), 'categories'=>$categories]);
    }

    public function storeProduct(Request $r) {
        $data = $r->validate([
            'sku'           => 'required|string|max:64|unique:products,sku',
            'name'          => 'required|string|max:160',
            'description'   => 'nullable|string',
            'gender'        => 'required|in:men,women,unisex',
            'price'         => 'required|numeric|min:0',
            'stock'         => 'required|integer|min:0',
            'image'         => 'nullable|image|max:2048',
            'categories'    => 'array',
            'categories.*'  => 'exists:categories,id',
        ]);
        if ($r->hasFile('image')) {
            $data['image'] = $r->file('image')->store('products', 'public');
        }
        $product = Product::create($data);
        $product->categories()->sync($data['categories'] ?? []);
        if ($data['stock'] > 0) {
            StockMovement::create([
                'product_id'      => $product->id,
                'user_id'         => auth()->id(),
                'type'            => 'restock',
                'quantity_change' => $data['stock'],
                'stock_after'     => $data['stock'],
                'notes'           => 'Initial stock',
            ]);
        }
        return redirect()->route('warehouse.inventory.index')->with('ok','Product created.');
    }

    public function editCategories(Product $product) {
        $categories = Category::orderBy('name')->get();
        return view('warehouse.inventory.categories', compact('product','categories'));
    }

    public function syncCategories(Request $r, Product $product) {
        $data = $r->validate([
            'categories'   => 'array',
            'categories.*' => 'exists:categories,id',
        ]);
        $product->categories()->sync($data['categories'] ?? []);
        return back()->with('ok','Categories updated.');
    }

    public function edit(Product $product) {
        $categories = Category::orderBy('name')->get();
        return view('warehouse.inventory.edit', compact('product','categories'));
    }

    public function update(Request $r, Product $product) {
        $data = $r->validate([
            'sku'           => 'required|string|max:64|unique:products,sku,'.$product->id,
            'name'          => 'required|string|max:160',
            'description'   => 'nullable|string',
            'gender'        => 'required|in:men,women,unisex',
            'price'         => 'required|numeric|min:0',
            'stock'         => 'required|integer|min:0',
            'image'         => 'nullable|image|max:2048',
            'categories'    => 'array',
            'categories.*'  => 'exists:categories,id',
        ]);

        $oldStock = $product->stock;
        $newStock = (int) $data['stock'];

        if ($r->hasFile('image')) {
            if ($product->image && Storage::disk('public')->exists($product->image)) {
                Storage::disk('public')->delete($product->image);
            }
            $data['image'] = $r->file('image')->store('products', 'public');
        }

        $product->update($data);
        $product->categories()->sync($data['categories'] ?? []);

        if ($newStock !== $oldStock) {
            StockMovement::create([
                'product_id'      => $product->id,
                'user_id'         => auth()->id(),
                'type'            => $newStock > $oldStock ? 'restock' : 'adjustment',
                'quantity_change' => $newStock - $oldStock,
                'stock_after'     => $newStock,
                'notes'           => 'Updated via edit',
            ]);
        }

        return redirect()->route('warehouse.inventory.index')->with('ok','Product updated.');
    }
}
