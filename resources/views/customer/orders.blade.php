@extends('layouts.app')
@section('title','My Orders')
@section('content')
<h1 class="text-2xl font-semibold mb-6">My Orders</h1>
@if($orders->isEmpty())
  <p class="text-ink-500">No orders yet.</p>
@else
<div class="card overflow-hidden">
  <table class="w-full table">
    <thead><tr><th>Code</th><th>Date</th><th>Items</th><th>Total</th><th>Status</th><th></th></tr></thead>
    <tbody>
      @foreach($orders as $o)
        <tr>
          <td class="font-mono">{{ $o->code }}</td>
          <td>{{ $o->created_at->format('d M Y H:i') }}</td>
          <td>{{ $o->items->sum('quantity') }}</td>
          <td>Rp {{ number_format($o->total,0,',','.') }}</td>
          <td><span class="badge bg-ink-100">{{ ucfirst($o->status) }}</span></td>
          <td><a class="text-sm underline" href="{{ route('customer.orders.show',$o) }}">View</a></td>
        </tr>
      @endforeach
    </tbody>
  </table>
</div>
<div class="mt-6">{{ $orders->links() }}</div>
@endif
@endsection
