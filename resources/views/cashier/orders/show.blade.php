@extends('layouts.dashboard')
@section('title','Order')
@section('heading','Order')
@section('content')
<div class="card">
  <div class="px-5 py-4 border-b border-white/5 flex items-center justify-between">
    <h2 class="font-semibold text-white/90">Order {{ $order->code }}</h2>
    <div class="text-sm text-white/40">{{ $order->created_at->format('d M Y H:i') }}</div>
  </div>
  <div class="p-5 space-y-3 text-white/80">
    <p><strong class="text-white/90">Customer:</strong> {{ $order->customer_name ?? $order->customer?->name ?? 'Guest' }}</p>
    <p><strong class="text-white/90">Phone:</strong> {{ $order->customer_phone ?? '-' }}</p>
    <p><strong class="text-white/90">Status:</strong> {{ ucfirst($order->status) }}</p>
    <p><strong class="text-white/90">Payment:</strong>
      @if($order->payment_status === 'paid')
        <span class="badge !bg-green-500/20 !text-green-400">Paid</span>
        @if($order->payment_method) <span class="text-xs text-white/50">({{ $order->payment_method }})</span> @endif
      @else
        <span class="badge !bg-amber-500/20 !text-amber-400">{{ ucfirst($order->payment_status) }}</span>
      @endif
    </p>
    @if($order->shipping_method)
      @php $sm = config('shipping.methods.'.$order->shipping_method); @endphp
      <p><strong class="text-white/90">Shipping:</strong> {{ $sm['label'] ?? $order->shipping_method }} @if($order->shipping_cost > 0)(Rp {{ number_format($order->shipping_cost,0,',','.') }})@endif</p>
      @if($order->shipping_address)<p><strong class="text-white/90">Address:</strong> {{ $order->shipping_address }}</p>@endif
      @if($order->tracking_number)<p><strong class="text-white/90">Tracking:</strong> {{ $order->tracking_number }}</p>@endif
    @endif

    <p class="mt-2"><strong class="text-white/90">Items</strong></p>
    <table class="w-full table mt-2"><thead><tr><th>Product</th><th>Qty</th><th>Price</th><th>Total</th></tr></thead>
      <tbody>
        @foreach($order->items as $it)
          <tr>
            <td class="text-white/80">{{ $it->product_name }}</td>
            <td class="text-white/70">{{ $it->quantity }}</td>
            <td class="text-white/70">Rp {{ number_format($it->unit_price,0,',','.') }}</td>
            <td class="text-white/80">Rp {{ number_format($it->line_total,0,',','.') }}</td>
          </tr>
        @endforeach
      </tbody>
    </table>
    <div class="mt-4 text-right">
      <p class="text-lg font-semibold text-gold-light">Total: Rp {{ number_format($order->total,0,',','.') }}</p>
    </div>
    <div class="mt-4 flex gap-2 flex-wrap">
      @if(is_null($order->cashier_id) && $order->status !== 'cancelled')
        <form method="POST" action="{{ route('cashier.orders.confirm', $order) }}">
          @csrf
          <button class="btn-dark">Claim & Confirm</button>
        </form>
      @elseif($order->cashier_id === auth()->id())
        <p class="text-sm text-white/40">You have claimed this order.</p>
      @elseif($order->cashier_id)
        <p class="text-sm text-white/40">Assigned to another cashier.</p>
      @endif
      <a href="{{ route('cashier.orders.pdf', $order) }}" class="btn-outline text-sm" target="_blank">Print Invoice</a>
    </div>
  </div>
</div>
@endsection
