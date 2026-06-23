@extends('layouts.dashboard')
@section('title','Inventory Reports')
@section('heading','Inventory Reports')
@section('content')
<form method="GET" class="card p-4 mb-6 flex flex-wrap gap-3 items-end">
  <div><label class="text-xs uppercase text-ink-500">From</label>
    <input type="date" name="from" value="{{ \Illuminate\Support\Carbon::parse($from)->toDateString() }}" class="input mt-1"></div>
  <div><label class="text-xs uppercase text-ink-500">To</label>
    <input type="date" name="to" value="{{ \Illuminate\Support\Carbon::parse($to)->toDateString() }}" class="input mt-1"></div>
  <button class="btn-dark">Apply</button>
  <a class="btn-outline" href="{{ route('warehouse.reports.pdf') }}">Inventory PDF</a>
</form>

<div class="grid lg:grid-cols-2 gap-6">
  <div class="card overflow-hidden">
    <div class="px-5 py-4 border-b border-ink-200"><h2 class="font-semibold">Stock movements</h2></div>
    <table class="w-full table">
      <thead><tr><th>When</th><th>Product</th><th>Type</th><th>Δ</th><th>After</th></tr></thead>
      <tbody>
        @forelse($movements as $m)
          <tr><td class="text-xs text-ink-500">{{ $m->created_at->format('d M H:i') }}</td>
              <td>{{ $m->product->name ?? '—' }}</td>
              <td><span class="badge bg-ink-100 capitalize">{{ $m->type }}</span></td>
              <td class="{{ $m->quantity_change < 0 ? 'text-red-600' : 'text-green-700' }}">{{ $m->quantity_change }}</td>
              <td>{{ $m->stock_after }}</td></tr>
        @empty <tr><td colspan="5" class="px-4 py-6 text-center text-ink-500">No movements.</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>

  <div class="card overflow-hidden">
    <div class="px-5 py-4 border-b border-ink-200"><h2 class="font-semibold">Current inventory</h2></div>
    <table class="w-full table">
      <thead><tr><th>SKU</th><th>Name</th><th>Stock</th></tr></thead>
      <tbody>
        @foreach($products as $p)
          <tr><td class="font-mono text-xs">{{ $p->sku }}</td><td>{{ $p->name }}</td>
              <td>
                @if($p->isOutOfStock())<span class="badge bg-red-50 text-red-700">{{ $p->stock }}</span>
                @elseif($p->isLowStock())<span class="badge bg-amber-50 text-amber-700">{{ $p->stock }}</span>
                @else{{ $p->stock }}@endif
              </td></tr>
        @endforeach
      </tbody>
    </table>
  </div>
</div>
@endsection
