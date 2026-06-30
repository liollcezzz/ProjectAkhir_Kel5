@extends('layouts.dashboard')
@section('title','Ship Order')
@section('heading','Ship Order')
@section('content')
<div class="max-w-2xl">
  <div class="card p-6">
    <h2 class="font-semibold text-white/90 mb-4">Ship Order {{ $order->code }}</h2>

    <div class="grid md:grid-cols-2 gap-4 text-sm text-white/70 mb-6">
      <div>
        <p><strong class="text-white/90">Customer:</strong> {{ $order->customer_name }}</p>
        <p><strong class="text-white/90">Phone:</strong> {{ $order->customer_phone }}</p>
        <p><strong class="text-white/90">Status:</strong> {{ ucfirst($order->status) }}</p>
      </div>
      <div>
        @php $sm = config('shipping.methods.'.$order->shipping_method); @endphp
        <p><strong class="text-white/90">Shipping:</strong> {{ $sm['label'] ?? $order->shipping_method }}</p>
        @if($order->shipping_cost > 0)
          <p><strong class="text-white/90">Cost:</strong> Rp {{ number_format($order->shipping_cost,0,',','.') }}</p>
        @endif
        @if($order->shipping_address)
          <p><strong class="text-white/90">Address:</strong><br>{{ $order->shipping_address }}</p>
        @endif
      </div>
    </div>

    <h3 class="font-semibold text-sm text-white/80 mb-2">Items</h3>
    <table class="w-full table text-sm mb-6">
      <thead><tr><th>Product</th><th>SKU</th><th>Qty</th></tr></thead>
      <tbody>
        @foreach($order->items as $item)
          <tr>
            <td class="text-white/80">{{ $item->product_name }}</td>
            <td class="font-mono text-xs text-white/50">{{ $item->product_sku }}</td>
            <td class="text-white/70">{{ $item->quantity }}</td>
          </tr>
        @endforeach
      </tbody>
    </table>

    <form method="POST" action="{{ route('warehouse.shipping.store', $order) }}" class="space-y-4">
      @csrf
      <div>
        <label class="text-sm font-medium text-white/80">Tracking Number <span class="text-red-400">*</span></label>
        <input name="tracking_number" class="input mt-1" placeholder="Contoh: JNE123456789" required>
        <p class="text-xs text-white/40 mt-1">Masukkan nomor resi dari ekspedisi pengiriman.</p>
      </div>
      <div class="flex gap-2">
        <button class="btn-dark">Mark as Shipped</button>
        <a href="{{ route('warehouse.shipping.index') }}" class="btn-outline">Cancel</a>
      </div>
    </form>
  </div>
</div>
@endsection
