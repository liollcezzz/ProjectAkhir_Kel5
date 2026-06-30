@extends('layouts.dashboard')
@section('title','Receipt')
@section('heading','Receipt')
@section('content')
<div class="max-w-md mx-auto card p-6" id="receipt">
  <div class="text-center">
    <p class="font-bold text-lg text-gold-light">AKSESORIA</p>
    <p class="text-xs text-white/50">In-store receipt</p>
  </div>
  <div class="text-xs mt-4 grid grid-cols-2 gap-1 text-white/70">
    <p class="text-white/50">Code:</p><p class="font-mono text-right text-white/90">{{ $order->code }}</p>
    <p class="text-white/50">Date:</p><p class="text-right text-white/90">{{ $order->created_at->format('d M Y H:i') }}</p>
    <p class="text-white/50">Cashier:</p><p class="text-right text-white/90">{{ $order->cashier->name ?? '-' }}</p>
    @if($order->customer_name)<p class="text-white/50">Customer:</p><p class="text-right text-white/90">{{ $order->customer_name }}</p>@endif
  </div>
  <div class="border-t border-dashed border-white/10 my-4"></div>
  <table class="w-full text-sm">
    @foreach($order->items as $i)
      <tr><td class="text-white/80">{{ $i->product_name }}<br><span class="text-xs text-white/40">{{ $i->quantity }} × Rp {{ number_format($i->unit_price,0,',','.') }}</span></td>
          <td class="text-right align-top text-white/90">Rp {{ number_format($i->line_total,0,',','.') }}</td></tr>
    @endforeach
  </table>
  <div class="border-t border-dashed border-white/10 my-4"></div>
  <div class="text-sm space-y-1 text-white/80">
    <div class="flex justify-between font-semibold text-gold-light"><span>Total</span><span>Rp {{ number_format($order->total,0,',','.') }}</span></div>
    <div class="flex justify-between"><span class="text-white/50">Paid</span><span>Rp {{ number_format($order->amount_paid,0,',','.') }}</span></div>
    <div class="flex justify-between"><span class="text-white/50">Change</span><span>Rp {{ number_format($order->change_due,0,',','.') }}</span></div>
  </div>
  <p class="text-center text-xs text-white/40 mt-6">— Thank you —</p>
</div>
<div class="text-center mt-6 print:hidden flex gap-3 justify-center">
  <button onclick="window.print()" class="btn-dark">Print</button>
  <a href="{{ route('cashier.pos.index') }}" class="btn-outline">New sale</a>
</div>
@endsection
