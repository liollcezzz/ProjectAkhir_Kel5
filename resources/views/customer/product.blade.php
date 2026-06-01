@extends('layouts.app')
@section('title',$product->name)
@section('content')
<div class="grid md:grid-cols-2 gap-10">
  <div class="aspect-square bg-ink-100 rounded-lg overflow-hidden">
    <img src="{{ $product->imageUrl() }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
  </div>
  <div>
    <p class="text-xs uppercase text-ink-500">{{ $product->categories->pluck('name')->join(' · ') }}</p>
    <h1 class="text-3xl font-semibold mt-2">{{ $product->name }}</h1>
    <p class="text-2xl mt-3">Rp {{ number_format($product->price,0,',','.') }}</p>
    <p class="mt-6 text-ink-700 leading-relaxed">{{ $product->description }}</p>
    @if($product->isOutOfStock())
      <p class="mt-6 text-red-600">Currently out of stock.</p>
    @else
      <form method="POST" action="{{ route('cart.add',$product) }}" class="mt-6 flex gap-3">
        @csrf
        <input type="number" name="qty" value="1" min="1" max="{{ $product->stock }}" class="input w-24">
        <button class="btn-dark">Add to cart</button>
      </form>
      @if($product->isLowStock())<p class="text-amber-700 text-sm mt-2">Only {{ $product->stock }} left in stock</p>@endif
    @endif
    <dl class="mt-8 text-sm border-t border-ink-200 pt-4 space-y-2 text-ink-600">
      <div class="flex justify-between"><dt>SKU</dt><dd>{{ $product->sku }}</dd></div>
      <div class="flex justify-between"><dt>Gender</dt><dd class="capitalize">{{ $product->gender }}</dd></div>
    </dl>
  </div>
</div>

@if($related->isNotEmpty())
<section class="mt-20">
  <h2 class="text-lg font-semibold mb-4">You may also like</h2>
  <div class="grid grid-cols-2 sm:grid-cols-4 gap-6">
    @foreach($related as $r)
      <a href="{{ route('catalog.show',$r) }}">
        <div class="aspect-square bg-ink-100 rounded-lg overflow-hidden">
          <img src="{{ $r->imageUrl() }}" alt="{{ $r->name }}" class="w-full h-full object-cover">
        </div>
        <p class="mt-2 text-sm">{{ $r->name }}</p>
        <p class="text-sm font-semibold">Rp {{ number_format($r->price,0,',','.') }}</p>
      </a>
    @endforeach
  </div>
</section>
@endif
@endsection
