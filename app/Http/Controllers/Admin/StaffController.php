<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules\Password;

class StaffController extends Controller
{
    public function index() {
        $staff = User::whereIn('role',['cashier','warehouse'])->latest()->paginate(15);
        return view('admin.staff.index', compact('staff'));
    }

    public function create() { return view('admin.staff.form', ['user'=>new User()]); }

    public function store(Request $r) {
        $data = $r->validate([
            'name'     => 'required|string|max:120',
            'email'    => 'required|email|unique:users,email',
            'role'     => 'required|in:cashier,warehouse',
            'phone'    => 'nullable|string|max:32',
            'password' => ['required','confirmed', Password::min(6)],
        ]);
        User::create($data);
        return redirect()->route('admin.staff.index')->with('ok','Staff account created.');
    }

    public function edit(User $staff) {
        abort_unless(in_array($staff->role, ['cashier','warehouse']), 404);
        return view('admin.staff.form', ['user'=>$staff]);
    }

    public function update(Request $r, User $staff) {
        abort_unless(in_array($staff->role, ['cashier','warehouse']), 404);
        $data = $r->validate([
            'name'     => 'required|string|max:120',
            'email'    => 'required|email|unique:users,email,'.$staff->id,
            'role'     => 'required|in:cashier,warehouse',
            'phone'    => 'nullable|string|max:32',
            'password' => ['nullable','confirmed', Password::min(6)],
        ]);
        if (empty($data['password'])) unset($data['password']);
        $staff->update($data);
        return redirect()->route('admin.staff.index')->with('ok','Staff account updated.');
    }

    public function destroy(User $staff) {
        abort_unless(in_array($staff->role, ['cashier','warehouse']), 404);
        $staff->delete();
        return back()->with('ok','Staff account deleted.');
    }
}
