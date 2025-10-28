<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Application
 *
 * Model ini merepresentasikan tabel `applications` di database.
 * Model ini digunakan untuk mengelola data aplikasi (misalnya data pendaftaran,
 * data permintaan pengguna, atau entitas aplikasi lain tergantung konteks proyek).
 */
class Application extends Model
{
    use HasFactory; // Mengaktifkan fitur factory agar model bisa digunakan dalam seeder dan testing.

    /**
     * Atribut yang tidak boleh diisi secara mass assignment.
     *
     * Dalam kasus ini, kolom `id` tidak boleh diisi manual
     * karena akan di-generate otomatis oleh database (auto increment).
     */
    protected $guarded = ['id'];
}
