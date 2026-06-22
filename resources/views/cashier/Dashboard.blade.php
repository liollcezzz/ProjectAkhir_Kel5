@extends('layouts.dashboard')
@section('title','Cashier Dashboard')
@section('heading','Cashier Dashboard')
@section('content')
<div class="grid sm:grid-cols-3 gap-4">
  <div class="card p-5"><p class="text-xs uppercase text-ink-500">Sales today</p>
    <p class="text-2xl font-semibold mt-1">Rp {{ number_format($todayTotal,0,',','.') }}</p></div>
  <div class="card p-5"><p class="text-xs uppercase text-ink-500">Transactions today</p>
    <p class="text-2xl font-semibold mt-1">{{ $todayCount }}</p></div>
  <div class="card p-5 flex flex-col justify-between">
    <p class="text-xs uppercase text-ink-500">Quick action</p>
    <a href="{{ route('cashier.pos.index') }}" class="btn-dark mt-2">Open POS →</a>
  </div>
</div>

<div class="card overflow-hidden mt-6">
  <div class="px-5 py-4 border-b border-ink-200 flex justify-between items-center">
    <h2 class="font-semibold">My recent sales</h2>
    <a href="{{ route('cashier.reports.index') }}" class="text-sm underline">View reports</a>
  </div>
  @if($recent->isEmpty())
    <p class="px-5 py-6 text-ink-500 text-sm">No sales yet today.</p>
  @else
    <table class="w-full table">
      <thead><tr><th>Code</th><th>When</th><th>Total</th><th></th></tr></thead>
      <tbody>
        @foreach($recent as $o)
          <tr><td class="font-mono">{{ $o->code }}</td>
              <td class="text-ink-500">{{ $o->created_at->format('d M Y H:i') }}</td>
              <td>Rp {{ number_format($o->total,0,',','.') }}</td>
              <td><a class="text-sm underline" href="{{ route('cashier.pos.receipt',$o) }}">Receipt</a></td></tr>
        @endforeach
      </tbody>
    </table>
  @endif
</div>
@endsection
