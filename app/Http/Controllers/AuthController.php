<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class AuthController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | LOGIN
    |--------------------------------------------------------------------------
    */

    // form login
    public function showLogin()
    {
        return view('auth.login');
    }

    // xử lý login
    public function login(Request $request)
    {
        // validate
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {

            $user = Auth::user();

            // redirect theo role
            if ($user->role === 'admin') {
                return redirect()->route('admin.dashboard');
            }

            if ($user->role === 'staff') {
                return redirect()->route('staff.dashboard');
            }

            if ($user->role === 'shipper') {
                return redirect()->route('shipper.dashboard');
            }

            // fallback
            return redirect('/login');
        }

        return back()->with('error', 'Sai email hoặc mật khẩu');
    }

    /*
    |--------------------------------------------------------------------------
    | REGISTER
    |--------------------------------------------------------------------------
    */

    // form register
    public function showRegister()
    {
        return view('auth.register');
    }

    // xử lý register
    public function register(Request $request)
    {
        // validate
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6|confirmed',
        ]);

        // tạo user
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'role' => 'staff', // mặc định
        ]);

        return redirect()->route('login')->with('success', 'Đăng ký thành công!');
    }

    /*
    |--------------------------------------------------------------------------
    | LOGOUT
    |--------------------------------------------------------------------------
    */

    public function logout()
    {
        Auth::logout();
        return redirect()->route('login');
    }
}
