<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>@yield('title','Dashboard') — Aksesoria</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Playfair+Display:wght@400;600;700&display=swap" rel="stylesheet">
@vite(['resources/css/app.css','resources/js/app.js'])
</head>
<body class="min-h-screen bg-gradient-to-br from-[#0a0d14] via-[#111827] to-[#0f0a04] text-white/90 antialiased dashboard-dark">

<div class="flex min-h-screen">

  <aside class="w-72 bg-gradient-to-b from-[#0c0f1a] via-[#1a1f2e] to-[#1a1508] text-white hidden md:flex flex-col shadow-2xl shadow-black/30 border-r border-white/5">
    <div class="px-6 py-7 border-b border-white/5">
      <p class="text-xs uppercase tracking-[0.3em] text-gold-soft/50">Aksesoria</p>
      <p class="font-serif font-bold mt-1 text-xl text-gold-light">{{ ucfirst(auth()->user()->role) }} Panel</p>
    </div>
    <nav class="flex-1 px-4 py-5 space-y-1 text-sm">
      @php $role = auth()->user()->role; @endphp

      @if($role === 'admin')
        @php $items = [
          ['admin.dashboard',         'Dashboard'],
          ['admin.categories.index',  'Categories'],
          ['admin.staff.index',       'Staff Accounts'],
        ]; @endphp
      @elseif($role === 'cashier')
        @php $items = [
          ['cashier.dashboard',       'Dashboard'],
          ['cashier.pos.index',       'POS Terminal'],
          ['cashier.orders.index',    'Customer Orders'],
          ['cashier.reports.index',   'Sales Reports'],
        ]; @endphp
      @else
        @php $items = [
          ['warehouse.dashboard',          'Dashboard'],
          ['warehouse.inventory.index',    'Inventory'],
          ['warehouse.inventory.create',   'New Product'],
          ['warehouse.shipping.index',     'Shipping'],
          ['warehouse.reports.index',      'Reports'],
        ]; @endphp
      @endif

      @foreach($items as [$route,$label])
        <a href="{{ route($route) }}"
           class="block px-4 py-3 text-sm font-medium transition-all duration-200 {{ request()->routeIs($route) ? 'bg-gradient-to-r from-brand-accent/20 to-transparent text-gold-light border-l-2 border-gold-soft' : 'text-white/60 hover:bg-white/5 hover:text-white/90 hover:border-l-2 hover:border-white/20 border-l-2 border-transparent' }}">
          {{ $label }}
        </a>
      @endforeach
    </nav>
    <div class="px-6 py-4 border-t border-white/5 text-xs text-white/50">
      <p class="text-white/90 font-medium">{{ auth()->user()->name }}</p>
      <p class="text-white/40">{{ auth()->user()->email }}</p>
      <form method="POST" action="{{ route('logout') }}" class="mt-3">@csrf
        <button class="w-full text-left text-white/50 hover:text-gold-light transition-colors">Sign out →</button>
      </form>
    </div>
  </aside>

  <div class="flex-1 flex flex-col">
    <header class="sticky top-0 z-20 bg-white/5 backdrop-blur-md border-b border-white/5 px-8 py-4 flex items-center justify-between shadow-sm">
      <div>
        <p class="text-xs uppercase tracking-[0.25em] text-gold-soft/60">Welcome back</p>
        <h1 class="text-2xl font-serif font-bold text-white mt-0.5">@yield('heading','Dashboard')</h1>
      </div>
      <div class="flex items-center gap-3 text-sm">
        <a href="{{ route('catalog.index') }}" class="text-gold-soft/80 hover:text-gold-light font-medium transition-colors">View store →</a>
      </div>
    </header>
    @if(session('ok'))
      <div class="px-8 pt-4">
        <div class="border border-brand-accent/20 bg-brand-accent/5 text-brand-accent text-sm px-4 py-3">{{ session('ok') }}</div>
      </div>
    @endif
    @if($errors->any())
      <div class="px-8 pt-4">
        <div class="border border-red-400/20 bg-red-500/5 text-red-400 text-sm px-4 py-3">
          <ul class="list-disc list-inside">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
        </div>
      </div>
    @endif
    <main class="p-8 space-y-6">
      @yield('content')
    </main>
  </div>

</div>
</body>
</html>
