@extends('layouts.dashboard')
@section('title','Warehouse Dashboard')
@section('heading','Warehouse Dashboard')
@section('content')
<div class="grid sm:grid-cols-4 gap-4">
  <div class="card p-5"><p class="text-xs uppercase tracking-wider text-white/50">Total SKU</p>
    <p class="text-2xl font-serif font-bold mt-1 text-gold-light">{{ $totalSku }}</p></div>
  <div class="card p-5"><p class="text-xs uppercase tracking-wider text-white/50">Total Categories</p>
    <p class="text-2xl font-serif font-bold mt-1 text-gold-light">{{ $totalCategories }}</p></div>
  <div class="card p-5"><p class="text-xs uppercase tracking-wider text-white/50">Unread Notifications</p>
    <p class="text-2xl font-serif font-bold mt-1 text-gold-light">{{ $notificationsCount }}</p></div>
  <div class="card p-5"><p class="text-xs uppercase tracking-wider text-white/50">Out of stock</p>
    <p class="text-2xl font-serif font-bold mt-1 text-red-400">{{ $outOfStock->count() }}</p></div>
</div>

<div class="grid lg:grid-cols-2 gap-6 mt-6">
  <div class="card overflow-hidden">
    <div class="px-5 py-4 border-b border-white/5 flex items-center justify-between">
      <h2 class="font-semibold text-white/90">Out of stock</h2>
      <a href="{{ route('warehouse.inventory.index') }}" class="text-sm text-gold-soft/80 hover:text-gold-light transition-colors">Manage</a>
    </div>
    @if($outOfStock->isEmpty())<p class="px-5 py-6 text-white/40 text-sm">Nothing out of stock.</p>
    @else
      <table class="w-full table">
        <thead><tr><th>SKU</th><th>Name</th><th></th></tr></thead><tbody>
        @foreach($outOfStock as $p)
          <tr><td class="font-mono text-xs text-white/50">{{ $p->sku }}</td><td class="text-white/80">{{ $p->name }}</td>
              <td class="text-right"><a class="text-sm text-gold-soft/80 hover:text-gold-light transition-colors" href="{{ route('warehouse.inventory.restock.form',$p) }}">Restock</a></td></tr>
        @endforeach
        </tbody></table>
    @endif
  </div>

  <div class="card overflow-hidden">
    <div class="px-5 py-4 border-b border-white/5"><h2 class="font-semibold text-white/90">Low stock (&lt; {{ \App\Models\Product::LOW_STOCK_THRESHOLD }})</h2></div>
    @if($lowStock->isEmpty())<p class="px-5 py-6 text-white/40 text-sm">No items low.</p>
    @else
      <table class="w-full table">
        <thead><tr><th>SKU</th><th>Name</th><th>Stock</th></tr></thead><tbody>
        @foreach($lowStock as $p)
          <tr><td class="font-mono text-xs text-white/50">{{ $p->sku }}</td><td class="text-white/80">{{ $p->name }}</td>
              <td><span class="badge !bg-amber-500/20 !text-amber-400">{{ $p->stock }}</span></td></tr>
        @endforeach
        </tbody></table>
    @endif
  </div>
</div>
@endsection
