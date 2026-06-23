@extends('layouts.dashboard')
@section('title','Warehouse Dashboard')
@section('heading','Warehouse Dashboard')
@section('content')
<div class="grid sm:grid-cols-3 gap-4">
  <div class="card p-5"><p class="text-xs uppercase text-ink-500">Total SKU</p>
    <p class="text-2xl font-semibold mt-1">{{ $totalSku }}</p></div>
  <div class="card p-5"><p class="text-xs uppercase text-ink-500">Total Categories</p>
    <p class="text-2xl font-semibold mt-1">{{ $totalCategories }}</p></div>
  <div class="card p-5"><p class="text-xs uppercase text-ink-500">Out of stock</p>
    <p class="text-2xl font-semibold mt-1 text-red-600">{{ $outOfStock->count() }}</p></div>
</div>

<div class="grid lg:grid-cols-2 gap-6 mt-6">
  <div class="card overflow-hidden">
    <div class="px-5 py-4 border-b border-ink-200 flex items-center justify-between">
      <h2 class="font-semibold">Out of stock</h2>
      <a href="{{ route('warehouse.inventory.index') }}" class="text-sm underline">Manage</a>
    </div>
    @if($outOfStock->isEmpty())<p class="px-5 py-6 text-ink-500 text-sm">Nothing out of stock. </p>
    @else
      <table class="w-full table">
        <thead><tr><th>SKU</th><th>Name</th><th></th></tr></thead><tbody>
        @foreach($outOfStock as $p)
          <tr><td class="font-mono text-xs">{{ $p->sku }}</td><td>{{ $p->name }}</td>
              <td class="text-right"><a class="text-sm underline" href="{{ route('warehouse.inventory.restock.form',$p) }}">Restock</a></td></tr>
        @endforeach
        </tbody></table>
    @endif
  </div>

  <div class="card overflow-hidden">
    <div class="px-5 py-4 border-b border-ink-200"><h2 class="font-semibold">Low stock (&lt; {{ \App\Models\Product::LOW_STOCK_THRESHOLD }})</h2></div>
    @if($lowStock->isEmpty())<p class="px-5 py-6 text-ink-500 text-sm">No items low.</p>
    @else
      <table class="w-full table">
        <thead><tr><th>SKU</th><th>Name</th><th>Stock</th></tr></thead><tbody>
        @foreach($lowStock as $p)
          <tr><td class="font-mono text-xs">{{ $p->sku }}</td><td>{{ $p->name }}</td>
              <td><span class="badge bg-amber-50 text-amber-700">{{ $p->stock }}</span></td></tr>
        @endforeach
        </tbody></table>
    @endif
  </div>
</div>
@endsection
