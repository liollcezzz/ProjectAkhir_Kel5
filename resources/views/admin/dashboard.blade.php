@extends('layouts.dashboard')
@section('title','Admin Dashboard')
@section('heading','Admin Dashboard')
@section('content')
<div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-4">
  <div class="card p-5"><p class="text-xs uppercase tracking-wider text-white/50">Total Sales</p>
    <p class="text-2xl font-serif font-bold mt-1 text-gold-light">Rp {{ number_format($totalSales,0,',','.') }}</p></div>
  <div class="card p-5"><p class="text-xs uppercase tracking-wider text-white/50">Sales Today</p>
    <p class="text-2xl font-serif font-bold mt-1 text-gold-light">Rp {{ number_format($todaySales,0,',','.') }}</p></div>
  <div class="card p-5"><p class="text-xs uppercase tracking-wider text-white/50">Total Staff</p>
    <p class="text-2xl font-serif font-bold mt-1 text-gold-light">{{ $totalStaff }}</p></div>
  <div class="card p-5"><p class="text-xs uppercase tracking-wider text-white/50">Unread Notifications</p>
    <p class="text-2xl font-serif font-bold mt-1 text-gold-light">{{ $notificationsCount }}</p></div>
  <div class="card p-5"><p class="text-xs uppercase tracking-wider text-white/50">Low / Out of Stock</p>
    <p class="text-2xl font-serif font-bold mt-1 text-gold-light">{{ $lowStock->count() }}</p></div>
</div>

<div class="grid lg:grid-cols-2 gap-6 mt-6">
  <div class="card overflow-hidden">
    <div class="px-5 py-4 border-b border-white/5 flex justify-between items-center">
      <h2 class="font-semibold text-white/90">Low stock warnings (&lt; {{ \App\Models\Product::LOW_STOCK_THRESHOLD }})</h2>
    </div>
    @if($lowStock->isEmpty())
      <p class="px-5 py-6 text-white/40 text-sm">All products are well stocked.</p>
    @else
      <table class="w-full table">
        <thead><tr><th>Product</th><th>SKU</th><th>Stock</th></tr></thead>
        <tbody>
          @foreach($lowStock as $p)
            <tr><td class="text-white/80">{{ $p->name }}</td><td class="font-mono text-xs text-white/50">{{ $p->sku }}</td>
                <td><span class="badge {{ $p->stock==0 ? '!bg-red-500/20 !text-red-400' : '!bg-amber-500/20 !text-amber-400' }}">{{ $p->stock }}</span></td></tr>
          @endforeach
        </tbody>
      </table>
    @endif
  </div>

  <div class="card overflow-hidden">
    <div class="px-5 py-4 border-b border-white/5"><h2 class="font-semibold text-white/90">Recent activity</h2></div>
    @if($recent->isEmpty())
      <p class="px-5 py-6 text-white/40 text-sm">No orders yet.</p>
    @else
      <table class="w-full table">
        <thead><tr><th>Order</th><th>Channel</th><th>Total</th><th>When</th></tr></thead>
        <tbody>
          @foreach($recent as $o)
            <tr><td class="font-mono text-xs text-white/70">{{ $o->code }}</td>
                <td><span class="badge !bg-white/5 !text-white/60">{{ strtoupper($o->channel) }}</span></td>
                <td class="text-white/80">Rp {{ number_format($o->total,0,',','.') }}</td>
                <td class="text-white/40">{{ $o->created_at->diffForHumans() }}</td></tr>
          @endforeach
        </tbody>
      </table>
    @endif
  </div>
</div>
@endsection
