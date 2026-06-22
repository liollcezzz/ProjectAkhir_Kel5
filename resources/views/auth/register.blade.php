@extends('layouts.app')
@section('title','Register')
@section('content')
<div class="max-w-md mx-auto py-10">
  <h1 class="text-2xl font-semibold mb-6">Create an account</h1>
  <form method="POST" action="{{ route('register') }}" class="card p-6 space-y-4">
    @csrf
    <div><label class="text-sm font-medium">Full name</label>
      <input name="name" value="{{ old('name') }}" class="input mt-1" required></div>
    <div><label class="text-sm font-medium">Email</label>
      <input name="email" type="email" value="{{ old('email') }}" class="input mt-1" required></div>
    <div><label class="text-sm font-medium">Phone (optional)</label>
      <input name="phone" value="{{ old('phone') }}" class="input mt-1"></div>
    <div><label class="text-sm font-medium">Password</label>
      <input name="password" type="password" class="input mt-1" required></div>
    <div><label class="text-sm font-medium">Confirm password</label>
      <input name="password_confirmation" type="password" class="input mt-1" required></div>
    @if($errors->any())<ul class="text-sm text-red-600 list-disc list-inside">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>@endif
    <button class="btn-dark w-full">Create account</button>
    <p class="text-sm text-ink-500">Already a member? <a href="{{ route('login') }}" class="underline">Sign in</a></p>
  </form>
</div>
@endsection