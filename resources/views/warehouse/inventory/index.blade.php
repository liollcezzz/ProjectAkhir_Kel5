@extends('layouts.dashboard')
@section('title','Inventory')
@section('heading','Inventory')
@section('content')
<div class="flex flex-wrap gap-3 justify-between mb-4">
  <form method="GET" class="flex-1 max-w-sm"><input name="q" value="{{ $q }}" class="input" placeholder="Search name or SKU…"></form>
  <a href="{{ route('warehouse.inventory.create') }}" class="btn-dark">+ New product</a>
</div>
<div class="card overflow-hidden">
  <table class="w-full table">
    <thead><tr><th>SKU</th><th>Name</th><th>Categories</th><th>Stock</th><th>Price</th><th></th></tr></thead>
    <tbody>
      @forelse($products as $p)
        <tr>
          <td class="font-mono text-xs">{{ $p->sku }}</td>
          <td><p class="font-medium">{{ $p->name }}</p><p class="text-xs text-ink-500 capitalize">{{ $p->gender }}</p></td>
          <td class="text-xs">{{ $p->categories->pluck('name')->join(', ') ?: '—' }}</td>
          <td>
            @if($p->isOutOfStock())<span class="badge bg-red-50 text-red-700">0</span>
            @elseif($p->isLowStock())<span class="badge bg-amber-50 text-amber-700">{{ $p->stock }}</span>
            @else<span class="badge bg-green-50 text-green-700">{{ $p->stock }}</span>@endif
          </td>
          <td>Rp {{ number_format($p->price,0,',','.') }}</td>
          <td class="text-right whitespace-nowrap">
            <a href="{{ route('warehouse.inventory.restock.form',$p) }}" class="text-sm underline">Restock</a>
            <a href="{{ route('warehouse.inventory.categories',$p) }}" class="text-sm underline ml-3">Categories</a>
          </td>
        </tr>
      @empty <tr><td colspan="6" class="px-4 py-6 text-center text-ink-500">No products.</td></tr>
      @endforelse
    </tbody>
  </table>
</div>
<div class="mt-6">{{ $products->links() }}</div>
@endsection
