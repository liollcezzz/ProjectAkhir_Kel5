<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title','Aksesoria') — Fashion Accessories</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Playfair+Display:ital,wght@0,400;0,600;0,700;1,400&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css','resources/js/app.js'])
    @stack('head')
</head>
<body class="min-h-screen storefront-dark">

<header class="border-b border-white/5 sticky top-0 z-30 bg-white/5 backdrop-blur-md transition-all duration-300">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 h-16 flex items-center justify-between">
    <a href="{{ route('catalog.index') }}" class="text-2xl font-serif font-bold tracking-[0.3em] text-brand-accent hover:opacity-80 transition-opacity">AKSESORIA</a>
    
    <nav class="hidden md:flex items-center gap-8 text-sm font-medium tracking-wide">
      <a href="{{ route('catalog.index',['gender'=>'men']) }}"   class="nav-link">Men</a>
      <a href="{{ route('catalog.index',['gender'=>'women']) }}" class="nav-link">Women</a>
      <a href="{{ route('catalog.index',['gender'=>'unisex']) }}" class="nav-link">Unisex</a>
    </nav>
    
    <div class="flex items-center gap-3 text-sm">
      <form action="{{ route('catalog.index') }}" class="hidden sm:flex">
        <input name="q" value="{{ request('q') }}" placeholder="Search…" class="input w-40 lg:w-48">
      </form>
      <a href="{{ route('cart.index') }}" class="btn-outline">Cart ({{ array_sum((array) session('cart',[])) }})</a>
      
      @auth
        @if(auth()->user()->isCustomer())
          <a href="{{ route('customer.orders.index') }}" class="hidden sm:inline btn-ghost">Orders</a>
        @else
          <a href="{{ route('dashboard.redirect') }}" class="hidden sm:inline btn-ghost">Dashboard</a>
        @endif
        <form method="POST" action="{{ route('logout') }}">@csrf <button class="btn-dark">Logout</button></form>
      @else
        <a href="{{ route('login') }}" class="btn-ghost">Login</a>
        <a href="{{ route('register') }}" class="btn-dark">Sign up</a>
      @endauth
    </div>
  </div>
</header>

@if(session('ok'))
  <div class="max-w-7xl mx-auto px-4 mt-4">
    <div class="border border-gold-soft/20 bg-white/5 text-gold-light text-sm px-4 py-3 tracking-wide">
        {{ session('ok') }}
    </div>
  </div>
@endif

<main class="max-w-7xl mx-auto px-4 sm:px-6 py-12">
  @yield('content')
</main>

<footer class="mt-24 border-t border-white/10 bg-brand">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 py-12 text-sm text-white/60 flex flex-col sm:flex-row justify-between items-center gap-4">
    <p class="tracking-wide">© {{ date('Y') }} Aksesoria. Course project.</p>
    <p class="font-medium tracking-wider text-brand-light">Belts · Wallets · Scarves · Cufflinks</p>
  </div>
</footer>
</body>
</html>