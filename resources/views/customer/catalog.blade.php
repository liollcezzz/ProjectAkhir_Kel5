@extends('layouts.app')
@section('title','Shop')
@section('content')

<section class="relative overflow-hidden rounded-xl bg-ink-900 text-white px-8 py-16 mb-10">
  <div class="max-w-2xl">
    <p class="text-xs uppercase tracking-widest text-ink-300">New Collection</p>
    <h1 class="text-4xl sm:text-5xl font-bold mt-3">Accessories, refined.</h1>
    <p class="mt-4 text-ink-300">Belts · Wallets · Scarves · Cufflinks. Crafted for everyday character.</p>
    <div class="mt-6 flex gap-3">
      <a href="{{ route('catalog.index',['gender'=>'men']) }}" class="btn bg-white text-ink-900 hover:bg-ink-100">Shop Men</a>
      <a href="{{ route('catalog.index',['gender'=>'women']) }}" class="btn border border-white/30 text-white hover:bg-white/10">Shop Women</a>
    </div>
  </div>
</section>

<form method="GET" class="card p-4 mb-8 flex flex-wrap gap-3 items-end">
  <div class="flex-1 min-w-[180px]">
    <label class="text-xs uppercase text-ink-500">Search</label>
    <input name="q" value="{{ $q }}" class="input mt-1" placeholder="Belt, wallet, scarf…">
  </div>
  <div>
    <label class="text-xs uppercase text-ink-500">Gender</label>
    <select name="gender" class="input mt-1">
      <option value="">All</option>
      @foreach(['men'=>'Men','women'=>'Women','unisex'=>'Unisex'] as $k=>$v)
        <option value="{{ $k }}" @selected($gender===$k)>{{ $v }}</option>
      @endforeach
    </select>
  </div>
  <div>
    <label class="text-xs uppercase text-ink-500">Category</label>
    <select name="category" class="input mt-1">
      <option value="">All</option>
      @foreach($categories as $c)
        <option value="{{ $c->slug }}" @selected($catSlug===$c->slug)>{{ $c->name }}</option>
      @endforeach
    </select>
  </div>
  <button class="btn-dark">Filter</button>
  @if($q||$gender||$catSlug)<a href="{{ route('catalog.index') }}" class="btn-outline">Reset</a>@endif
</form>

@if($products->isEmpty())
  <p class="text-center text-ink-500 py-20">No products found.</p>
@else
  <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-6">
    @foreach($products as $p)
      <a href="{{ route('catalog.show',$p) }}" class="group">
        <div class="aspect-square bg-ink-100 rounded-lg overflow-hidden">
          <img src="{{ $p->imageUrl() }}" alt="{{ $p->name }}" class="w-full h-full object-cover group-hover:scale-105 transition">
        </div>
        <div class="mt-3 space-y-1">
          <p class="text-xs uppercase text-ink-500">{{ $p->categories->pluck('name')->join(' · ') }}</p>
          <h3 class="text-sm font-medium leading-snug">{{ $p->name }}</h3>
          <p class="text-sm font-semibold">Rp {{ number_format($p->price,0,',','.') }}</p>
          @if($p->isOutOfStock())<span class="badge bg-red-50 text-red-700">Out of stock</span>
          @elseif($p->isLowStock())<span class="badge bg-amber-50 text-amber-700">Only {{ $p->stock }} left</span>
          @endif
        </div>
      </a>
    @endforeach
  </div>
  <div class="mt-10">{{ $products->links() }}</div>
@endif
@endsection
