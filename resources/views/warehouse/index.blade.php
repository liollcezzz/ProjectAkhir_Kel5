@extends('layouts.dashboard')
@section('title','Shipping')
@section('heading','Manage Shipping')
@section('content')

<div class="flex gap-2 mb-6">
  <a href="{{ route('warehouse.shipping.index', ['tab' => 'to_ship']) }}"
     class="btn-outline text-sm {{ $tab === 'to_ship' ? '!bg-brand-accent !text-white !border-brand-accent' : '' }}">
    Need to Ship @if($counts['to_ship'] > 0)<span class="ml-1.5 bg-white/20 text-xs rounded-full px-2 py-0.5">{{ $counts['to_ship'] }}</span>@endif
  </a>
  <a href="{{ route('warehouse.shipping.index', ['tab' => 'shipped']) }}"
     class="btn-outline text-sm {{ $tab === 'shipped' ? '!bg-brand-accent !text-white !border-brand-accent' : '' }}">
    In Transit @if($counts['shipped'] > 0)<span class="ml-1.5 bg-white/20 text-xs rounded-full px-2 py-0.5">{{ $counts['shipped'] }}</span>@endif
  </a>
  <a href="{{ route('warehouse.shipping.index', ['tab' => 'delivered']) }}"
     class="btn-outline text-sm {{ $tab === 'delivered' ? '!bg-brand-accent !text-white !border-brand-accent' : '' }}">
    Delivered @if($counts['delivered'] > 0)<span class="ml-1.5 bg-white/20 text-xs rounded-full px-2 py-0.5">{{ $counts['delivered'] }}</span>@endif
  </a>
</div>

<div class="card overflow-hidden">
  <table class="w-full table">
    <thead>
      <tr>
        <th>Order</th>
        <th>Date</th>
        <th>Customer</th>
        <th>Shipping</th>
        <th>Address</th>
        <th>Shipping Status</th>
        <th>Tracking</th>
        <th></th>
      </tr>
    </thead>
    <tbody>
      @forelse($orders as $o)
        @php $sm = config('shipping.methods.'.$o->shipping_method); @endphp
        <tr>
          <td class="font-mono text-xs text-white/70">{{ $o->code }}</td>
          <td class="text-xs text-white/40">{{ $o->created_at->format('d M Y') }}</td>
          <td class="text-white/80">{{ $o->customer_name }}</td>
          <td class="text-xs text-white/50">{{ $sm['label'] ?? $o->shipping_method }}</td>
          <td class="text-xs max-w-[180px] truncate text-white/50" title="{{ $o->shipping_address }}">
            @if($o->shipping_method !== 'pickup')
              {{ $o->shipping_address ?? '-' }}
            @else
              <span class="text-white/30">Pickup</span>
            @endif
          </td>
          <td>
            @php
              $statusBadge = match($o->shipping_status) {
                'shipped' => '!bg-blue-500/20 !text-blue-400',
                'delivered' => '!bg-green-500/20 !text-green-400',
                default => '!bg-amber-500/20 !text-amber-400',
              };
              $statusLabel = match($o->shipping_status) {
                'shipped' => 'In Transit',
                'delivered' => 'Delivered',
                default => 'Pending',
              };
            @endphp
            <span class="badge {{ $statusBadge }}">{{ $statusLabel }}</span>
          </td>
          <td class="text-xs font-mono text-white/50">{{ $o->tracking_number ?? '-' }}</td>
          <td class="whitespace-nowrap">
            @if($tab === 'to_ship')
              <a href="{{ route('warehouse.shipping.ship', $o) }}" class="btn-dark text-xs">Ship Now</a>
            @elseif($tab === 'shipped')
              <div class="flex gap-1">
                <form method="POST" action="{{ route('warehouse.shipping.delivered', $o) }}" class="inline">
                  @csrf
                  <button class="btn-dark text-xs">Delivered</button>
                </form>
                <button onclick="toggleTracking({{ $o->id }})" class="btn-outline text-xs">Update</button>
              </div>
              <form method="POST" action="{{ route('warehouse.shipping.tracking', $o) }}"
                    id="tracking-form-{{ $o->id }}" class="hidden mt-1 flex gap-1">
                @csrf
                <input name="tracking_number" value="{{ $o->tracking_number }}"
                       class="input text-xs py-1 w-28" placeholder="No. resi" required>
                <button class="btn-dark text-xs">Save</button>
              </form>
            @elseif($tab === 'delivered')
              <span class="text-xs text-white/40">Completed</span>
            @endif
          </td>
        </tr>
      @empty
        <tr><td colspan="8" class="text-center text-white/40 py-8">
          @if($tab === 'to_ship')
            No orders need shipping.
          @elseif($tab === 'shipped')
            No orders in transit.
          @else
            No delivered orders yet.
          @endif
        </td></tr>
      @endforelse
    </tbody>
  </table>
</div>
<div class="mt-6">{{ $orders->links() }}</div>

<script>
function toggleTracking(id) {
  const form = document.getElementById('tracking-form-' + id);
  form.classList.toggle('hidden');
}
</script>
@endsection
