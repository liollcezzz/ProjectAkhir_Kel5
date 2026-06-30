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
          <td class="font-mono text-xs text-white/50">{{ $p->sku }}</td>
          <td><p class="font-medium text-white/90">{{ $p->name }}</p><p class="text-xs text-white/40 capitalize">{{ $p->gender }}</p></td>
          <td class="text-xs text-white/50">{{ $p->categories->pluck('name')->join(', ') ?: '—' }}</td>
          <td>
            @if($p->isOutOfStock())<span class="badge !bg-red-500/20 !text-red-400">0</span>
            @elseif($p->isLowStock())<span class="badge !bg-amber-500/20 !text-amber-400">{{ $p->stock }}</span>
            @else<span class="badge !bg-green-500/20 !text-green-400">{{ $p->stock }}</span>@endif
          </td>
          <td class="text-white/80">Rp {{ number_format($p->price,0,',','.') }}</td>
          <td class="text-right whitespace-nowrap">
            <a href="{{ route('warehouse.inventory.edit',$p) }}" class="text-sm text-gold-soft/80 hover:text-gold-light transition-colors">Edit</a>
            <a href="{{ route('warehouse.inventory.restock.form',$p) }}" class="text-sm text-white/50 hover:text-white/80 ml-3 transition-colors">Restock</a>
            <a href="{{ route('warehouse.inventory.categories',$p) }}" class="text-sm text-white/50 hover:text-white/80 ml-3 transition-colors">Categories</a>
          </td>
        </tr>
      @empty <tr><td colspan="6" class="px-4 py-6 text-center text-white/40">No products.</td></tr>
      @endforelse
    </tbody>
  </table>
</div>
<div class="mt-6">{{ $products->links() }}</div>
@endsection
