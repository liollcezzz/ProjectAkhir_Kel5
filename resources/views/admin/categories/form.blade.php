@extends('layouts.dashboard')
@section('title', $category->exists ? 'Edit Category' : 'New Category')
@section('heading', $category->exists ? 'Edit Category' : 'New Category')
@section('content')
<form method="POST" action="{{ $category->exists ? route('admin.categories.update',$category) : route('admin.categories.store') }}" class="card p-6 space-y-4 max-w-xl">
  @csrf @if($category->exists) @method('PUT') @endif
  <div><label class="text-sm font-medium text-white/80">Name</label>
    <input name="name" value="{{ old('name',$category->name) }}" class="input mt-1" required></div>
  <div><label class="text-sm font-medium text-white/80">Slug (optional)</label>
    <input name="slug" value="{{ old('slug',$category->slug) }}" class="input mt-1"></div>
  <div><label class="text-sm font-medium text-white/80">Gender</label>
    <select name="gender" class="input mt-1">
      @foreach(['men','women','unisex'] as $g)
        <option value="{{ $g }}" @selected(old('gender',$category->gender)===$g)>{{ ucfirst($g) }}</option>
      @endforeach
    </select></div>
  <div><label class="text-sm font-medium text-white/80">Description</label>
    <textarea name="description" rows="3" class="input mt-1">{{ old('description',$category->description) }}</textarea></div>
  <div class="flex gap-2">
    <button class="btn-dark">{{ $category->exists ? 'Update' : 'Create' }}</button>
    <a href="{{ route('admin.categories.index') }}" class="btn-outline">Cancel</a>
  </div>
</form>
@endsection
