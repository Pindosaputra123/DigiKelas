<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class Answer_user
 *
 * Model ini merepresentasikan tabel `answer_users` di database.
 * Setiap entri pada tabel ini menyimpan jawaban yang diberikan oleh pengguna (user)
 * terhadap pertanyaan (question) tertentu pada kuis (quiz) yang mereka kerjakan.
 *
 * Model ini berperan penting dalam menghubungkan antara:
 * - User yang menjawab pertanyaan
 * - Question yang dijawab
 * - Answer yang dipilih
 * - Result hasil akhir kuis pengguna
 */
class Answer_user extends Model
{
    use HasFactory; // Mengaktifkan fitur factory untuk pembuatan data dummy (seeding/testing).

    /**
     * Atribut yang tidak dapat diisi secara mass assignment.
     * 'id' diabaikan karena nilainya otomatis diisi oleh database (auto increment).
     *
     * @var array<int, string>
     */
    protected $guarded = ['id'];

    /**
     * Relasi Many-to-One ke model Result.
     * Setiap jawaban pengguna terkait dengan satu hasil kuis (result).
     * 
     * Contoh: satu hasil kuis (result_id) bisa memiliki banyak jawaban.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function result(): BelongsTo
    {
        return $this->belongsTo(Result::class);
    }

    /**
     * Relasi Many-to-One ke model User.
     * Menunjukkan bahwa setiap jawaban dikirim oleh satu pengguna tertentu.
     *
     * Contoh: user_id digunakan untuk mengidentifikasi siapa yang memberikan jawaban.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relasi Many-to-One ke model Answer.
     * Menunjukkan bahwa jawaban yang dipilih oleh pengguna berasal dari tabel Answer.
     *
     * Contoh: answer_id mengacu ke opsi jawaban yang dipilih pengguna.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function answer(): BelongsTo
    {
        return $this->belongsTo(Answer::class);
    }

    /**
     * Relasi Many-to-One ke model Question.
     * Menunjukkan pertanyaan mana yang dijawab oleh pengguna.
     *
     * Contoh: question_id digunakan untuk melacak pertanyaan terkait.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function question(): BelongsTo
    {
        return $this->belongsTo(Question::class);
    }
}
