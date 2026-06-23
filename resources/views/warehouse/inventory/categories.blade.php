@extends('layouts.dashboard')
@section('title','Assign Categories')
@section('heading','Categories — '.$product->name)
@section('content')
<form method="POST" action="{{ route('warehouse.inventory.categories',$product) }}" class="card p-6 max-w-xl">
  @csrf
  <p class="text-sm text-ink-500 mb-4">Select all categories that apply (many-to-many).</p>
  @php $selected = $product->categories->pluck('id')->all(); @endphp
  <div class="grid grid-cols-2 gap-2">
    @foreach($categories as $c)
      <label class="flex items-center gap-2 text-sm">
        <input type="checkbox" name="categories[]" value="{{ $c->id }}" @checked(in_array($c->id,$selected))>
        {{ $c->name }} <span class="text-xs text-ink-500">({{ $c->gender }})</span>
      </label>
    @endforeach
  </div>
  <div class="flex gap-2 mt-6">
    <button class="btn-dark">Save</button>
    <a href="{{ route('warehouse.inventory.index') }}" class="btn-outline">Cancel</a>
  </div>
</form>
@endsection
