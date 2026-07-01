@extends('layouts.dashboard')
@section('title','Sales Reports')
@section('heading','Sales Reports')
@section('content')
<form method="GET" class="card p-4 mb-6 flex flex-wrap gap-3 items-end">
  <div><label class="text-xs uppercase tracking-wider text-white/50">From</label>
    <input type="date" name="from" value="{{ \Illuminate\Support\Carbon::parse($from)->toDateString() }}" class="input mt-1"></div>
  <div><label class="text-xs uppercase tracking-wider text-white/50">To</label>
    <input type="date" name="to" value="{{ \Illuminate\Support\Carbon::parse($to)->toDateString() }}" class="input mt-1"></div>
  <button class="btn-dark">Apply</button>
  <a class="btn-outline" href="{{ route('cashier.reports.pdf', request()->only('from','to')) }}">Download PDF</a>
</form>

<div class="card p-5 mb-6"><p class="text-xs uppercase tracking-wider text-white/50">Total</p>
  <p class="text-2xl font-serif font-bold text-gold-light mt-1">Rp {{ number_format($total,0,',','.') }}</p>
  <p class="text-sm text-white/40 mt-1">{{ $orders->count() }} transactions</p></div>

<div class="card overflow-hidden">
  <table class="w-full table">
    <thead><tr><th>Code</th><th>Date</th><th>Items</th><th>Total</th></tr></thead>
    <tbody>
      @forelse($orders as $o)
        <tr><td class="font-mono text-white/80">{{ $o->code }}</td>
            <td class="text-white/50">{{ $o->created_at->format('d M Y H:i') }}</td>
            <td class="text-white/70">{{ $o->items->sum('quantity') }}</td>
            <td class="text-white/80">Rp {{ number_format($o->total,0,',','.') }}</td></tr>
      @empty <tr><td colspan="4" class="px-4 py-6 text-center text-white/40">No sales in range.</td></tr>
      @endforelse
    </tbody>
  </table>
</div>
@endsection
