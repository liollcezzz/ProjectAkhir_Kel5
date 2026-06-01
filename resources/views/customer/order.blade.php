@extends('layouts.app')
@section('title','Order '.$order->code)
@section('content')
<a href="{{ route('customer.orders.index') }}" class="text-sm text-ink-500">← Back to orders</a>
<h1 class="text-2xl font-semibold mt-2">Order {{ $order->code }}</h1>
<p class="text-ink-500 text-sm">{{ $order->created_at->format('d M Y H:i') }} · Status: <strong>{{ ucfirst($order->status) }}</strong></p>

<div class="grid md:grid-cols-3 gap-8 mt-6">
  <div class="md:col-span-2 card overflow-hidden">
    <table class="w-full table">
      <thead><tr><th>Item</th><th>Price</th><th>Qty</th><th>Total</th></tr></thead>
      <tbody>
        @foreach($order->items as $i)
          <tr><td>{{ $i->product_name }} <span class="text-xs text-ink-500">({{ $i->product_sku }})</span></td>
              <td>Rp {{ number_format($i->unit_price,0,',','.') }}</td>
              <td>{{ $i->quantity }}</td>
              <td>Rp {{ number_format($i->line_total,0,',','.') }}</td></tr>
        @endforeach
      </tbody>
    </table>
  </div>
  <aside class="card p-6 h-fit space-y-2 text-sm">
    <div class="flex justify-between"><span>Subtotal</span><span>Rp {{ number_format($order->subtotal,0,',','.') }}</span></div>
    <div class="flex justify-between font-semibold border-t border-ink-200 pt-2"><span>Total</span><span>Rp {{ number_format($order->total,0,',','.') }}</span></div>
    <div class="pt-2 text-ink-500"><p>{{ $order->customer_name }}</p><p>{{ $order->customer_phone }}</p></div>
  </aside>
</div>
@endsection
