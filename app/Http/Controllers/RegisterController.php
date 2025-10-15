<?php

namespace App\Http\Controllers;

// Mengimpor model User untuk menyimpan data user ke database
use App\Models\User;
// Mengimpor Request untuk menangani input dari form
use Illuminate\Http\Request;
// Digunakan untuk mengarahkan (redirect) setelah proses register selesai
use Illuminate\Http\RedirectResponse;
// Rule validasi password yang lebih aman
use Illuminate\Validation\Rules\Password;
// Mengimpor model Application untuk mengambil data aplikasi (nama, logo, dll)
use App\Models\Application;

class RegisterController extends Controller
{
    /**
     * Menampilkan halaman form register.
     * Method ini mengirim data aplikasi dan title ke view register.
     */
    public function index()
    {
        return view('register.index', [
            'app' => Application::all(), // Mengambil semua data aplikasi dari database
            'title' => 'Register'       // Mengirim judul halaman
        ]);
    }

    /**
     * Menangani proses penyimpanan data user baru setelah registrasi.
     * Melakukan validasi input dan menyimpan ke tabel users.
     */
    public function store(Request $request): RedirectResponse
    {
        // Validasi data input form register
        $validatedData = $request->validate([
            'username' => 'required|string|regex:/^[a-zA-Z0-9]+$/|min:5|max:50|unique:users', // Username hanya huruf & angka
            'name' => 'required|string|max:100',                                            // Nama wajib diisi
            'email' => 'required|email:dns|unique:users',                                    // Email valid & unik
            'gender' => 'required|in:Laki-Laki,Perempuan',                                   // Gender hanya 2 pilihan
            'password' => [
                'required',
                'max:255',
                Password::min(8)            // Password minimal 8 karakter
                    ->mixedCase()          // Harus ada huruf besar dan kecil
                    ->letters()            // Harus ada huruf
                    ->numbers()            // Harus ada angka
                    ->symbols(),           // Harus ada simbol unik
                'confirmed'                // Password harus sama dengan confirmation
            ],
            'terms' => 'required'  // Checkbox syarat & ketentuan harus dicentang
        ]);

        // Memberikan gambar profil default berdasarkan gender
        if ($request->gender == 'Perempuan') {
            $validatedData['image'] = 'profil-images/girl.jpeg';
        } else {
            $validatedData['image'] = 'profil-images/man.jpeg';
        }

        // Enkripsi password sebelum disimpan ke database
        $validatedData['password'] = bcrypt($validatedData['password']);

        // Simpan data user ke database
        User::create($validatedData);

        // Redirect ke halaman login dengan pesan sukses
        return redirect('/login')->with('registerBerhasil', 'Registrasi akun anda berhasil!');
    }
}
