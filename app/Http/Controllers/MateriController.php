<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Materi;
use App\Models\Application;

class MateriController extends Controller
{
    /**
     * Tampilkan halaman daftar materi untuk admin.
     */
    public function index()
    {
        return view('admin.datamateri.index', [
            'app' => Application::all(), // mengambil data aplikasi (informasi global)
            'materis' => Materi::latest()->paginate(10), // ambil semua materi dengan pagination
            'title' => 'Data Materi' // judul halaman
        ]);
    }

    /**
     * Tampilkan halaman materi untuk user (member).
     */
    public function show()
    {
        return view('users.materi.index', [
            'app' => Application::all(), // data aplikasi (untuk header/footer)
            'materis' => Materi::all(), // ambil semua materi tanpa pagination
            'title' => 'Materi' // judul halaman
        ]);
    }

    /**
     * Tambah data materi baru.
     */
    public function store(Request $request)
    {
        // Validasi input dari form tambah materi
        $validatedData = $request->validate([
            'image' => 'required|image|file|max:500', // wajib upload gambar max 500KB
            'title' => 'required|max:255|string', // judul wajib diisi max 255 karakter
            'audio' => 'nullable|mimes:mp3|max:250' // audio opsional, format mp3 max 250KB
        ]);

        // Simpan file gambar ke folder storage/app/aksara
        if ($request->file('image')) {
            $validatedData['image'] = $request->file('image')->store('aksara');
        }

        // Simpan file audio ke folder storage/app/audio jika ada
        if ($request->file('audio')) {
            $validatedData['audio'] = $request->file('audio')->store('audio');
        }

        // Simpan data ke tabel materi
        Materi::create($validatedData);

        // Redirect kembali dengan pesan sukses
        return back()->with('addMateriSuccess', 'Materi berhasil ditambah tanpa kategori!');
    }

    /**
     * Update data materi yang sudah ada.
     */
    public function update(Request $request)
    {
        // Validasi input dari form edit materi
        $validatedData = $request->validate([
            'imageEdit' => 'nullable|image|file|max:500', // gambar opsional
            'titleEdit' => 'required|max:255|string', // judul wajib diisi
            'audioEdit' => 'nullable|mimes:mp3|max:250' // audio opsional
        ]);

        // Jika ada file gambar baru, simpan ke folder aksara
        if ($request->file('imageEdit')) {
            $validatedData['image'] = $request->file('imageEdit')->store('aksara');
        }

        // Jika ada file audio baru, simpan ke folder audio
        if ($request->file('audioEdit')) {
            $validatedData['audio'] = $request->file('audioEdit')->store('audio');
        }

        // Ubah nama field agar sesuai dengan kolom database
        $validatedData['title'] = $validatedData['titleEdit'];

        // Hapus field tambahan agar tidak ikut disimpan
        unset($validatedData['titleEdit'], $validatedData['imageEdit'], $validatedData['audioEdit']);

        // Update data berdasarkan ID terenkripsi
        Materi::where('id', decrypt($request->codeMateri))->update($validatedData);

        // Redirect dengan pesan sukses
        return back()->with('editMateriSuccess', 'Materi berhasil diperbarui tanpa kategori!');
    }

    /**
     * Hapus data materi berdasarkan ID terenkripsi.
     */
    public function destroy(Request $request)
    {
        Materi::destroy(decrypt($request->codeMateri)); // hapus data materi
        return back()->with('deleteMateriSuccess', 'Materi berhasil dihapus!');
    }

    /**
     * Fitur pencarian materi berdasarkan keyword.
     */
    public function search()
    {
        // Jika keyword kosong, redirect ke halaman utama data materi
        if (request('q') === null) {
            return redirect('/admin/data-materi');
        }

        // Jika ada keyword, tampilkan hasil pencarian
        return view('admin.datamateri.search', [
            'app' => Application::all(), // data aplikasi
            'title' => 'Data Materi', // judul halaman
            'materis' => Materi::latest()->searching(request('q'))->paginate(10) // hasil pencarian
        ]);
    }
}
