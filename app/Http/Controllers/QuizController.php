<?php

namespace App\Http\Controllers;

use App\Models\Answer_user;
use App\Models\Question;
use App\Models\Result;
use App\Models\Quiz;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\RedirectResponse;
use App\Models\Application;

class QuizController extends Controller
{
    /**
     * Menampilkan daftar kuis aktif di halaman user.
     */
    public function index()
    {
        return view('users.quiz.index', [
            'app' => Application::all(), // Ambil semua data aplikasi (biasanya untuk header/footer)
            'title' => 'Quiz', // Judul halaman
            // Ambil kuis dengan status "Aktif", urutkan terbaru, dan tampilkan 6 per halaman
            'quizzes' => Quiz::where('status', 'Aktif')->latest()->paginate(6)
        ]);
    }

    /**
     * Menyimpan hasil jawaban kuis user ke database.
     * Termasuk perhitungan skor dan jawaban benar/salah.
     */
    public function store(Request $request): RedirectResponse
    {
        // Dekripsi kode kuis yang dikirim dari form (untuk keamanan)
        $idQuiz = decrypt($request->quizCode);

        // Gunakan transaksi agar data tidak setengah tersimpan jika ada error
        DB::beginTransaction();
        try {
            $totalScore = 0; // Inisialisasi skor total

            // Buat record baru di tabel 'results' (hasil kuis)
            $newRecord = Result::create([
                'user_id' => auth()->user()->id,
                'score' => 0,
                'quiz_id' => $idQuiz,
                // Gunakan encrypt agar kode hasil bersifat unik dan aman
                'code' => encrypt(date('Y-m-d H:i:s') . auth()->user()->id)
            ]);

            // Simpan ID result dan kode kuis yang baru dibuat
            $codeQuiz = $newRecord->code;
            $result_id = $newRecord->id;

            // Loop semua jawaban user berdasarkan ID pertanyaan
            foreach ($request->answer as $question_id => $answers_id) {
                $id = $question_id;
                $answer_id = decrypt($answers_id); // Dekripsi ID jawaban

                // Ambil data pertanyaan beserta daftar jawabannya
                $totalQuestion = Question::with(['answer'])->find($id);

                // Default nilai benar/salah dan skor per soal
                $correct = 0;
                $score = 0;

                // Jika jawaban yang dipilih benar (nilai 1 di kolom correct)
                if ($totalQuestion->answer->find($answer_id)->correct === 1) {
                    $totalScore += $totalQuestion->score; // Tambah ke total skor
                    $score = $totalQuestion->score;
                    $correct = 1;
                };

                // Simpan jawaban user ke tabel answer_users
                Answer_user::create([
                    'result_id' => $result_id,
                    'question_id' => $question_id,
                    'user_id' => auth()->user()->id,
                    'answer_id' => $answer_id,
                    'correct' => $correct,
                    'score' =>  $score
                ]);
            };

            // Pastikan pertanyaan yang tidak dijawab juga tersimpan dengan nilai nol
            $quizQuestions = Quiz::find($idQuiz)->question;
            foreach ($quizQuestions as $question) {
                if (!isset($request->answer[$question->id])) {
                    // Jika user tidak menjawab pertanyaan ini
                    Answer_user::create([
                        'result_id' => $result_id,
                        'question_id' => $question->id,
                        'user_id' => auth()->user()->id,
                        'answer_id' => null, // null artinya tidak menjawab
                        'correct' => 0, // otomatis salah
                        'score' => 0, // skor nol
                    ]);
                };
            };

            // Update total skor ke tabel result
            $updateScore = Result::find($result_id);
            $updateScore->score = $totalScore;
            $updateScore->save();

            // Commit transaksi jika semua berhasil
            DB::commit();

            // Arahkan ke halaman detail nilai user
            return redirect('/nilai/details/' . $codeQuiz);
        } catch (\Exception $e) {
            // Rollback semua jika terjadi error
            DB::rollBack();
            // Redirect kembali ke halaman kuis dengan pesan error
            return redirect('/quiz/start/' . decrypt($request->bubblesmart))->with('messages', 'Kerjakan minimal satu soal!');
        }
    }

    /**
     * Menampilkan detail soal dan jawaban dari kuis yang dipilih user.
     */
    public function show(Quiz $quiz)
    {
        // Ambil semua pertanyaan berdasarkan quiz_id dan sertakan relasi jawaban
        $questions = \App\Models\Question::where('quiz_id', $quiz->id)
            ->with(['answer'])
            ->get();

        // Kirim data ke view kuis (halaman mengerjakan kuis)
        return view('users.quiz.start', [
            'app' => \App\Models\Application::all(), // data aplikasi
            'title' => 'Quiz', // judul halaman
            'titleQuiz' => $quiz->title, // judul kuis yang sedang dikerjakan
            'codeQuiz' => encrypt($quiz->id), // enkripsi ID kuis untuk keamanan
            'bubblesmart' => encrypt($quiz->slug), // enkripsi slug kuis
            'quizzes' => $questions // kumpulan pertanyaan & jawabannya
        ]);
    }
}
