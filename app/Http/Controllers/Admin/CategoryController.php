<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index() {
        $categories = Category::withCount('products')->latest()->paginate(15);
        return view('admin.categories.index', compact('categories'));
    }
    public function create() { return view('admin.categories.form', ['category'=>new Category()]); }

    public function store(Request $r) {
        $data = $r->validate($this->rules());
        Category::create($data);
        return redirect()->route('admin.categories.index')->with('ok','Category created.');
    }

    public function edit(Category $category) { return view('admin.categories.form', compact('category')); }

    public function update(Request $r, Category $category) {
        $data = $r->validate($this->rules($category->id));
        $category->update($data);
        return redirect()->route('admin.categories.index')->with('ok','Category updated.');
    }

    public function destroy(Category $category) {
        $category->delete();
        return back()->with('ok','Category deleted.');
    }

    private function rules(?int $id = null): array {
        return [
            'name'        => 'required|string|max:120',
            'slug'        => 'nullable|string|max:140|unique:categories,slug'.($id ? ",$id" : ''),
            'gender'      => 'required|in:men,women,unisex',
            'description' => 'nullable|string|max:1000',
        ];
    }
}
