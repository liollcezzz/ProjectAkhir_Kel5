@extends('layouts.app')
@section('title','Payment')
@push('head')
@if($snapToken)
<script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ $clientKey }}"></script>
@endif
@endpush
@section('content')
<div class="max-w-lg mx-auto space-y-6 py-8">
  <div class="card p-10 text-center">
    <h1 class="text-3xl font-serif font-bold mb-2">Payment</h1>
    <p class="text-ink-500 mb-6">Order <strong class="text-brand">{{ $order->code }}</strong></p>
    <p class="text-4xl font-serif font-bold text-brand-accent mb-8">Rp {{ number_format($order->total,0,',','.') }}</p>

    @if($snapToken)
      <button id="pay-btn" class="btn-dark w-full text-center">Pay with Midtrans</button>
      <p class="text-xs text-ink-400 mt-4">Secure payment via Midtrans (CC, Bank Transfer, E-Wallet, QRIS).</p>
    @else
      <div class="border border-ink-200/50 p-6 mb-6">
        <h2 class="font-serif font-bold mb-4 text-left">Manual Bank Transfer</h2>
        <div class="space-y-3 text-sm text-left">
          <div class="bg-white/5 p-4">
            <p class="text-xs uppercase tracking-wider text-white/50 font-medium">BCA</p>
            <p class="font-mono font-bold text-xl text-brand mt-1">123 456 7890</p>
            <p class="text-ink-500 text-xs mt-0.5">a.n. Aksesoria Store</p>
          </div>
          <div class="bg-white/5 p-4">
            <p class="text-xs uppercase tracking-wider text-white/50 font-medium">Mandiri</p>
            <p class="font-mono font-bold text-xl text-brand mt-1">1234 5678 9012 3456</p>
            <p class="text-ink-500 text-xs mt-0.5">a.n. Aksesoria Store</p>
          </div>
          <p class="text-xs text-ink-400 mt-2">Transfer sesuai total. Konfirmasi setelah transfer.</p>
        </div>
      </div>
      <form method="POST" action="{{ route('customer.checkout.pay.confirm', $order) }}">
        @csrf
        <button class="btn-dark w-full">I've Transferred — Confirm Payment</button>
      </form>
      <p class="text-xs text-ink-400 mt-4">Demo: klik tombol untuk simulasi pembayaran.</p>
    @endif
  </div>

  <a href="{{ route('customer.orders.show', $order) }}" class="block text-center text-sm text-ink-500 hover:text-brand-accent transition-colors">← Back to order</a>
</div>

@if($snapToken)
<script>
document.getElementById('pay-btn').addEventListener('click', function () {
  window.snap.pay('{{ $snapToken }}', {
    onSuccess: function(result) { window.location.href = '{{ route('payment.finish', ['order_id' => $order->id]) }}'; },
    onPending: function(result) { window.location.href = '{{ route('payment.unfinish', ['order_id' => $order->id]) }}'; },
    onError: function(result)   { window.location.href = '{{ route('payment.error', ['order_id' => $order->id]) }}'; },
    onClose: function()         { window.location.href = '{{ route('payment.unfinish', ['order_id' => $order->id]) }}'; },
  });
});
</script>
@endif
@endsection

