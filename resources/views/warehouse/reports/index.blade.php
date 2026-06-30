@extends('layouts.dashboard')
@section('title','Inventory Reports')
@section('heading','Inventory Reports')
@section('content')
<form method="GET" class="card p-4 mb-6 flex flex-wrap gap-3 items-end">
  <div><label class="text-xs uppercase tracking-wider text-white/50">From</label>
    <input type="date" name="from" value="{{ \Illuminate\Support\Carbon::parse($from)->toDateString() }}" class="input mt-1"></div>
  <div><label class="text-xs uppercase tracking-wider text-white/50">To</label>
    <input type="date" name="to" value="{{ \Illuminate\Support\Carbon::parse($to)->toDateString() }}" class="input mt-1"></div>
  <button class="btn-dark">Apply</button>
  <a class="btn-outline" href="{{ route('warehouse.reports.pdf') }}">Inventory PDF</a>
</form>

<div class="grid lg:grid-cols-2 gap-6">
  <div class="card overflow-hidden">
    <div class="px-5 py-4 border-b border-white/5"><h2 class="font-semibold text-white/90">Stock movements</h2></div>
    <table class="w-full table">
      <thead><tr><th>When</th><th>Product</th><th>Type</th><th>Δ</th><th>After</th></tr></thead>
      <tbody>
        @forelse($movements as $m)
          <tr><td class="text-xs text-white/40">{{ $m->created_at->format('d M H:i') }}</td>
              <td class="text-white/80">{{ $m->product->name ?? '—' }}</td>
              <td><span class="badge !bg-white/5 !text-white/60 capitalize">{{ $m->type }}</span></td>
              <td class="{{ $m->quantity_change < 0 ? 'text-red-400' : 'text-green-400' }}">{{ $m->quantity_change }}</td>
              <td class="text-white/70">{{ $m->stock_after }}</td></tr>
        @empty <tr><td colspan="5" class="px-4 py-6 text-center text-white/40">No movements.</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>

  <div class="card overflow-hidden">
    <div class="px-5 py-4 border-b border-white/5"><h2 class="font-semibold text-white/90">Current inventory</h2></div>
    <table class="w-full table">
      <thead><tr><th>SKU</th><th>Name</th><th>Stock</th></tr></thead>
      <tbody>
        @foreach($products as $p)
          <tr><td class="font-mono text-xs text-white/50">{{ $p->sku }}</td><td class="text-white/80">{{ $p->name }}</td>
              <td>
                @if($p->isOutOfStock())<span class="badge !bg-red-500/20 !text-red-400">{{ $p->stock }}</span>
                @elseif($p->isLowStock())<span class="badge !bg-amber-500/20 !text-amber-400">{{ $p->stock }}</span>
                @else<span class="text-white/70">{{ $p->stock }}</span>@endif
              </td></tr>
        @endforeach
      </tbody>
    </table>
  </div>
</div>
@endsection
