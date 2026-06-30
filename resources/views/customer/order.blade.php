@extends('layouts.app')
@section('title','Order '.$order->code)
@section('content')
<a href="{{ route('customer.orders.index') }}" class="text-sm text-ink-500 hover:text-brand-accent transition-colors">← Back to orders</a>
<h1 class="text-3xl font-serif font-bold mt-2">Order {{ $order->code }}</h1>
<p class="text-ink-500 text-sm mt-1">{{ $order->created_at->format('d M Y H:i') }} · Status: <strong class="text-brand">{{ ucfirst($order->status) }}</strong></p>

@if($order->payment_status === 'unpaid')
  <div class="mt-6 border border-amber-500/30 bg-amber-500/10 px-5 py-4 flex items-center justify-between">
    <span class="text-amber-400 text-sm">Payment pending — please complete your payment.</span>
    <a href="{{ route('customer.checkout.pay', $order) }}" class="btn-dark text-sm">Pay Now</a>
  </div>
@endif

<div class="mt-4 flex gap-2">
  <a href="{{ route('customer.orders.pdf', $order) }}" class="btn-outline text-sm" target="_blank">
    Print Invoice PDF
  </a>
</div>

@if($order->shipping_method && $order->shipping_method !== 'pickup')
  <div class="mt-6 border border-white/10 px-5 py-4 flex items-center justify-between
    @if($order->shipping_status === 'shipped') !border-blue-500/30 !bg-blue-500/10
    @elseif($order->shipping_status === 'delivered') !border-green-500/30 !bg-green-500/10
    @else !border-white/10 !bg-white/5 @endif">
    <div>
      <span class="text-sm font-medium">
        @php
          $shipLabel = match($order->shipping_status) {
            'shipped' => 'Package in transit',
            'delivered' => 'Package delivered',
            default => 'Processing order',
          };
        @endphp
        {{ $shipLabel }}
      </span>
      @if($order->tracking_number)
        <p class="text-xs text-ink-500 mt-0.5">Tracking: <strong>{{ $order->tracking_number }}</strong></p>
      @endif
    </div>
    @if($order->shipping_status === 'shipped')
      <span class="badge !bg-blue-500/20 !text-blue-400 text-xs">In Transit</span>
    @elseif($order->shipping_status === 'delivered')
      <span class="badge !bg-green-500/20 !text-green-400 text-xs">Delivered</span>
    @else
      <span class="badge !bg-amber-500/20 !text-amber-400 text-xs">Pending</span>
    @endif
  </div>
@endif

<div class="grid md:grid-cols-3 gap-10 mt-8">
  <div class="md:col-span-2 card overflow-hidden">
    <table class="w-full table">
      <thead><tr><th class="text-[11px]">Item</th><th class="text-[11px]">Price</th><th class="text-[11px]">Qty</th><th class="text-[11px]">Total</th></tr></thead>
      <tbody>
        @foreach($order->items as $i)
          <tr><td>{{ $i->product_name }} <span class="text-xs text-ink-400">({{ $i->product_sku }})</span></td>
              <td class="text-ink-600">Rp {{ number_format($i->unit_price,0,',','.') }}</td>
              <td>{{ $i->quantity }}</td>
              <td class="font-medium">Rp {{ number_format($i->line_total,0,',','.') }}</td></tr>
        @endforeach
      </tbody>
    </table>
  </div>
  <aside class="card p-8 h-fit space-y-3 text-sm">
    <div class="flex justify-between"><span class="text-ink-500">Subtotal</span><span>Rp {{ number_format($order->subtotal,0,',','.') }}</span></div>
    @if($order->shipping_cost > 0)
    <div class="flex justify-between"><span class="text-ink-500">Shipping</span><span>Rp {{ number_format($order->shipping_cost,0,',','.') }}</span></div>
    @endif
    <div class="flex justify-between font-bold border-t border-ink-200/50 pt-3 text-brand"><span>Total</span><span class="text-brand-accent">Rp {{ number_format($order->total,0,',','.') }}</span></div>
    <div class="pt-4 text-ink-500 space-y-1.5 border-t border-ink-200/50">
      <p class="font-medium text-brand">{{ $order->customer_name }}</p>
      <p>{{ $order->customer_phone }}</p>
      @if($order->shipping_method)
        @php $sm = config('shipping.methods.'.$order->shipping_method); @endphp
        <p class="mt-3"><strong class="text-brand">Shipping:</strong> {{ $sm['label'] ?? $order->shipping_method }}</p>
        @if($order->shipping_address)
          <p class="text-xs text-ink-400 mt-1">{{ $order->shipping_address }}</p>
        @endif
        @if($order->tracking_number)
          <p><strong class="text-brand">Tracking:</strong> {{ $order->tracking_number }}</p>
        @endif
      @endif
    </div>
    @if($order->payment_status === 'paid')
      <div class="pt-4 border-t border-white/10">
        <span class="badge !bg-green-500/20 !text-green-400">Paid</span>
        @if($order->payment_method)<p class="text-xs mt-1.5 text-ink-400">{{ str_replace('_',' ',ucfirst($order->payment_method)) }}</p>@endif
      </div>
    @endif
  </aside>
</div>
@endsection
