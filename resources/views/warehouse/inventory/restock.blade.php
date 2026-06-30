@extends('layouts.dashboard')
@section('title','Restock')
@section('heading','Restock — '.$product->name)
@section('content')
<form method="POST" action="{{ route('warehouse.inventory.restock',$product) }}" class="card p-6 space-y-4 max-w-md">
  @csrf
  <div class="text-sm text-white/70">
    <p><strong class="text-white/90">SKU:</strong> {{ $product->sku }}</p>
    <p><strong class="text-white/90">Current stock:</strong> <span class="text-gold-light font-semibold">{{ $product->stock }}</span></p>
  </div>
  <div><label class="text-sm font-medium text-white/80">Quantity to add</label>
    <input type="number" min="1" name="quantity" value="1" class="input mt-1" required></div>
  <div><label class="text-sm font-medium text-white/80">Notes</label>
    <textarea name="notes" rows="2" class="input mt-1"></textarea></div>
  <div class="flex gap-2">
    <button class="btn-dark">Add stock</button>
    <a href="{{ route('warehouse.inventory.index') }}" class="btn-outline">Cancel</a>
  </div>
</form>
@endsection
