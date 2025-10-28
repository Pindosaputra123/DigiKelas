<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * Atribut yang tidak boleh diisi secara massal (mass assignment).
     * Dengan demikian, data 'id' tidak bisa diubah secara langsung melalui input user.
     */
    protected $guarded = ['id'];

    /**
     * Atribut yang disembunyikan ketika model dikonversi ke JSON atau array.
     * Biasanya untuk menjaga keamanan agar data sensitif seperti password tidak tampil di API response.
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Atribut yang akan otomatis dikonversi (cast) ke tipe data tertentu.
     * - email_verified_at → otomatis menjadi instance tanggal (Carbon)
     * - password → otomatis di-hash oleh Laravel saat disimpan
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    /**
     * Scope pencarian dinamis (digunakan untuk fitur search di tabel user).
     * Mencari data berdasarkan nama, email, alamat, tanggal lahir, gender, dan username.
     */
    public function scopeSearching($query, $keyword)
    {
        $query->when($keyword, function ($query, $keyword) {
            return $query->where('name', 'like', '%' . $keyword . '%')
                ->orWhere('email', 'like', '%' . $keyword . '%')
                ->orWhere('alamat', 'like', '%' . $keyword . '%')
                ->orWhere('tanggal_lahir', 'like', '%' . $keyword . '%')
                ->orWhere('gender', 'like', '%' . $keyword . '%')
                ->orWhere('username', 'like', '%' . $keyword . '%');
        });
    }

    /**
     * Relasi One to Many antara User dan Result.
     * Satu user bisa memiliki banyak hasil kuis (Result).
     */
    public function result(): HasMany
    {
        return $this->hasMany(Result::class);
    }

    /**
     * Relasi One to Many antara User dan Answer_user.
     * Menyimpan semua jawaban yang diberikan user untuk setiap soal kuis.
     */
    public function answer_user(): HasMany
    {
        return $this->hasMany(Answer_user::class);
    }
}
