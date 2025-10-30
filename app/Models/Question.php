<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class Question
 *
 * Model ini merepresentasikan tabel `questions` di database.
 * Tabel ini menyimpan data pertanyaan yang berhubungan dengan kuis (Quiz) dan jawaban (Answer).
 * Setiap pertanyaan dapat memiliki banyak jawaban dan jawaban pengguna.
 */
class Question extends Model
{
    use HasFactory; // Mengaktifkan fitur factory untuk pembuatan data dummy dan testing.

    /**
     * Atribut yang tidak boleh diisi secara mass assignment.
     * Dalam hal ini, kolom 'id' dikecualikan karena otomatis diisi oleh database.
     */
    protected $guarded = ['id'];

    /**
     * Scope untuk melakukan pencarian berdasarkan kata kunci tertentu.
     * Pencarian dapat dilakukan pada kolom pertanyaan, tanggal pembuatan, atau jawaban yang berhubungan.
     *
     * Contoh penggunaan:
     * Question::searching($keyword)->get();
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string|null $keyword
     * @return void
     */
    public function scopeSearching($query, $keyword)
    {
        $query->when($keyword, function ($query, $keyword) {
            return $query->where('question', 'like', '%' . $keyword . '%') // Cari berdasarkan teks pertanyaan
                ->orWhere('created_at', 'like', '%' . $keyword . '%') // Atau berdasarkan tanggal pembuatan
                ->orWhereHas('answer', function ($query) use ($keyword) { // Cari juga berdasarkan jawaban terkait
                    return $query->where('answer', 'like', '%' . $keyword . '%')
                                 ->orWhere('updated_at', 'like', '%' . $keyword . '%');
                });
        });
    }

    /**
     * Relasi One to Many ke model Answer.
     * Satu pertanyaan dapat memiliki banyak jawaban.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function answer(): HasMany
    {
        return $this->hasMany(Answer::class);
    }

    /**
     * Relasi Many to One ke model Quiz.
     * Setiap pertanyaan merupakan bagian dari satu kuis tertentu.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function quiz(): BelongsTo
    {
        return $this->belongsTo(Quiz::class);
    }

    /**
     * Relasi One to Many ke model Answer_user.
     * Setiap pertanyaan dapat memiliki banyak jawaban dari pengguna (user answers).
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function answer_user(): HasMany
    {
        return $this->hasMany(Answer_user::class);
    }
}
