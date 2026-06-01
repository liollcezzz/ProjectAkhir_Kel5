<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function show()  { return view('auth.login'); }

    public function store(Request $r) {
        $data = $r->validate(['email'=>'required|email','password'=>'required']);
        if (!Auth::attempt($data, $r->boolean('remember'))) {
            return back()->withErrors(['email'=>'Invalid credentials.'])->onlyInput('email');
        }
        $r->session()->regenerate();
        return redirect()->intended(route('dashboard.redirect'));
    }

    public function destroy(Request $r) {
        Auth::logout();
        $r->session()->invalidate();
        $r->session()->regenerateToken();
        return redirect('/');
    }
}
