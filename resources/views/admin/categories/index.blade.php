@extends('layouts.dashboard')
@section('title','Categories')
@section('heading','Product Categories')
@section('content')
<div class="flex justify-end mb-4">
  <a href="{{ route('admin.categories.create') }}" class="btn-dark">+ New category</a>
</div>
<div class="card overflow-hidden">
  <table class="w-full table">
    <thead><tr><th>Name</th><th>Gender</th><th>Products</th><th></th></tr></thead>
    <tbody>
      @forelse($categories as $c)
        <tr>
          <td><p class="font-medium text-white/90">{{ $c->name }}</p><p class="text-xs text-white/40">{{ $c->slug }}</p></td>
          <td class="capitalize text-white/70">{{ $c->gender }}</td>
          <td class="text-white/70">{{ $c->products_count }}</td>
          <td class="text-right">
            <a href="{{ route('admin.categories.edit',$c) }}" class="text-sm text-gold-soft/80 hover:text-gold-light transition-colors">Edit</a>
            <form method="POST" action="{{ route('admin.categories.destroy',$c) }}" class="inline" onsubmit="return confirm('Delete this category?')">
              @csrf @method('DELETE')
              <button class="text-sm text-red-400 hover:text-red-300 ml-3 transition-colors">Delete</button>
            </form>
          </td>
        </tr>
      @empty <tr><td colspan="4" class="px-4 py-6 text-center text-white/40">No categories yet.</td></tr>
      @endforelse
    </tbody>
  </table>
</div>
<div class="mt-6">{{ $categories->links() }}</div>
@endsection
