<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Class Result
 *
 * Model ini merepresentasikan tabel `results` di database.
 * Setiap entri pada tabel ini berisi hasil dari pengerjaan kuis oleh pengguna (user).
 * Model ini juga berhubungan dengan User, Quiz, dan Answer_user.
 */
class Result extends Model
{
    use HasFactory; // Mengaktifkan fitur factory untuk pembuatan data dummy atau testing otomatis.

    /**
     * Atribut yang tidak dapat diisi secara mass assignment.
     * 'id' diabaikan karena sudah otomatis diatur oleh database (auto increment).
     *
     * @var array<int, string>
     */
    protected $guarded = ['id'];

    /**
     * Scope untuk fitur pencarian hasil kuis berdasarkan admin.
     * Admin dapat mencari data berdasarkan:
     * - Nilai (score)
     * - Tanggal dibuat (created_at)
     * - Judul atau deskripsi kuis (melalui relasi dengan Quiz)
     *
     * Contoh penggunaan:
     * Result::searching($keyword)->get();
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string|null $keyword
     * @return void
     */
    public function scopeSearching($query, $keyword)
    {
        $query->when($keyword, function ($query, $keyword) {
            return $query->where('score', 'like', '%' . $keyword . '%') // Cari berdasarkan skor
                ->orWhere('created_at', 'like', '%' . $keyword . '%')   // Cari berdasarkan tanggal
                ->orWhereHas('quiz', function ($query) use ($keyword) { // Cari berdasarkan relasi Quiz
                    return $query->where('title', 'like', '%' . $keyword . '%')
                        ->orWhere('description', 'like', '%' . $keyword . '%');
                });
        });
    }

    /**
     * Scope tambahan untuk pencarian hasil kuis oleh pengguna biasa (bukan admin).
     * User dapat mencari berdasarkan skor, tanggal, dan data pengguna (melalui relasi User).
     *
     * Contoh penggunaan:
     * Result::searchingAccess($keyword)->get();
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string|null $keyword
     * @return void
     */
    public function scopeSearchingAccess($query, $keyword)
    {
        $query->when($keyword, function ($query, $keyword) {
            return $query->where('score', 'like', '%' . $keyword . '%')
                ->orWhere('created_at', 'like', '%' . $keyword . '%')
                ->orWhereHas('user', function ($query) use ($keyword) { // Cari berdasarkan relasi User
                    return $query->where('name', 'like', '%' . $keyword . '%')
                        ->orWhere('gender', 'like', '%' . $keyword . '%');
                });
        });
    }

    /**
     * Relasi Many-to-One ke model User.
     * Setiap hasil kuis dimiliki oleh satu pengguna.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relasi One-to-Many ke model Answer_user.
     * Setiap hasil kuis memiliki banyak jawaban yang dikumpulkan oleh pengguna.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function answer_user(): HasMany
    {
        return $this->hasMany(Answer_user::class);
    }

    /**
     * Relasi Many-to-One ke model Quiz.
     * Setiap hasil kuis berkaitan dengan satu kuis tertentu.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function quiz(): BelongsTo
    {
        return $this->belongsTo(Quiz::class);
    }
}
