@extends('layouts.app')
@section('title','Checkout')
@section('content')
<h1 class="text-2xl font-semibold mb-6">Checkout</h1>
<div class="grid md:grid-cols-3 gap-8">
  <form method="POST" action="{{ route('customer.checkout.place') }}" class="md:col-span-2 card p-6 space-y-4">
    @csrf
    <div><label class="text-sm font-medium">Name</label>
      <input name="customer_name" value="{{ old('customer_name', auth()->user()->name) }}" class="input mt-1" required></div>
    <div><label class="text-sm font-medium">Phone</label>
      <input name="customer_phone" value="{{ old('customer_phone', auth()->user()->phone) }}" class="input mt-1" required></div>
    <div><label class="text-sm font-medium">Notes (optional)</label>
      <textarea name="notes" class="input mt-1" rows="3">{{ old('notes') }}</textarea></div>
    <button class="btn-dark">Place order</button>
    <p class="text-xs text-ink-500">This demo simulates payment. Order status starts as <em>pending</em>.</p>
  </form>
  <aside class="card p-6 h-fit">
    <h2 class="font-semibold mb-3">Order summary</h2>
    <ul class="space-y-2 text-sm">
      @foreach($cart as $id => $qty)
        @php $p = $products[$id] ?? null; if(!$p) continue; @endphp
        <li class="flex justify-between"><span>{{ $p->name }} × {{ $qty }}</span><span>Rp {{ number_format($p->price*$qty,0,',','.') }}</span></li>
      @endforeach
    </ul>
    <div class="border-t border-ink-200 mt-4 pt-4 flex justify-between font-semibold">
      <span>Total</span><span>Rp {{ number_format($total,0,',','.') }}</span>
    </div>
  </aside>
</div>
@endsection
