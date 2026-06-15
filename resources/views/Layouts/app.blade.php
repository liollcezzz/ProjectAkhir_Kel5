<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>@yield('title','Aksesoria') — Fashion Accessories</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
@vite(['resources/css/app.css','resources/js/app.js'])
</head>
<body class="bg-white text-ink-900">

<header class="border-b border-ink-200 sticky top-0 z-30 bg-white/90 backdrop-blur">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 h-16 flex items-center justify-between">
    <a href="{{ route('catalog.index') }}" class="text-xl tracking-tight font-bold">AKSESORIA</a>
    <nav class="hidden md:flex items-center gap-6 text-sm">
      <a href="{{ route('catalog.index',['gender'=>'men']) }}"   class="hover:text-brand-accent">Men</a>
      <a href="{{ route('catalog.index',['gender'=>'women']) }}" class="hover:text-brand-accent">Women</a>
      <a href="{{ route('catalog.index',['gender'=>'unisex']) }}"class="hover:text-brand-accent">Unisex</a>
    </nav>
    <div class="flex items-center gap-3 text-sm">
      <form action="{{ route('catalog.index') }}" class="hidden sm:flex">
        <input name="q" value="{{ request('q') }}" placeholder="Search…" class="input w-48">
      </form>
      <a href="{{ route('cart.index') }}" class="btn-outline">Cart ({{ array_sum((array) session('cart',[])) }})</a>
      @auth
        @if(auth()->user()->isCustomer())
          <a href="{{ route('customer.orders.index') }}" class="hidden sm:inline btn-outline">Orders</a>
        @else
          <a href="{{ route('dashboard.redirect') }}" class="hidden sm:inline btn-outline">Dashboard</a>
        @endif
        <form method="POST" action="{{ route('logout') }}">@csrf <button class="btn-dark">Logout</button></form>
      @else
        <a href="{{ route('login') }}" class="btn-outline">Login</a>
        <a href="{{ route('register') }}" class="btn-dark">Sign up</a>
      @endauth
    </div>
  </div>
</header>

@if(session('ok'))
  <div class="max-w-7xl mx-auto px-4 mt-4">
    <div class="rounded-md bg-green-50 border border-green-200 text-green-800 text-sm px-4 py-2">{{ session('ok') }}</div>
  </div>
@endif

<main class="max-w-7xl mx-auto px-4 sm:px-6 py-8">
  @yield('content')
</main>

<footer class="mt-20 border-t border-ink-200">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 py-10 text-sm text-ink-500 flex flex-col sm:flex-row justify-between gap-4">
    <p>© {{ date('Y') }} Aksesoria. Course project.</p>
    <p>Belts · Wallets · Scarves · Cufflinks</p>
  </div>
</footer>
</body>
</html>