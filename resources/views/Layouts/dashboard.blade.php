<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>@yield('title','Dashboard') — Aksesoria</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
@vite(['resources/css/app.css','resources/js/app.js'])
</head>
<body class="bg-ink-50 min-h-screen">
<div class="flex min-h-screen">

  <aside class="w-64 bg-ink-900 text-ink-100 hidden md:flex flex-col">
    <div class="px-6 py-5 border-b border-ink-800">
      <p class="text-xs uppercase text-ink-400 tracking-widest">Aksesoria</p>
      <p class="font-semibold mt-1">{{ ucfirst(auth()->user()->role) }} Panel</p>
    </div>
    <nav class="flex-1 px-3 py-4 space-y-1 text-sm">
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
          ['cashier.reports.index',   'Sales Reports'],
        ]; @endphp
      @else
        @php $items = [
          ['warehouse.dashboard',          'Dashboard'],
          ['warehouse.inventory.index',    'Inventory'],
          ['warehouse.inventory.create',   'New Product'],
          ['warehouse.reports.index',      'Reports'],
        ]; @endphp
      @endif

      @foreach($items as [$route,$label])
        <a href="{{ route($route) }}"
           class="block px-3 py-2 rounded {{ request()->routeIs($route) ? 'bg-ink-800 text-white' : 'hover:bg-ink-800/60' }}">
          {{ $label }}
        </a>
      @endforeach
    </nav>
    <div class="px-6 py-4 border-t border-ink-800 text-xs">
      <p class="text-ink-300">{{ auth()->user()->name }}</p>
      <p class="text-ink-500">{{ auth()->user()->email }}</p>
      <form method="POST" action="{{ route('logout') }}" class="mt-3">@csrf
        <button class="w-full text-left text-ink-300 hover:text-white">Sign out →</button>
      </form>
    </div>
  </aside>

  <div class="flex-1 flex flex-col">
    <header class="bg-white border-b border-ink-200 px-6 py-4 flex items-center justify-between">
      <h1 class="text-lg font-semibold">@yield('heading','Dashboard')</h1>
      <div class="flex items-center gap-3 text-sm">
        <a href="{{ route('catalog.index') }}" class="text-ink-500 hover:text-ink-900">View store →</a>
      </div>
    </header>
    @if(session('ok'))
      <div class="px-6 pt-4">
        <div class="rounded-md bg-green-50 border border-green-200 text-green-800 text-sm px-4 py-2">{{ session('ok') }}</div>
      </div>
    @endif
    @if($errors->any())
      <div class="px-6 pt-4">
        <div class="rounded-md bg-red-50 border border-red-200 text-red-800 text-sm px-4 py-2">
          <ul class="list-disc list-inside">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
        </div>
      </div>
    @endif
    <main class="p-6">@yield('content')</main>
  </div>

</div>
</body>
</html>
