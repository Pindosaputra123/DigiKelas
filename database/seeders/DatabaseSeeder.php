<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Quiz;
use App\Models\User;
use App\Models\Answer;
use App\Models\Question;
use App\Models\Materi;
use App\Models\Application;
use App\Models\Thread;
use App\Models\Comment;
use App\Models\Like;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Applikasi
        Application::create([
            'name_app' => 'DigiKelas',
            'description_app' => 'DikiKelas adalah platform belajar bahasa Jepang terpercaya nomor satu di Indonesia.'
        ]);



        // User
        User::create([
            'name' => 'Pindo Saputra',
            'email' => 'pindo@gmail.com',
            'username' => 'pindo',
            'image' => 'profil-images/5.jpeg',
            'is_admin' => 1,
            'gender' => 'Laki-Laki',
            'password' => bcrypt('pindo123')
        ]);

        User::create([
            'name' => 'Dunik Andriyani',
            'email' => 'dunik@gmail.com',
            'username' => 'dunik',
            'image' => 'profil-images/1.jpeg',
            'gender' => 'Perempuan',
            'password' => bcrypt('dunik123')
        ]);

        User::create([
            'name' => 'Ilham Syihabudin',
            'email' => 'ilham@gmail.com',
            'username' => 'ilham',
            'image' => 'profil-images/6.jpg',
            'gender' => 'Laki-Laki',
            'password' => bcrypt('ilham123')
        ]);



        // materi
        Materi::create([
            'image' => 'assets/img/aksara/aksara_ba.png',
            'title' => 'Ba',
            'audio' => 'assets/audio/jakarta.mp3',
        ]);



        // quiz
        Quiz::create([
            'title' => 'Belajar Bahasa Jepang Dasar',
            'description' => 'Bahasa Jepang Dasar untuk Pemula',
            'slug' => encrypt('jepang')
        ]);

        // soal 1
        Question::create([
            'title' => 'Huruf apa yang digunakan untuk menulis kata-kata asli bahasa Jepang?',
            'score' => '20',
            'quiz_id' => 1
        ]);
        Answer::create([
            'question_id' => 1,
            'answer' => 'Kanji'
        ]);
        Answer::create([
            'question_id' => 1,
            'answer' => 'Hiragana',
            'correct' => 1
        ]);
        Answer::create([
            'question_id' => 1,
            'answer' => 'Katakana'
        ]);
        Answer::create([
            'question_id' => 1,
            'answer' => 'Romaji'
        ]);

        // soal 2
        Question::create([
            'title' => 'Bagaimana cara mengucapkan salam "selamat pagi" dalam bahasa Jepang?',
            'score' => '20',
            'quiz_id' => 1
        ]);
        Answer::create([
            'question_id' => 2,
            'answer' => 'Konnichiwa'
        ]);
        Answer::create([
            'question_id' => 2,
            'answer' => 'Ohayou gozaimasu',
            'correct' => 1
        ]);
        Answer::create([
            'question_id' => 2,
            'answer' => 'Sayounara'
        ]);
        Answer::create([
            'question_id' => 2,
            'answer' => 'Arigatou'
        ]);

        // soal 3
        Question::create([
            'title' => 'Kata “Arigatou” berarti apa dalam bahasa Indonesia?',
            'score' => '20',
            'quiz_id' => 1
        ]);
        Answer::create([
            'question_id' => 3,
            'answer' => 'Selamat tinggal'
        ]);
        Answer::create([
            'question_id' => 3,
            'answer' => 'Maaf'
        ]);
        Answer::create([
            'question_id' => 3,
            'answer' => 'Terima kasih',
            'correct' => 1
        ]);
        Answer::create([
            'question_id' => 3,
            'answer' => 'Tolong'
        ]);

        // soal 4
        Question::create([
            'title' => 'Bagaimana cara mengatakan "nama saya...." dalam bahasa Jepang?',
            'score' => '20',
            'quiz_id' => 1
        ]);
        Answer::create([
            'question_id' => 4,
            'answer' => 'Kore wa ..... desu'
        ]);
        Answer::create([
            'question_id' => 4,
            'answer' => 'Anata wa ..... desu'
        ]);
        Answer::create([
            'question_id' => 4,
            'answer' => 'Watashi wa ..... desu',
            'correct' => 1
        ]);
        Answer::create([
            'question_id' => 4,
            'answer' => 'Konnichiwa wa ..... desu'
        ]);

        // soal 5
        Question::create([
            'title' => 'Apa arti dari kata "Sensei"?',
            'score' => '20',
            'quiz_id' => 1
        ]);
        Answer::create([
            'question_id' => 5,
            'answer' => 'Teman'
        ]);
        Answer::create([
            'question_id' => 5,
            'answer' => 'Siswa'
        ]);
        Answer::create([
            'question_id' => 5,
            'answer' => 'Guru',
            'correct' => 1
        ]);
        Answer::create([
            'question_id' => 5,
            'answer' => 'Orang tua'
        ]);
    }
}
