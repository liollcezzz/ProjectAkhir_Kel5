@extends('layouts.app')
@section('title',$product->name)
@section('content')

<div class="flex items-center justify-between mb-8">
  <div>
    <p class="text-[11px] uppercase tracking-[0.25em] text-ink-400 font-medium">{{ $product->categories->pluck('name')->join(' · ') }}</p>
    <h1 class="text-3xl sm:text-4xl font-serif font-bold mt-1">{{ $product->name }}</h1>
  </div>
  <div class="flex items-center gap-2">
    @if($prev)
      <a href="{{ route('catalog.show', $prev) }}" class="w-10 h-10 border border-ink-200 flex items-center justify-center hover:border-brand-accent hover:text-brand-accent transition-all duration-300 group" title="{{ $prev->name }}">
        <svg class="w-4 h-4 group-hover:-translate-x-0.5 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
      </a>
    @else
      <span class="w-10 h-10 border border-ink-100 flex items-center justify-center text-ink-300 cursor-not-allowed">
        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
      </span>
    @endif
    @if($next)
      <a href="{{ route('catalog.show', $next) }}" class="w-10 h-10 border border-ink-200 flex items-center justify-center hover:border-brand-accent hover:text-brand-accent transition-all duration-300 group" title="{{ $next->name }}">
        <svg class="w-4 h-4 group-hover:translate-x-0.5 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
      </a>
    @else
      <span class="w-10 h-10 border border-ink-100 flex items-center justify-center text-ink-300 cursor-not-allowed">
        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
      </span>
    @endif
  </div>
</div>

<div class="grid md:grid-cols-2 gap-12">
  <div class="aspect-square bg-ink-50 overflow-hidden relative group">
    <img src="{{ $product->imageUrl() }}" alt="{{ $product->name }}" class="w-full h-full object-cover transition duration-700 group-hover:scale-105">
  </div>
  <div class="flex flex-col justify-center">
    <p class="text-3xl font-serif font-bold text-brand-accent">Rp {{ number_format($product->price,0,',','.') }}</p>
    <div class="w-12 h-0.5 bg-brand-accent/30 mt-4 mb-6"></div>
    <p class="text-ink-600 leading-relaxed">{{ $product->description }}</p>
    @if($product->isOutOfStock())
      <p class="mt-8 text-red-600 font-medium tracking-wide">Currently out of stock.</p>
    @else
      <form method="POST" action="{{ route('cart.add',$product) }}" class="mt-8 flex gap-3">
        @csrf
        <input type="number" name="qty" value="1" min="1" max="{{ $product->stock }}" class="input w-20 text-center">
        <button class="btn-dark flex-1">Add to cart</button>
      </form>
      @if($product->isLowStock())<p class="text-amber-400 text-sm mt-3 tracking-wide">Only {{ $product->stock }} left in stock</p>@endif
    @endif
    <dl class="mt-10 text-sm border-t border-ink-200/50 pt-5 space-y-3 text-ink-500">
      <div class="flex justify-between"><dt class="tracking-wider uppercase text-[11px]">SKU</dt><dd class="text-brand font-medium">{{ $product->sku }}</dd></div>
      <div class="flex justify-between"><dt class="tracking-wider uppercase text-[11px]">Gender</dt><dd class="text-brand font-medium capitalize">{{ $product->gender }}</dd></div>
    </dl>
  </div>
</div>

@if($related->isNotEmpty())
<section class="mt-24">
  <div class="flex items-center gap-4 mb-8">
    <h2 class="text-lg font-serif font-bold">You may also like</h2>
    <div class="flex-1 h-px bg-ink-200/50"></div>
  </div>
  <div class="grid grid-cols-2 sm:grid-cols-4 gap-6">
    @foreach($related as $r)
      <a href="{{ route('catalog.show',$r) }}" class="group">
        <div class="aspect-square bg-ink-50 overflow-hidden">
          <img src="{{ $r->imageUrl() }}" alt="{{ $r->name }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-700">
        </div>
        <div class="mt-3">
          <p class="text-sm text-brand group-hover:text-brand-accent transition-colors">{{ $r->name }}</p>
          <p class="text-sm font-semibold text-brand mt-0.5">Rp {{ number_format($r->price,0,',','.') }}</p>
        </div>
      </a>
    @endforeach
  </div>
</section>
@endif
@endsection
