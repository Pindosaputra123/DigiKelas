<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Class Answer
 *
 * Model ini merepresentasikan tabel `answers` di database.
 * Setiap data di tabel ini menyimpan jawaban (answer) yang berkaitan dengan pertanyaan (question).
 * Model ini juga memiliki relasi ke model Answer_user yang menyimpan jawaban yang diberikan oleh pengguna.
 */
class Answer extends Model
{
    use HasFactory; // Mengaktifkan fitur factory agar model dapat digunakan dalam seeder dan testing otomatis.

    /**
     * Atribut yang tidak boleh diisi secara mass assignment.
     *
     * Dalam hal ini, kolom 'id' tidak boleh diisi manual karena sudah diatur auto increment oleh database.
     */
    protected $guarded = ['id'];

    /**
     * Relasi ke model Question.
     * Setiap Answer dimiliki oleh satu Question.
     *
     * Contoh penggunaan:
     * $answer->question->text;
     *
     * @return BelongsTo
     */
    public function question(): BelongsTo
    {
        return $this->belongsTo(Question::class);
    }

    /**
     * Relasi ke model Answer_user.
     * Setiap Answer dapat memiliki banyak entri jawaban dari pengguna (user answers).
     *
     * Contoh penggunaan:
     * $answer->answer_user; // Mengambil semua jawaban pengguna terkait
     *
     * @return HasMany
     */
    public function answer_user(): HasMany
    {
        return $this->hasMany(Answer_user::class);
    }
}
