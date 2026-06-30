@extends('layouts.app')
@section('title','Checkout')
@section('content')
<h1 class="text-3xl font-serif font-bold mb-8">Checkout</h1>
<div class="grid md:grid-cols-3 gap-10">
  <form method="POST" action="{{ route('customer.checkout.place') }}" class="md:col-span-2 card p-8 space-y-5 border-l-2 border-l-brand-accent">
    @csrf
    <div><label class="text-xs uppercase tracking-wider text-ink-500 font-medium">Name</label>
      <input name="customer_name" value="{{ old('customer_name', auth()->user()->name) }}" class="input mt-1.5" required></div>
    <div><label class="text-xs uppercase tracking-wider text-ink-500 font-medium">Phone</label>
      <input name="customer_phone" value="{{ old('customer_phone', auth()->user()->phone) }}" class="input mt-1.5" required></div>

    <div><label class="text-xs uppercase tracking-wider text-ink-500 font-medium">Shipping Method</label>
      <select name="shipping_method" id="shipping_method" class="input mt-1.5" required onchange="toggleAddress()">
        @foreach($shippingMethods as $key => $method)
          <option value="{{ $key }}" data-cost="{{ $method['cost'] }}" @selected(old('shipping_method')===$key)>
            {{ $method['label'] }} — Rp {{ number_format($method['cost'],0,',','.') }}
          </option>
        @endforeach
      </select>
      <p class="text-xs text-ink-400 mt-1" id="shipping_desc">{{ $shippingMethods[old('shipping_method','pickup')]['description'] ?? '' }}</p>
    </div>

    <div id="address_field" style="{{ old('shipping_method','pickup') === 'pickup' ? 'display:none' : '' }}">
      <label class="text-xs uppercase tracking-wider text-ink-500 font-medium">Shipping Address</label>
      <textarea name="shipping_address" class="input mt-1.5" rows="3">{{ old('shipping_address', auth()->user()->address) }}</textarea>
    </div>

    <div><label class="text-xs uppercase tracking-wider text-ink-500 font-medium">Notes (optional)</label>
      <textarea name="notes" class="input mt-1.5" rows="3">{{ old('notes') }}</textarea></div>

    <button class="btn-dark w-full">Place order</button>
  </form>
  <aside class="card p-8 h-fit" id="summary">
    <h2 class="font-serif font-bold text-lg mb-4">Order summary</h2>
    <ul class="space-y-3 text-sm">
      @foreach($cart as $id => $qty)
        @php $p = $products[$id] ?? null; if(!$p) continue; @endphp
        <li class="flex justify-between"><span class="text-ink-600">{{ $p->name }} × {{ $qty }}</span><span class="font-medium">Rp {{ number_format($p->price*$qty,0,',','.') }}</span></li>
      @endforeach
    </ul>
    <div class="border-t border-ink-200/50 mt-5 pt-5 space-y-2">
      <div class="flex justify-between text-sm"><span class="text-ink-500">Subtotal</span><span>Rp {{ number_format($total,0,',','.') }}</span></div>
      <div class="flex justify-between text-sm" id="shipping_row">
        <span class="text-ink-500">Shipping</span><span id="shipping_cost">Rp 0</span>
      </div>
      <div class="flex justify-between font-bold border-t border-ink-200/50 pt-3 text-brand mt-1">
        <span>Total</span><span id="total_display" class="text-brand-accent">Rp {{ number_format($total,0,',','.') }}</span>
      </div>
    </div>
  </aside>
</div>

<script>
const methods = @json($shippingMethods);
const subtotal = {{ $total }};

function toggleAddress() {
  const sel = document.getElementById('shipping_method');
  const addr = document.getElementById('address_field');
  const desc = document.getElementById('shipping_desc');
  const cost = document.getElementById('shipping_cost');
  const total = document.getElementById('total_display');
  const method = methods[sel.value];
  desc.textContent = method.description;
  addr.style.display = sel.value === 'pickup' ? 'none' : '';
  cost.textContent = 'Rp ' + Number(method.cost).toLocaleString('id-ID');
  total.textContent = 'Rp ' + (subtotal + method.cost).toLocaleString('id-ID');
}
document.addEventListener('DOMContentLoaded', toggleAddress);
</script>
@endsection
