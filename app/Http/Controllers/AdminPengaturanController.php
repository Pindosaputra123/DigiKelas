<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Application;

class AdminPengaturanController extends Controller
{
  // =================== HALAMAN UTAMA PENGATURAN =================== //
  public function index()
  {
    // Menampilkan halaman pengaturan admin dengan data aplikasi
    return view('admin.setting.index', [
      'app' => Application::all(),
      'title' => 'Pengaturan'
    ]);
  }

  // =================== VERIFIKASI USER (LOGIN ULANG) =================== //
  public function verify(Request $request)
  {
    // Validasi input verifikasi username dan password
    $credentials = $request->validate([
      'usernameverify' => 'required',
      'password' => 'required',
    ]);

    // Ubah key agar sesuai dengan kolom username di database
    $credentials['username'] = $credentials['usernameverify'];
    unset($credentials['usernameverify']);

    // Cek kredensial dan autentikasi ulang
    if (Auth::attempt($credentials)) {
      $request->session()->regenerate();
      return back()->with('statusverifysuccess', 'success'); // verifikasi berhasil
      exit;
    }

    // Jika gagal verifikasi
    return back()->with('statusverifyfailed', 'failed');
  }

  // =================== UPDATE EMAIL ADMIN =================== //
  public function setemail(Request $request)
  {
    // Validasi email baru agar unik dan valid
    $validatedData = $request->validate([
      'email' => 'required|email:dns|unique:users',
    ]);

    // Update email admin yang sedang login
    User::where('id', auth()->user()->id)
      ->update($validatedData);

    return redirect('/admin/pengaturan')->with('updateEmailUser', 'Email berhasil diupdate!');
  }

  // =================== UPDATE DATA PROFIL ADMIN =================== //
  public function store(Request $request)
  {
    // Validasi data profil
    $rules = [
      'name' => 'required|string|max:100',
      'alamat' => 'Max:255',
      'gender' => 'in:Laki-Laki,Perempuan',
      'tanggal_lahir' => '',
      'image' => 'image|file|max:500|dimensions:ratio=1/1'
    ];

    // Jika username berubah, pastikan tidak duplikat dan sesuai format
    if ($request->username != auth()->user()->username) {
      $rules['username'] = 'required|string|regex:/^[a-zA-Z0-9]+$/|min:5|max:50|unique:users';
    }

    // Jalankan validasi
    $validatedData = $request->validate($rules, [
      'image.dimensions' => 'The :attribute must have a 1:1 aspect ratio.',
    ]);

    // Simpan gambar profil baru jika ada
    if ($request->file('image')) {
      $validatedData['image'] = $request->file('image')->store('profil-images');
    }

    // Update data profil admin
    User::where('id', auth()->user()->id)->update($validatedData);

    return redirect('/admin/pengaturan')->with('updateUserBerhasil', 'Data admin berhasil diupdate!');
  }

  // =================== GANTI PASSWORD ADMIN =================== //
  public function changepassword(Request $request)
  {
    // Validasi password lama dan password baru
    $validatedData = $request->validate([
      'passwordLama' => 'required',
      'passwordBaru' => ['required', 'max:255', Password::min(8)->mixedCase()->letters()->numbers()->symbols(), 'confirmed']
    ]);

    // Cek apakah password lama sesuai
    if (Hash::check($validatedData['passwordLama'], auth()->user()->password)) {
      // Jika cocok, hash password baru dan update
      $hashPassword = bcrypt($validatedData['passwordBaru']);
      User::where('id', auth()->user()->id)
        ->update(['password' => $hashPassword]);

      return redirect('/admin/pengaturan')->with('passwordUpdateSuccess', 'Password berhasil diupdate!');
      exit;
    } else {
      // Jika password lama salah
      return redirect('/admin/pengaturan')->with('passwordLamaSalah', 'Password lama Anda salah!');
    }
  }

  // =================== UPDATE DATA APLIKASI =================== //
  public function updateapp(Request $request)
  {
    // Validasi data aplikasi
    $rules = [
      'name_app' => 'required|string|max:255',
      'description_app' => 'Max:255',
      'logo' => 'image|file|max:500|dimensions:ratio=1/1'
    ];

    $validatedData = $request->validate($rules, [
      'logo.dimensions' => 'The :attribute must have a 1:1 aspect ratio.',
    ]);

    // Jika logo baru diunggah, simpan ke storage
    if ($request->file('logo')) {
      $validatedData['logo'] = $request->file('logo')->store('logo-aplikasi');
    }

    // Update data aplikasi utama
    Application::where('id', 1)->update($validatedData);

    return redirect('/admin/pengaturan')->with('updateAppBerhasil', 'Data app berhasil diupdate!');
  }
}
