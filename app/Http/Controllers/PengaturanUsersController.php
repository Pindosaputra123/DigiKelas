<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Application;

class PengaturanUsersController extends Controller
{
    /**
     * Menampilkan halaman pengaturan user
     * Berisi data aplikasi dan judul halaman
     */
    public function index()
    {
        return view('users.setting.index', [
            'app' => Application::all(), // mengambil semua data dari model Application
            'title' => 'Pengaturan' // judul halaman
        ]);
    }

    /**
     * Verifikasi user berdasarkan username dan password
     * Digunakan untuk validasi ulang sebelum melakukan perubahan sensitif
     */
    public function verify(Request $request)
    {
        // Validasi input dari form verifikasi
        $credentials = $request->validate([
            'usernameverify' => 'required',
            'password' => 'required',
        ]);

        // Ubah key usernameverify menjadi username agar cocok dengan kolom di database
        $credentials['username'] = $credentials['usernameverify'];
        unset($credentials['usernameverify']);

        // Coba autentikasi user berdasarkan username dan password
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate(); // buat session baru setelah login berhasil
            return back()->with('statusverifysuccess', 'success'); // kirim notifikasi sukses
            exit;
        }

        // Jika login gagal, kirim notifikasi gagal
        return back()->with('statusverifyfailed', 'failed');
    }

    /**
     * Mengatur / mengganti email user
     */
    public function setemail(Request $request)
    {
        // Validasi input email agar valid dan unik
        $validatedData = $request->validate([
            'email' => 'required|email:dns|unique:users',
        ]);

        // Update email user berdasarkan ID yang sedang login
        User::where('id', auth()->user()->id)
            ->update($validatedData);

        // Redirect ke halaman pengaturan dengan pesan sukses
        return redirect('/pengaturan')->with('updateEmailUser', 'Email berhasil diupdate!');
    }

    /**
     * Menyimpan perubahan data profil user
     */
    public function store(Request $request)
    {
        // Aturan validasi untuk input data user
        $rules = [
            'name' => 'required|string|max:100',
            'alamat' => 'Max:255',
            'gender' => 'in:Laki-Laki,Perempuan',
            'tanggal_lahir' => '',
            'image' => 'image|file|max:500|dimensions:ratio=1/1' // gambar harus square (1:1)
        ];

        // Validasi tambahan jika username diubah
        if ($request->username != auth()->user()->username) {
            $rules['username'] = 'required|string|regex:/^[a-zA-Z0-9]+$/|min:5|max:50|unique:users';
        }

        // Jalankan validasi
        $validatedData = $request->validate($rules, [
            'image.dimensions' => 'The :attribute must have a 1:1 aspect ratio.',
        ]);

        // Jika user upload gambar baru, simpan ke storage
        if ($request->file('image')) {
            $validatedData['image'] = $request->file('image')->store('profil-images');
        }

        // Update data user di database
        User::where('id', auth()->user()->id)->update($validatedData);

        // Redirect ke halaman pengaturan dengan pesan sukses
        return redirect('/pengaturan')->with('updateUserBerhasil', 'Data user berhasil diupdate!');
    }

    /**
     * Mengubah password user
     */
    public function changepassword(Request $request)
    {
        // Validasi input password lama dan password baru
        $validatedData = $request->validate([
            'passwordLama' => 'required',
            'passwordBaru' => [
                'required',
                'max:255',
                // Gunakan rule Password bawaan Laravel
                Password::min(8)
                    ->mixedCase()
                    ->letters()
                    ->numbers()
                    ->symbols(),
                'confirmed' // harus sama dengan field passwordBaru_confirmation
            ]
        ]);

        // Cek apakah password lama cocok dengan yang di database
        if (Hash::check($validatedData['passwordLama'], auth()->user()->password)) {
            // Jika cocok, hash password baru dan simpan
            $hashPassword = bcrypt($validatedData['passwordBaru']);
            User::where('id', auth()->user()->id)
                ->update(['password' => $hashPassword]);

            // Redirect dengan pesan sukses
            return redirect('/pengaturan')->with('passwordUpdateSuccess', 'Password berhasil diupdate!');
            exit;
        } else {
            // Jika password lama salah, kirim pesan error
            return redirect('/pengaturan')->with('passwordLamaSalah', 'Password lama Anda salah!');
        }
    }
}
