@extends('layouts.dashboard')
@section('title','Restock')
@section('heading','Restock — '.$product->name)
@section('content')
<form method="POST" action="{{ route('warehouse.inventory.restock',$product) }}" class="card p-6 space-y-4 max-w-md">
  @csrf
  <div class="text-sm text-ink-600">
    <p><strong>SKU:</strong> {{ $product->sku }}</p>
    <p><strong>Current stock:</strong> {{ $product->stock }}</p>
  </div>
  <div><label class="text-sm font-medium">Quantity to add</label>
    <input type="number" min="1" name="quantity" value="1" class="input mt-1" required></div>
  <div><label class="text-sm font-medium">Notes</label>
    <textarea name="notes" rows="2" class="input mt-1"></textarea></div>
  <div class="flex gap-2">
    <button class="btn-dark">Add stock</button>
    <a href="{{ route('warehouse.inventory.index') }}" class="btn-outline">Cancel</a>
  </div>
</form>
@endsection
