<?php

namespace App\Http\Controllers;

use App\Models\Question;
use App\Models\Answer;
use App\Models\Answer_user;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use App\Models\Quiz;
use Illuminate\Support\Facades\DB;
use App\Models\Application;

class AdminDataQuizController extends Controller
{
    /**
     * Menampilkan daftar semua quiz untuk admin.
     */
    public function index()
    {
        return view('admin.dataquiz.index', [
            'app' => Application::all(), // Data aplikasi global
            'title' => 'Data Quiz', // Judul halaman
            'allQuiz' => Quiz::latest()->paginate(10) // Ambil semua quiz dengan pagination
        ]);
    }

    /**
     * Tambah quiz baru ke database.
     */
    public function store(Request $request): RedirectResponse
    {
        // Validasi data quiz baru
        $validated = $request->validate([
            'title' => 'required|max:255',
            'description' => 'required|max:255|min:100',
        ]);

        // Generate slug terenkripsi untuk ID unik
        $validated['slug'] = encrypt(date('Y-m-d H i s'));
        $validated['status'] = "Nonaktif"; // Default status quiz baru

        // Simpan quiz ke database
        Quiz::create($validated);
        return redirect('/admin/data-quiz')->with('quizSuccess', 'Quiz berhasil ditambahkan!');
    }

    /**
     * Menampilkan halaman detail soal & jawaban quiz.
     */
    public function show(Quiz $quiz)
    {
        return view('admin.dataquiz.show', [
            'app' => Application::all(),
            'title' => 'Soal & Jawaban Quiz',
            'code' => $quiz->slug,
            'titleQuiz' => $quiz->title,
            'questions' => $quiz->question()->with(['answer', 'quiz'])->latest()->paginate(10)
        ]);
    }

    /**
     * Tambah pertanyaan (soal) baru ke dalam quiz.
     */
    public function addquestion(Quiz $quiz)
    {
        // Cek apakah quiz sudah pernah dijawab oleh user
        $questions = $quiz->question->where('quiz_id', $quiz->id)->first();
        if ($questions) {
            $answerUserCount = Answer_user::where('question_id', $questions->id)->count();
            if ($answerUserCount > 0) {
                // Jika sudah digunakan user, batalkan update
                return redirect('/admin/data-quiz/q&a/' . $quiz->slug)->with('updateQuestionError', 'Data quiz digunakan users!');
                exit;
            }
        }

        // Validasi input soal dan opsi jawaban
        $validated = Request()->validate([
            'question' => 'Required',
            'score' => 'Required|numeric|min:1|max:100',
            'option.1' => 'Required|max:255',
            'option.2' => 'Required|max:255',
            'option.3' => 'Required|max:255',
            'option.4' => 'Required|max:255',
            'correctAnswer' => 'Required|in:1,2,3,4'
        ], [
            // Pesan error kustom untuk tiap field
            'option.1.required' => 'The option 1 field is required.',
            'option.2.required' => 'The option 2 field is required.',
            'option.3.required' => 'The option 3 field is required.',
            'option.4.required' => 'The option 4 field is required.',
        ]);

        // Gunakan transaksi agar data konsisten
        DB::beginTransaction();
        try {
            // Tambahkan pertanyaan baru
            $newRecordQuestion = Question::create([
                'quiz_id' => $quiz->id,
                'question' => $validated['question'],
                'score' => $validated['score']
            ]);

            $question_id = $newRecordQuestion->id; // ID pertanyaan baru

            // Tambahkan jawaban berdasarkan input option
            foreach (Request()->option as $index => $opsi) {
                $correct = ($index == Request()->correctAnswer) ? 1 : 0;
                Answer::create([
                    'question_id' => $question_id,
                    'answer' => $opsi,
                    'correct' => $correct
                ]);
            }
            DB::commit(); // Simpan perubahan
            return redirect('/admin/data-quiz/q&a/' . $quiz->slug)->with('questionSuccess', 'Soal berhasil ditambahkan!');
        } catch (\Exception $e) {
            DB::rollBack(); // Batalkan jika gagal
            return redirect('/admin/data-quiz/q&a/' . $quiz->slug)->with('questionFailed', 'Upss..Terjadi kesalahan!');
        }
    }

    /**
     * Update data quiz (judul, deskripsi, status).
     */
    public function update(Request $request)
    {
        // Validasi input quiz
        $validated = $request->validate([
            'titleQuiz' => 'required|max:255',
            'descriptionQuiz' => 'required|max:255|min:100',
            'status' => 'required|in:Aktif,Nonaktif',
        ]);

        // Map field ke kolom database
        $validated['title'] = $validated['titleQuiz'];
        $validated['description'] = $validated['descriptionQuiz'];
        unset($validated['titleQuiz'], $validated['descriptionQuiz']);

        // Update data quiz berdasarkan slug
        Quiz::where('slug', $request->codeQuiz)->update($validated);
        return back()->with('updateQuizSuccess', 'Quiz berhasil diupdate!');
    }

    /**
     * Update soal dan opsi jawaban.
     */
    public function updatequestion(Request $request)
    {
        // Validasi form edit soal
        $validated = $request->validate([
            'editQuestion' => 'Required',
            'editScore' => 'Required|numeric|min:1|max:100',
            'editOption.1' => 'Required|max:255',
            'editOption.2' => 'Required|max:255',
            'editOption.3' => 'Required|max:255',
            'editOption.4' => 'Required|max:255',
            'editCorrectAnswer' => 'Required|in:1,2,3,4'
        ]);

        // Siapkan data untuk update
        $validated['question'] = $validated['editQuestion'];
        $validated['score'] = $validated['editScore'];
        unset($validated['editQuestion'], $validated['editScore']);

        $quizID = Question::find(decrypt($request->codeQuestion))->quiz_id;
        $quiz = Quiz::find($quizID);
        $slugUrl = $quiz->slug;

        // Cek apakah soal pernah dijawab user
        $answerUserCount = Answer_user::where('question_id', decrypt($request->codeQuestion))->count();
        if ($answerUserCount > 0) {
            return redirect('/admin/data-quiz/q&a/' . $slugUrl)->with('updateQuestionError', 'Data Soal digunakan users!');
            exit;
        }

        // Transaksi database untuk update
        DB::beginTransaction();
        try {
            $question_id = decrypt($request->codeQuestion);
            // Update pertanyaan
            Question::where('id', $question_id)->update([
                'question' => $validated['question'],
                'score' => $validated['score']
            ]);

            // Hapus semua jawaban lama
            Answer::where('question_id', $question_id)->delete();

            // Tambahkan jawaban baru
            foreach ($request->editOption as $index => $opsi) {
                $correct = ($index == $request->editCorrectAnswer) ? 1 : 0;
                Answer::create([
                    'question_id' => $question_id,
                    'answer' => $opsi,
                    'correct' => $correct
                ]);
            }
            DB::commit();
            return back()->with('updateQuestionSuccess', 'Soal berhasil diupdate!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('editQuestionFailed', 'Upss..Terjadi kesalahan!');
        }
    }

    /**
     * Hapus quiz beserta soal dan jawaban jika belum digunakan user.
     */
    public function destroy(Quiz $quiz)
    {
        $questions = Question::with(['answer_user'])->where('quiz_id', $quiz->id)->get();
        $deleteQuizError = false;

        // Periksa apakah ada soal yang sudah digunakan user
        foreach ($questions as $question) {
            $answerUserCount = Answer_user::where('question_id', $question->id)->count();
            if ($answerUserCount === 0) {
                Answer::where('question_id', $question->id)->delete();
            } else {
                $deleteQuizError = true;
            }
        }

        // Jika quiz digunakan user, batalkan penghapusan
        if ($deleteQuizError) {
            return back()->with('deleteQuizError', 'Data quiz digunakan users!');
            exit;
        }

        // Hapus semua soal dan quiz
        Question::where('quiz_id', $quiz->id)->delete();
        Quiz::destroy($quiz->id);
        return back()->with('deleteQuizSuccess', 'Quiz berhasil dihapus!');
    }

    /**
     * Hapus soal tertentu dari quiz jika belum digunakan user.
     */
    public function destroyquestion(Question $question)
    {
        $answerUserCount = Answer_user::where('question_id', $question->id)->count();
        if ($answerUserCount === 0) {
            Answer::where('question_id', $question->id)->delete();
        } else {
            return redirect('/admin/data-quiz/q&a/' . $question->quiz->slug)->with('deleteQuestionError', 'Data soal digunakan users!');
            exit;
        }
        Question::destroy($question->id);
        return back()->with('deleteQuestion', 'Soal berhasil dihapus!');
    }

    /**
     * AJAX: Ambil daftar jawaban berdasarkan ID pertanyaan.
     */
    public function getanswer(Request $request)
    {
        $id = decrypt($request->id);
        $data = Answer::where('question_id', $id)->get();
        return $data;
    }

    /**
     * Pencarian quiz berdasarkan keyword.
     */
    public function search()
    {
        if (request('q') === null) {
            return redirect('/admin/data-quiz');
            exit;
        }

        return view('admin.dataquiz.search', [
            'app' => Application::all(),
            'title' => 'Data Quiz',
            'allQuiz' => Quiz::latest()->searching(request('q'))->paginate(10)
        ]);
    }

    /**
     * Pencarian soal dalam satu quiz tertentu.
     */
    public function searchquestion(Quiz $quiz)
    {
        if (request('q') === null) {
            return redirect('/admin/data-quiz/q&a/' . $quiz->slug);
            exit;
        }

        return view('admin.dataquiz.searchquestion', [
            'app' => Application::all(),
            'title' => 'Soal & Jawaban Quiz',
            'code' => $quiz->slug,
            'titleQuiz' => $quiz->title,
            'questions' => $quiz->question()->with(['answer', 'quiz'])->latest()->searching(request('q'))->paginate(10)
        ]);
    }
}
