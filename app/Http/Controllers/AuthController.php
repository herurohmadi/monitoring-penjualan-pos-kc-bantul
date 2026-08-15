<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;

class AuthController extends Controller
{
    public function __construct()
    {
        // Middleware: hanya guest yang bisa akses login, kecuali logout dan index
        $this->middleware('guest')->except(['logout', 'index']);
    }

    /**
     * Redirect user ke dashboard jika sudah login,
     * atau ke halaman login jika belum.
     */
    public function index(): RedirectResponse
    {
        return Auth::check()
            ? redirect()->route('dashboard')
            : redirect()->route('login');
    }

    /**
     * Tampilkan halaman login dengan header anti-cache.
     */
    public function showLoginForm(): Response
    {
        return response()
            ->view('auth.login')
            ->header('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0')
            ->header('Pragma', 'no-cache')
            ->header('Expires', 'Sat, 01 Jan 2000 00:00:00 GMT');
    }

    /**
     * Proses login user berdasarkan username.
     */
    public function login(Request $request): RedirectResponse
    {
        // Validasi input
        $validated = $request->validate([
            'username' => 'required|string|min:3|max:20',
        ], [
            'username.required' => 'Keyword wajib diisi.',
            'username.min'      => 'Keyword minimal 3 karakter.',
            'username.max'      => 'Keyword maksimal 20 karakter.',
        ]);

        // Cari user berdasarkan username
        $user = User::where('username', $validated['username'])->first();

        if ($user) {
            Auth::login($user);                // Login user
            $request->session()->regenerate(); // Regenerate session untuk keamanan

            return redirect()->intended(route('dashboard'))
                ->with('success', 'Selamat datang, ' . $user->name . '!');
        }

        // Jika username tidak ditemukan
        return back()
            ->withErrors(['username' => 'Keyword tidak ditemukan.'])
            ->withInput();
    }

    /**
     * Logout user dan bersihkan session.
     */
    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();       // Hapus session lama
        $request->session()->regenerateToken();  // Regenerate CSRF token

        return redirect()->route('login')
            ->with('success', 'Anda berhasil logout.');
    }
}
