@extends('layouts.dashboard')
@section('title','Cashier Dashboard')
@section('heading','Cashier Dashboard')
@section('content')
<div class="grid sm:grid-cols-3 gap-4">
  <div class="card p-5"><p class="text-xs uppercase tracking-wider text-white/50">Sales today</p>
    <p class="text-2xl font-serif font-bold mt-1 text-gold-light">Rp {{ number_format($todayTotal,0,',','.') }}</p></div>
  <div class="card p-5"><p class="text-xs uppercase tracking-wider text-white/50">Transactions today</p>
    <p class="text-2xl font-serif font-bold mt-1 text-gold-light">{{ $todayCount }}</p></div>
  <div class="card p-5 flex flex-col justify-between">
    <p class="text-xs uppercase tracking-wider text-white/50">Pending customer orders</p>
    <div class="mt-2 text-2xl font-serif font-bold text-gold-light">{{ $pendingOrders }}</div>
    <a href="{{ route('cashier.orders.index') }}" class="btn-dark mt-4">Review orders →</a>
  </div>
</div>

<div class="card overflow-hidden mt-6">
  <div class="px-5 py-4 border-b border-white/5 flex justify-between items-center">
    <h2 class="font-semibold text-white/90">My recent sales</h2>
    <a href="{{ route('cashier.reports.index') }}" class="text-sm text-gold-soft/80 hover:text-gold-light transition-colors">View reports</a>
  </div>
  @if($recent->isEmpty())
    <p class="px-5 py-6 text-white/40 text-sm">No sales yet today.</p>
  @else
    <table class="w-full table">
      <thead><tr><th>Code</th><th>When</th><th>Total</th><th></th></tr></thead>
      <tbody>
        @foreach($recent as $o)
          <tr><td class="font-mono text-white/80">{{ $o->code }}</td>
              <td class="text-white/40">{{ $o->created_at->format('d M Y H:i') }}</td>
              <td class="text-white/80">Rp {{ number_format($o->total,0,',','.') }}</td>
              <td><a class="text-sm text-gold-soft/80 hover:text-gold-light transition-colors" href="{{ route('cashier.pos.receipt',$o) }}">Receipt</a></td></tr>
        @endforeach
      </tbody>
    </table>
  @endif
</div>
@endsection
