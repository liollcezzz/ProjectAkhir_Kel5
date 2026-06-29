@extends('layouts.dashboard')
@section('title','Staff Accounts')
@section('heading','Staff Accounts')
@section('content')
<div class="flex justify-end mb-4">
  <a href="{{ route('admin.staff.create') }}" class="btn-dark">+ New staff</a>
</div>
<div class="card overflow-hidden">
  <table class="w-full table">
    <thead><tr><th>Name</th><th>Email</th><th>Role</th><th>Created</th><th></th></tr></thead>
    <tbody>
      @forelse($staff as $s)
        <tr>
          <td class="font-medium text-white/90">{{ $s->name }}</td>
          <td class="text-white/70">{{ $s->email }}</td>
          <td><span class="badge !bg-white/5 !text-white/60 capitalize">{{ $s->role === 'cashier' ? 'Kasir' : 'Gudang' }}</span></td>
          <td class="text-white/40">{{ $s->created_at->format('d M Y') }}</td>
          <td class="text-right">
            <a href="{{ route('admin.staff.edit',$s) }}" class="text-sm text-gold-soft/80 hover:text-gold-light transition-colors">Edit</a>
            <form method="POST" action="{{ route('admin.staff.destroy',$s) }}" class="inline" onsubmit="return confirm('Delete this account?')">
              @csrf @method('DELETE')
              <button class="text-sm text-red-400 hover:text-red-300 ml-3 transition-colors">Delete</button>
            </form>
          </td>
        </tr>
      @empty <tr><td colspan="5" class="px-4 py-6 text-center text-white/40">No staff accounts.</td></tr>
      @endforelse
    </tbody>
  </table>
</div>
<div class="mt-6">{{ $staff->links() }}</div>
@endsection
