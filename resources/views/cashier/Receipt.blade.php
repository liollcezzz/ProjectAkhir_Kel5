@extends('layouts.dashboard')
@section('title','Receipt')
@section('heading','Receipt')
@section('content')
<div class="max-w-md mx-auto card p-6" id="receipt">
  <div class="text-center">
    <p class="font-bold text-lg">AKSESORIA</p>
    <p class="text-xs text-ink-500">In-store receipt</p>
  </div>
  <div class="text-xs mt-4 grid grid-cols-2 gap-1">
    <p>Code:</p><p class="font-mono text-right">{{ $order->code }}</p>
    <p>Date:</p><p class="text-right">{{ $order->created_at->format('d M Y H:i') }}</p>
    <p>Cashier:</p><p class="text-right">{{ $order->cashier->name ?? '-' }}</p>
    @if($order->customer_name)<p>Customer:</p><p class="text-right">{{ $order->customer_name }}</p>@endif
  </div>
  <div class="border-t border-dashed border-ink-300 my-4"></div>
  <table class="w-full text-sm">
    @foreach($order->items as $i)
      <tr><td>{{ $i->product_name }}<br><span class="text-xs text-ink-500">{{ $i->quantity }} × Rp {{ number_format($i->unit_price,0,',','.') }}</span></td>
          <td class="text-right align-top">Rp {{ number_format($i->line_total,0,',','.') }}</td></tr>
    @endforeach
  </table>
  <div class="border-t border-dashed border-ink-300 my-4"></div>
  <div class="text-sm space-y-1">
    <div class="flex justify-between font-semibold"><span>Total</span><span>Rp {{ number_format($order->total,0,',','.') }}</span></div>
    <div class="flex justify-between"><span>Paid</span><span>Rp {{ number_format($order->amount_paid,0,',','.') }}</span></div>
    <div class="flex justify-between"><span>Change</span><span>Rp {{ number_format($order->change_due,0,',','.') }}</span></div>
  </div>
  <p class="text-center text-xs text-ink-500 mt-6">— Thank you —</p>
</div>
<div class="text-center mt-6 print:hidden flex gap-3 justify-center">
  <button onclick="window.print()" class="btn-dark">Print</button>
  <a href="{{ route('cashier.pos.index') }}" class="btn-outline">New sale</a>
</div>
@endsection
