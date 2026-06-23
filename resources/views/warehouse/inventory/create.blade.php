@extends('layouts.dashboard')
@section('title','New product')
@section('heading','New product')
@section('content')
<form method="POST" action="{{ route('warehouse.inventory.store') }}" enctype="multipart/form-data" class="card p-6 space-y-4 max-w-2xl">
  @csrf
  <div class="grid sm:grid-cols-2 gap-4">
    <div><label class="text-sm font-medium">SKU</label>
      <input name="sku" value="{{ old('sku') }}" class="input mt-1" required></div>
    <div><label class="text-sm font-medium">Name</label>
      <input name="name" value="{{ old('name') }}" class="input mt-1" required></div>
    <div><label class="text-sm font-medium">Gender</label>
      <select name="gender" class="input mt-1">
        @foreach(['men','women','unisex'] as $g)<option value="{{ $g }}">{{ ucfirst($g) }}</option>@endforeach
      </select></div>
    <div><label class="text-sm font-medium">Price (Rp)</label>
      <input type="number" step="0.01" min="0" name="price" value="{{ old('price') }}" class="input mt-1" required></div>
    <div><label class="text-sm font-medium">Initial stock</label>
      <input type="number" min="0" name="stock" value="{{ old('stock',0) }}" class="input mt-1" required></div>
    <div><label class="text-sm font-medium">Image (optional)</label>
      <input type="file" name="image" accept="image/*" class="mt-1 text-sm"></div>
  </div>
  <div><label class="text-sm font-medium">Description</label>
    <textarea name="description" rows="3" class="input mt-1">{{ old('description') }}</textarea></div>
  <div>
    <label class="text-sm font-medium">Categories</label>
    <div class="grid grid-cols-2 gap-2 mt-2">
      @foreach($categories as $c)
        <label class="flex items-center gap-2 text-sm">
          <input type="checkbox" name="categories[]" value="{{ $c->id }}">
          {{ $c->name }} <span class="text-xs text-ink-500">({{ $c->gender }})</span>
        </label>
      @endforeach
    </div>
  </div>
  <div class="flex gap-2">
    <button class="btn-dark">Create product</button>
    <a href="{{ route('warehouse.inventory.index') }}" class="btn-outline">Cancel</a>
  </div>
</form>
@endsection
