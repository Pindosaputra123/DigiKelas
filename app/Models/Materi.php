<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Materi
 *
 * Model ini merepresentasikan tabel `materis` di database.
 * Tabel ini digunakan untuk menyimpan data materi pembelajaran, seperti judul, deskripsi, gambar, atau audio (jika ada).
 * Model ini juga menyediakan fitur pencarian (searching) berdasarkan judul materi.
 */
class Materi extends Model
{
    use HasFactory; // Mengaktifkan fitur factory untuk pembuatan data otomatis dalam seeding dan testing.

    /**
     * Atribut yang tidak boleh diisi secara mass assignment.
     *
     * Dalam hal ini, kolom 'id' tidak boleh diisi manual karena bersifat auto increment oleh database.
     */
    protected $guarded = ['id'];

    /**
     * Scope untuk melakukan pencarian data berdasarkan keyword.
     * Metode ini memungkinkan pencarian fleksibel dengan kata kunci yang diberikan pengguna.
     *
     * Contoh penggunaan di controller:
     * Materi::searching($keyword)->get();
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string|null $keyword
     * @return void
     */
    public function scopeSearching($query, $keyword)
    {
        $query->when($keyword, function ($query, $keyword) {
            // Mencari data materi berdasarkan judul yang mirip dengan keyword
            return $query->where('title', 'like', '%' . $keyword . '%');
        });
    }
}
