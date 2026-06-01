<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class RegisterController extends Controller
{
    public function show()  { return view('auth.register'); }

    public function store(Request $r) {
        $data = $r->validate([
            'name'     => 'required|string|max:120',
            'email'    => 'required|email|unique:users,email',
            'password' => ['required','confirmed', Password::min(6)],
            'phone'    => 'nullable|string|max:32',
        ]);
        $user = User::create([
            'name'=>$data['name'],'email'=>$data['email'],
            'password'=>Hash::make($data['password']),'phone'=>$data['phone'] ?? null,
            'role' => User::ROLE_CUSTOMER, // public registration => customer
        ]);
        Auth::login($user);
        return redirect()->route('catalog.index');
    }
}
