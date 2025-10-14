<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use App\Models\Application;

class LoginController extends Controller
{
    /**
     * Menampilkan halaman login.
     * Method ini mengambil data aplikasi (nama, logo, dll)
     * untuk ditampilkan pada halaman login.
     */
    public function index()
    {
        return view('login.index', [
            // Mengambil semua data aplikasi dari tabel applications
            'app' => Application::all(),
            // Mengirim judul halaman ke view
            'title' => 'Login'
        ]);
    }

    /**
     * Proses autentikasi user (login).
     * Validasi username dan password terlebih dahulu,
     * lalu cek kecocokan di database menggunakan Auth::attempt().
     */
    public function authenticate(Request $request): RedirectResponse
    {
        // Validasi input username dan password wajib diisi
        $credentials = $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);

        // Cek apakah data login cocok dengan database
        if (Auth::attempt($credentials)) {
            // Regenerate session untuk keamanan setelah login
            $request->session()->regenerate();

            // Jika user memiliki hak admin arahkan ke dashboard admin
            if (auth()->user()->is_admin) {
                return redirect()->intended('/admin/dashboard');
            } else {
                // Jika bukan admin, arahkan ke halaman materi
                return redirect()->intended('/materi');
            }
        }

        // Jika login gagal, kembali ke halaman sebelumnya
        // dengan pesan error
        return back()->with('loginError', 'Username atau password salah!');
    }

    /**
     * Logout user.
     * Menghapus session dan token CSRF, lalu redirect ke halaman login.
     */
    public function logout(Request $request): RedirectResponse
    {
        // Menghapus autentikasi pengguna
        Auth::logout();

        // Menghapus sesi dan regenerasi token untuk keamanan
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // Arahkan kembali ke halaman login
        return redirect('/login');
    }
}
