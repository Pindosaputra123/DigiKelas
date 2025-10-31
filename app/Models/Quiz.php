<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Quiz
 *
 * Model ini merepresentasikan tabel `quizzes` di database.
 * Setiap entri dalam tabel ini merupakan data kuis yang berisi judul, deskripsi, dan status.
 * Kuis ini memiliki hubungan dengan model Question dan Result.
 */
class Quiz extends Model
{
    use HasFactory; // Mengaktifkan fitur factory untuk membuat data dummy dan pengujian otomatis.

    /**
     * Atribut yang tidak boleh diisi secara mass assignment.
     * Dalam hal ini, kolom 'id' dikecualikan karena akan diatur otomatis oleh database.
     *
     * @var array<int, string>
     */
    protected $guarded = ['id'];

    /**
     * Scope pencarian dinamis untuk model Quiz.
     * Fitur ini memungkinkan admin mencari data kuis berdasarkan beberapa kolom:
     * - status
     * - tanggal pembuatan (created_at)
     * - judul kuis (title)
     * - deskripsi kuis (description)
     *
     * Contoh penggunaan:
     * Quiz::searching($keyword)->get();
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string|null $keyword
     * @return void
     */
    public function scopeSearching($query, $keyword)
    {
        $query->when($keyword, function ($query, $keyword) {
            return $query->where('status', 'like', '%' . $keyword . '%') // Cari berdasarkan status
                ->orWhere('created_at', 'like', '%' . $keyword . '%')   // Atau berdasarkan tanggal pembuatan
                ->orWhere('title', 'like', '%' . $keyword . '%')        // Atau berdasarkan judul
                ->orWhere('description', 'like', '%' . $keyword . '%'); // Atau berdasarkan deskripsi
        });
    }

    /**
     * Relasi One-to-Many dengan model Question.
     * Satu kuis dapat memiliki banyak pertanyaan (Question).
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function question(): HasMany
    {
        return $this->hasMany(Question::class);
    }

    /**
     * Relasi One-to-Many dengan model Result.
     * Satu kuis dapat memiliki banyak hasil (Result) yang disimpan berdasarkan pengguna yang menyelesaikan kuis.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function result(): HasMany
    {
        return $this->hasMany(Result::class);
    }
}
