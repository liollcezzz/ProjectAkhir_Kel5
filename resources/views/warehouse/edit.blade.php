@extends('layouts.dashboard')
@section('title','Edit product')
@section('heading','Edit — '.$product->name)
@section('content')
<form method="POST" action="{{ route('warehouse.inventory.update',$product) }}" enctype="multipart/form-data" class="card p-6 space-y-4 max-w-2xl">
  @csrf @method('PUT')
  <div class="grid sm:grid-cols-2 gap-4">
    <div><label class="text-sm font-medium text-white/80">SKU</label>
      <input name="sku" value="{{ old('sku',$product->sku) }}" class="input mt-1" required></div>
    <div><label class="text-sm font-medium text-white/80">Name</label>
      <input name="name" value="{{ old('name',$product->name) }}" class="input mt-1" required></div>
    <div><label class="text-sm font-medium text-white/80">Gender</label>
      <select name="gender" class="input mt-1">
        @foreach(['men','women','unisex'] as $g)
          <option value="{{ $g }}" @selected(old('gender',$product->gender)===$g)>{{ ucfirst($g) }}</option>
        @endforeach
      </select></div>
    <div><label class="text-sm font-medium text-white/80">Price (Rp)</label>
      <input type="number" step="0.01" min="0" name="price" value="{{ old('price',$product->price) }}" class="input mt-1" required></div>
    <div><label class="text-sm font-medium text-white/80">Stock</label>
      <input type="number" min="0" name="stock" value="{{ old('stock',$product->stock) }}" class="input mt-1" required></div>
    <div>
      <label class="text-sm font-medium text-white/80">Image</label>
      <div class="mt-1 flex items-center gap-3">
        <div class="w-14 h-14 bg-white/10 overflow-hidden flex-shrink-0">
          <img src="{{ $product->imageUrl() }}" class="w-full h-full object-cover">
        </div>
        <input type="file" name="image" accept="image/*" class="text-sm text-white/60 file:mr-3 file:py-1 file:px-3 file:border-0 file:text-sm file:bg-white/10 file:text-white/70 hover:file:bg-white/20">
      </div>
      @if($product->image)
        <p class="text-xs text-white/40 mt-1">Kosongkan jika tidak ingin mengganti gambar.</p>
      @endif
    </div>
  </div>
  <div><label class="text-sm font-medium text-white/80">Description</label>
    <textarea name="description" rows="3" class="input mt-1">{{ old('description',$product->description) }}</textarea></div>
  <div>
    <label class="text-sm font-medium text-white/80">Categories</label>
    @php $selected = $product->categories->pluck('id')->all(); @endphp
    <div class="grid grid-cols-2 gap-2 mt-2">
      @foreach($categories as $c)
        <label class="flex items-center gap-2 text-sm text-white/70">
          <input type="checkbox" name="categories[]" value="{{ $c->id }}" @checked(in_array($c->id,$selected)) class="accent-brand-accent">
          {{ $c->name }} <span class="text-xs text-white/40">({{ $c->gender }})</span>
        </label>
      @endforeach
    </div>
  </div>
  <div class="flex gap-2">
    <button class="btn-dark">Update product</button>
    <a href="{{ route('warehouse.inventory.index') }}" class="btn-outline">Cancel</a>
  </div>
</form>
@endsection
