@extends('layouts.app')
@section('title','Cart')
@section('content')
<h1 class="text-2xl font-semibold mb-6">Your cart</h1>
@if(empty($rows))
  <p class="text-ink-500">Your cart is empty. <a href="{{ route('catalog.index') }}" class="underline">Shop now</a>.</p>
@else
<form method="POST" action="{{ route('cart.update') }}">
  @csrf @method('PATCH')
  <div class="card overflow-hidden">
    <table class="w-full table">
      <thead><tr><th>Item</th><th>Price</th><th>Qty</th><th>Total</th><th></th></tr></thead>
      <tbody>
        @foreach($rows as $row)
          <tr>
            <td>
              <div class="flex items-center gap-3">
                <img src="{{ $row['p']->imageUrl() }}" class="w-12 h-12 rounded object-cover">
                <div>
                  <a href="{{ route('catalog.show',$row['p']) }}" class="font-medium">{{ $row['p']->name }}</a>
                  <p class="text-xs text-ink-500">{{ $row['p']->sku }}</p>
                </div>
              </div>
            </td>
            <td>Rp {{ number_format($row['p']->price,0,',','.') }}</td>
            <td><input type="number" min="1" max="{{ $row['p']->stock }}" name="items[{{ $row['p']->id }}]" value="{{ $row['qty'] }}" class="input w-20"></td>
            <td>Rp {{ number_format($row['line'],0,',','.') }}</td>
            <td>
              <button form="remove-{{ $row['p']->id }}" class="text-sm text-red-600 hover:underline">Remove</button>
            </td>
          </tr>
        @endforeach
      </tbody>
    </table>
  </div>
  <div class="mt-6 flex flex-wrap items-center justify-between gap-4">
    <button class="btn-outline">Update cart</button>
    <div class="text-right">
      <p class="text-sm text-ink-500">Subtotal</p>
      <p class="text-2xl font-semibold">Rp {{ number_format($total,0,',','.') }}</p>
    </div>
  </div>
</form>
@foreach($rows as $row)
  <form id="remove-{{ $row['p']->id }}" method="POST" action="{{ route('cart.remove',$row['p']) }}">@csrf @method('DELETE')</form>
@endforeach
<div class="mt-6 text-right">
  @auth
    @if(auth()->user()->isCustomer())
      <a href="{{ route('customer.checkout.show') }}" class="btn-dark">Checkout →</a>
    @else
      <p class="text-sm text-ink-500">Sign in as customer to checkout.</p>
    @endif
  @else
    <a href="{{ route('login') }}" class="btn-dark">Sign in to checkout</a>
  @endauth
</div>
@endif
@endsection
