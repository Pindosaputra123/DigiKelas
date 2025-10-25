<?php

namespace App\Http\Controllers;

use App\Models\Answer_user;
use Illuminate\Http\Request;
use App\Models\Application;
use App\Models\User;
use App\Models\Result;
use App\Models\Thread;
use App\Models\Comment;
use App\Models\Like;
use Illuminate\Validation\Rules\Password;

class AdminPenggunaController extends Controller
{
    /**
     * Menampilkan halaman daftar semua pengguna (bukan admin).
     * Data ditampilkan secara paginasi dan diurutkan berdasarkan pengguna terbaru.
     */
    public function index()
    {
        return view('admin.pengguna.index', [
            'app' => Application::all(),
            'title' => 'Data Pengguna',
            'users' => User::latest()->where('is_admin', false)->paginate(10)
        ]);
    }

    /**
     * Menyimpan data pengguna baru ke dalam database.
     * Validasi dilakukan untuk memastikan format username, email, password, dan foto profil benar.
     */
    public function store(Request $request)
    {
        // Validasi input pengguna baru
        $validatedData = $request->validate([
            'username' => 'required|string|regex:/^[a-zA-Z0-9]+$/|min:5|max:50|unique:users',
            'name' => 'required|string|max:100',
            'email' => 'required|email:dns|unique:users',
            'image' => 'image|file|max:500|dimensions:ratio=1/1',
            'gender' => 'required|in:Laki-Laki,Perempuan',
            'password' => ['required', 'max:255', Password::min(8)->mixedCase()->letters()->numbers()->symbols()]
        ], [
            'image.dimensions' => 'The :attribute must have a 1:1 aspect ratio.',
        ]);

        // Cek apakah pengguna mengunggah foto profil atau tidak
        if ($request->file('image')) {
            // Jika ada, simpan ke folder profil-images
            $validatedData['image'] = $request->file('image')->store('profil-images');
        } else {
            // Jika tidak, gunakan gambar default berdasarkan gender
            if ($request->gender == 'Perempuan') {
                $validatedData['image'] = 'profil-images/girl.jpeg';
            } else {
                $validatedData['image'] = 'profil-images/man.jpeg';
            }
        }

        // Enkripsi password sebelum disimpan
        $validatedData['password'] = bcrypt($validatedData['password']);

        // Simpan data pengguna baru ke database
        User::create($validatedData);

        // Redirect kembali dengan pesan sukses
        return back()->with('adduserSuccess', 'Pengguna berhasil ditambah!');
    }


    /**
     * Memperbarui data pengguna yang sudah ada.
     * Termasuk update username, email, password, dan gambar profil.
     */
    public function update(Request $request)
    {
        try {
            // Dekripsi ID pengguna dari form (keamanan data)
            $id_user = decrypt($request->codeUser);
            $data = User::where('id', $id_user)->first();

            // Aturan dasar validasi
            $rules = [
                'name' => 'required|string|max:100',
                'image' => 'image|file|max:500|dimensions:ratio=1/1',
                'gender' => 'required|in:Laki-Laki,Perempuan',
            ];

            // Jika username diubah, tambahkan validasi unik
            if ($request->username != $data->username) {
                $rules['username'] = 'required|string|regex:/^[a-zA-Z0-9]+$/|min:5|max:50|unique:users';
            }

            // Jika email diubah, tambahkan validasi unik
            if ($request->email != $data->email) {
                $rules['email'] = 'required|email:dns|unique:users';
            }

            // Jika password diubah, tambahkan validasi keamanan password
            if ($request->password) {
                $rules['password'] = ['required', 'max:255', Password::min(8)->mixedCase()->letters()->numbers()->symbols()];
            }

            // Jalankan validasi data
            $validatedData = $request->validate($rules, [
                'image.dimensions' => 'The image must have a 1:1 aspect ratio.'
            ]);

            // Enkripsi password baru jika diinputkan
            if ($request->password) {
                $validatedData['password'] = bcrypt($request->password);
            }

            // Simpan foto profil baru jika diunggah
            if ($request->image) {
                $validatedData['image'] = $request->file('image')->store('profil-images');
            }

            // Update data pengguna di database
            User::where('id', $id_user)->update($validatedData);

            // Kembalikan ke halaman sebelumnya dengan pesan sukses
            return back()->with('updateUserSuccess', 'Pengguna berhasil diupdate!');
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Tangkap error validasi dan tampilkan kembali form dengan error message
            $errors = $e->validator->errors()->toArray();
            session()->flash('validationErrors', $errors);
            return redirect()->back()->withInput();
        }
    }

    /**
     * Mengambil data pengguna berdasarkan ID untuk keperluan AJAX (edit modal).
     */
    public function getuser(Request $request)
    {
        // Dekripsi ID pengguna
        $id = decrypt($request->id);

        // Ambil data pengguna sesuai ID
        $data = User::where('id', $id)->get();

        // Kembalikan data dalam bentuk JSON (otomatis oleh Laravel)
        return $data;
    }

    /**
     * Menghapus data pengguna dan semua data terkaitnya di tabel lain
     * seperti like, comment, thread, answer_user, dan result.
     */
    public function destroy(User $user)
    {
        // Hapus semua relasi data terkait sebelum menghapus user
        Like::where('user_id', $user->id)->delete();
        Comment::where('user_id', $user->id)->delete();
        Thread::where('user_id', $user->id)->delete();
        Answer_user::where('user_id', $user->id)->delete();
        Result::where('user_id', $user->id)->delete();

        // Hapus data user utama
        User::destroy($user->id);

        // Kembalikan ke halaman sebelumnya dengan pesan sukses
        return back()->with('deleteUserSuccess', 'Pengguna berhasil dihapus!');
    }

    /**
     * Mencari data pengguna berdasarkan kata kunci dari input admin.
     */
    public function search()
    {
        // Jika input kosong, kembalikan ke halaman utama pengguna
        if (request('q') === null) {
            return redirect('/admin/pengguna');
            exit;
        }

        // Tampilkan hasil pencarian pengguna non-admin berdasarkan keyword
        return view('admin.pengguna.search', [
            'app' => Application::all(),
            'title' => 'Data Pengguna',
            'users' => User::latest()->where('is_admin', false)->searching(request('q'))->paginate(10)
        ]);
    }
}
