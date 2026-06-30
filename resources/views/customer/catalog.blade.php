@extends('layouts.app')
@section('title','Shop')
@section('content')

<section class="relative overflow-hidden bg-brand text-white px-8 py-20 mb-12 shadow-xl shadow-brand/10">
  <div class="absolute inset-0 opacity-[0.03]" style="background-image: repeating-linear-gradient(45deg, transparent, transparent 40px, rgba(255,255,255,0.1) 40px, rgba(255,255,255,0.1) 41px);"></div>
  <div class="absolute top-0 right-0 w-96 h-96 bg-brand-accent/10 rounded-full blur-3xl -translate-y-1/2 translate-x-1/2"></div>
  <div class="relative max-w-2xl">
    <p class="text-xs uppercase tracking-[0.35em] text-brand-light/70">New Collection</p>
    <h1 class="text-4xl sm:text-5xl font-serif font-bold mt-3 leading-tight">Elevate your everyday accessories.</h1>
    <p class="mt-4 text-ink-300 max-w-xl leading-relaxed">Discover elegant belts, modern wallets, and curated scarves with premium finishes designed for confident everyday wear.</p>
    <div class="mt-8 flex flex-wrap gap-3">
      <a href="{{ route('catalog.index',['gender'=>'men']) }}" class="{{ $gender === 'men' ? 'btn-dark !bg-brand-accent !text-white !shadow-brand-accent/30' : 'btn-outline !border-white/30 !text-white hover:!bg-white hover:!text-brand' }}">Shop Men</a>
      <a href="{{ route('catalog.index',['gender'=>'women']) }}" class="{{ $gender === 'women' ? 'btn-dark !bg-brand-accent !text-white !shadow-brand-accent/30' : 'btn-outline !border-white/30 !text-white hover:!bg-white hover:!text-brand' }}">Shop Women</a>
    </div>
  </div>
</section>

<form method="GET" class="card p-5 mb-10 flex flex-wrap gap-4 items-end border-l-2 border-l-brand-accent">
  <div class="flex-1 min-w-[180px]">
    <label class="text-xs uppercase tracking-wider text-ink-500 font-medium">Search</label>
    <input name="q" value="{{ $q }}" class="input mt-1.5" placeholder="Belt, wallet, scarf…">
  </div>
  <div>
    <label class="text-xs uppercase tracking-wider text-ink-500 font-medium">Gender</label>
    <select name="gender" class="input mt-1.5">
      <option value="">All</option>
      @foreach(['men'=>'Men','women'=>'Women','unisex'=>'Unisex'] as $k=>$v)
        <option value="{{ $k }}" @selected($gender===$k)>{{ $v }}</option>
      @endforeach
    </select>
  </div>
  <div>
    <label class="text-xs uppercase tracking-wider text-ink-500 font-medium">Category</label>
    <select name="category" class="input mt-1.5">
      <option value="">All</option>
      @foreach($categories as $c)
        <option value="{{ $c->slug }}" @selected($catSlug===$c->slug)>{{ $c->name }}</option>
      @endforeach
    </select>
  </div>
  <button class="btn-dark">Filter</button>
  @if($q||$gender||$catSlug)<a href="{{ route('catalog.index') }}" class="btn-ghost">Reset</a>@endif
</form>

@if($products->isEmpty())
  <p class="text-center text-ink-500 py-20">No products found.</p>
@else
  <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-x-6 gap-y-10">
    @foreach($products as $p)
      <a href="{{ route('catalog.show',$p) }}" class="group">
        <div class="aspect-square bg-ink-100 overflow-hidden relative">
          <img src="{{ $p->imageUrl() }}" alt="{{ $p->name }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-700">
          <div class="absolute inset-0 bg-brand/0 group-hover:bg-brand/10 transition duration-300"></div>
        </div>
        <div class="mt-4 space-y-1.5">
          <p class="text-[11px] uppercase tracking-[0.2em] text-ink-400 font-medium">{{ $p->categories->pluck('name')->join(' · ') }}</p>
          <h3 class="text-sm font-medium leading-snug text-brand group-hover:text-brand-accent transition-colors">{{ $p->name }}</h3>
          <p class="text-sm font-semibold text-brand">Rp {{ number_format($p->price,0,',','.') }}</p>
          @if($p->isOutOfStock())<span class="badge !bg-red-500/20 !text-red-400">Out of stock</span>
          @elseif($p->isLowStock())<span class="badge !bg-amber-500/20 !text-amber-400">Only {{ $p->stock }} left</span>
          @endif
        </div>
      </a>
    @endforeach
  </div>
  <div class="mt-12">{{ $products->links('vendor.pagination.shop') }}</div>
@endif
@endsection
