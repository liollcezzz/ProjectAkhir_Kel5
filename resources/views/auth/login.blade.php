@extends('layouts.app')
@section('title','Login')
@section('content')
<div class="max-w-md mx-auto py-10">
  <h1 class="text-2xl font-semibold mb-6">Sign in</h1>
  <form method="POST" action="{{ route('login') }}" class="card p-6 space-y-4">
    @csrf
    <div><label class="text-sm font-medium">Email</label>
      <input name="email" type="email" value="{{ old('email') }}" class="input mt-1" required></div>
    <div><label class="text-sm font-medium">Password</label>
      <input name="password" type="password" class="input mt-1" required></div>
    <label class="flex items-center gap-2 text-sm"><input type="checkbox" name="remember"> Remember me</label>
    @error('email')<p class="text-sm text-red-600">{{ $message }}</p>@enderror
    <button class="btn-dark w-full">Sign in</button>
    <p class="text-sm text-ink-500">No account? <a href="{{ route('register') }}" class="underline">Register</a></p>
    <div class="text-xs text-ink-500 border-t border-ink-200 pt-3">
      <p class="font-medium mb-1">Demo accounts (password: <code>password</code>)</p>
      <ul class="space-y-0.5">
        <li>admin@aksesoria.test · kasir@aksesoria.test</li>
        <li>gudang@aksesoria.test · customer@aksesoria.test</li>
      </ul>
    </div>
  </form>
</div>
@endsection