@extends('layouts.app')
@section('title','My Orders')
@section('content')
<h1 class="text-3xl font-serif font-bold mb-8">My Orders</h1>
@if($orders->isEmpty())
  <p class="text-ink-500 py-20 text-center">No orders yet.</p>
@else
<div class="card overflow-hidden">
  <table class="w-full table">
    <thead><tr><th class="text-[11px]">Code</th><th class="text-[11px]">Date</th><th class="text-[11px]">Items</th><th class="text-[11px]">Total</th><th class="text-[11px]">Shipping</th><th class="text-[11px]">Status</th><th class="text-[11px]">Payment</th><th class="text-[11px]"></th></tr></thead>
    <tbody>
      @foreach($orders as $o)
        <tr>
          <td class="font-mono text-brand">{{ $o->code }}</td>
          <td class="text-ink-500">{{ $o->created_at->format('d M Y') }}</td>
          <td>{{ $o->items->sum('quantity') }}</td>
          <td class="font-medium">Rp {{ number_format($o->total,0,',','.') }}</td>
          <td>
            @if($o->shipping_method && $o->shipping_method !== 'pickup')
              <span class="badge text-xs
                @if($o->shipping_status === 'shipped') !bg-blue-500/20 !text-blue-400
                @elseif($o->shipping_status === 'delivered') !bg-green-500/20 !text-green-400
                @else !bg-white/10 !text-white/60 @endif">
                {{ match($o->shipping_status) { 'shipped' => 'In Transit', 'delivered' => 'Delivered', default => 'Pending' } }}
              </span>
            @else
              <span class="text-xs text-ink-400">Pickup</span>
            @endif
          </td>
          <td><span class="badge !bg-white/10 !text-white/60">{{ ucfirst($o->status) }}</span></td>
          <td>
            @if($o->payment_status === 'paid')
              <span class="badge !bg-green-500/20 !text-green-400">Paid</span>
            @else
              <span class="badge !bg-amber-500/20 !text-amber-400">{{ ucfirst($o->payment_status) }}</span>
            @endif
          </td>
          <td><a class="text-sm text-brand-accent hover:underline font-medium" href="{{ route('customer.orders.show',$o) }}">View</a></td>
        </tr>
      @endforeach
    </tbody>
  </table>
</div>
<div class="mt-8">{{ $orders->links('vendor.pagination.shop') }}</div>
@endif
@endsection
