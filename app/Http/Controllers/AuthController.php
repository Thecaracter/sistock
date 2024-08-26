<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AuthController extends Controller
{

    public function index()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        try {
            // Validasi inputan
            $request->validate([
                'email' => 'required|email',
                'password' => 'required|min:6',
            ]);

            // Ambil credential dari input
            $credentials = $request->only('email', 'password');

            // Coba untuk melakukan login
            if (auth()->attempt($credentials)) {
                // Regenerasi session jika login berhasil
                $request->session()->regenerate();

                // Redirect ke dashboard dengan notifikasi sukses
                return redirect()->intended(route('dashboard'))->with('notification', [
                    'title' => 'Login Successful!',
                    'text' => 'You have been successfully logged in.',
                    'type' => 'success'
                ]);
            }

            // Jika login gagal, kembalikan dengan notifikasi error
            return back()->with('notification', [
                'title' => 'Login Failed!',
                'text' => 'The provided credentials do not match our records.',
                'type' => 'error'
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            // Menangani error validasi khusus
            return back()->withErrors($e->errors())->with('notification', [
                'title' => 'Validation Error!',
                'text' => 'Please check your input and try again.',
                'type' => 'error'
            ]);
        } catch (\Exception $e) {
            // Menangani error umum lainnya
            return back()->with('notification', [
                'title' => 'An Error Occurred!',
                'text' => 'Something went wrong. Please try again later.',
                'type' => 'error'
            ]);
        }
    }


    public function logout()
    {
        auth()->logout();

        request()->session()->invalidate();

        request()->session()->regenerateToken();

        return redirect(route('login'));
    }


}
