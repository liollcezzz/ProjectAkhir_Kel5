@extends('layouts.dashboard')
@section('title', $user->exists ? 'Edit Staff' : 'New Staff')
@section('heading', $user->exists ? 'Edit Staff Account' : 'New Staff Account')
@section('content')
<form method="POST" action="{{ $user->exists ? route('admin.staff.update',['staff'=>$user]) : route('admin.staff.store') }}" class="card p-6 space-y-4 max-w-xl">
  @csrf @if($user->exists) @method('PUT') @endif
  <div><label class="text-sm font-medium text-white/80">Full name</label>
    <input name="name" value="{{ old('name',$user->name) }}" class="input mt-1" required></div>
  <div><label class="text-sm font-medium text-white/80">Email</label>
    <input name="email" type="email" value="{{ old('email',$user->email) }}" class="input mt-1" required></div>
  <div><label class="text-sm font-medium text-white/80">Phone</label>
    <input name="phone" value="{{ old('phone',$user->phone) }}" class="input mt-1"></div>
  <div><label class="text-sm font-medium text-white/80">Role</label>
    <select name="role" class="input mt-1">
      <option value="cashier"   @selected(old('role',$user->role)==='cashier')>Kasir (Cashier)</option>
      <option value="warehouse" @selected(old('role',$user->role)==='warehouse')>Gudang (Warehouse)</option>
    </select></div>
  <div><label class="text-sm font-medium text-white/80">Password {{ $user->exists ? '(leave blank to keep)' : '' }}</label>
    <input name="password" type="password" class="input mt-1" {{ $user->exists ? '' : 'required' }}></div>
  <div><label class="text-sm font-medium text-white/80">Confirm password</label>
    <input name="password_confirmation" type="password" class="input mt-1" {{ $user->exists ? '' : 'required' }}></div>
  <div class="flex gap-2">
    <button class="btn-dark">{{ $user->exists ? 'Update' : 'Create' }}</button>
    <a href="{{ route('admin.staff.index') }}" class="btn-outline">Cancel</a>
  </div>
</form>
@endsection
