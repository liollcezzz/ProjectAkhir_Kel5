@extends('layouts.app')
@section('title','Cart')
@section('content')
<h1 class="text-3xl font-serif font-bold mb-8">Your cart</h1>
@if(empty($rows))
  <p class="text-ink-500 py-20 text-center">Your cart is empty. <a href="{{ route('catalog.index') }}" class="text-brand-accent hover:underline font-medium">Shop now</a>.</p>
@else
<form method="POST" action="{{ route('cart.update') }}">
  @csrf @method('PATCH')
  <div class="card overflow-hidden">
    <table class="w-full table">
      <thead><tr><th class="text-[11px]">Item</th><th class="text-[11px]">Price</th><th class="text-[11px]">Qty</th><th class="text-[11px]">Total</th><th class="text-[11px]"></th></tr></thead>
      <tbody>
        @foreach($rows as $row)
          <tr>
            <td>
              <div class="flex items-center gap-3">
                <img src="{{ $row['p']->imageUrl() }}" class="w-14 h-14 object-cover">
                <div>
                  <a href="{{ route('catalog.show',$row['p']) }}" class="font-medium text-brand hover:text-brand-accent transition-colors">{{ $row['p']->name }}</a>
                  <p class="text-xs text-ink-400">{{ $row['p']->sku }}</p>
                </div>
              </div>
            </td>
            <td class="text-ink-600">Rp {{ number_format($row['p']->price,0,',','.') }}</td>
            <td><input type="number" min="1" max="{{ $row['p']->stock }}" name="items[{{ $row['p']->id }}]" value="{{ $row['qty'] }}" class="input w-20 text-center"></td>
            <td class="font-medium">Rp {{ number_format($row['line'],0,',','.') }}</td>
            <td>
              <button form="remove-{{ $row['p']->id }}" class="text-sm text-red-500 hover:text-red-700 transition-colors">Remove</button>
            </td>
          </tr>
        @endforeach
      </tbody>
    </table>
  </div>
  <div class="mt-8 flex flex-wrap items-center justify-between gap-4">
    <button class="btn-outline">Update cart</button>
    <div class="text-right">
      <p class="text-xs uppercase tracking-wider text-ink-400 font-medium">Subtotal</p>
      <p class="text-3xl font-serif font-bold text-brand mt-1">Rp {{ number_format($total,0,',','.') }}</p>
    </div>
  </div>
</form>
@foreach($rows as $row)
  <form id="remove-{{ $row['p']->id }}" method="POST" action="{{ route('cart.remove',$row['p']) }}">@csrf @method('DELETE')</form>
@endforeach
<div class="mt-8 text-right border-t border-ink-200/50 pt-6">
  @auth
    @if(auth()->user()->isCustomer())
      <a href="{{ route('customer.checkout.show') }}" class="btn-dark">Proceed to checkout →</a>
    @else
      <p class="text-sm text-ink-500">Sign in as customer to checkout.</p>
    @endif
  @else
    <a href="{{ route('login') }}" class="btn-dark">Sign in to checkout</a>
  @endauth
</div>
@endif
@endsection
