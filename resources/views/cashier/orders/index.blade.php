@extends('layouts.dashboard')
@section('title','Customer Orders')
@section('heading','Customer Orders')
@section('content')
<div class="card">
  <div class="px-5 py-4 border-b border-white/5 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
    <h2 class="font-semibold text-white/90">Pending & My Orders</h2>
    <form method="GET" class="flex items-center gap-2">
      <label class="text-sm text-white/50">Status</label>
      <select name="status" class="input text-sm" onchange="this.form.submit()">
        @foreach($statuses as $value => $label)
          <option value="{{ $value }}" {{ $status === $value ? 'selected' : '' }}>{{ $label }}</option>
        @endforeach
      </select>
    </form>
  </div>
  @if($orders->isEmpty())
    <p class="p-5 text-sm text-white/40">No orders found.</p>
  @else
    <table class="w-full table">
      <thead><tr><th>Code</th><th>Status</th><th>Channel</th><th>Customer</th><th>When</th><th>Total</th><th></th></tr></thead>
      <tbody>
        @foreach($orders as $o)
          <tr>
            <td class="font-mono text-white/80">{{ $o->code }}</td>
            <td class="text-sm text-white/50">{{ ucfirst($o->status) }}</td>
            <td class="text-sm text-white/50">{{ $o->channel }}</td>
            <td class="text-sm text-white/50">{{ $o->customer_name ?? $o->customer?->name ?? 'Guest' }}</td>
            <td class="text-sm text-white/50">{{ $o->created_at->format('d M Y H:i') }}</td>
            <td class="text-white/80">Rp {{ number_format($o->total,0,',','.') }}</td>
            <td><a class="text-sm text-gold-soft/80 hover:text-gold-light transition-colors" href="{{ route('cashier.orders.show',$o) }}">View</a></td>
          </tr>
        @endforeach
      </tbody>
    </table>
    <div class="p-5">{{ $orders->links() }}</div>
  @endif
</div>
@endsection
