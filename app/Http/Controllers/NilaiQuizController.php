<?php

namespace App\Http\Controllers;

use App\Models\Answer_user;
use App\Models\Question;
use App\Models\Quiz;
use Illuminate\Http\Request;
use App\Models\Result;
use App\Models\Application;

class NilaiQuizController extends Controller
{
    /**
     * Menampilkan daftar hasil/nilai quiz pengguna yang sedang login.
     * Dapat difilter berdasarkan nilai tertinggi (teratas) atau terendah (terendah).
     */
    public function index()
    {
        // Ambil data hasil quiz beserta relasi quiz dan jawaban pengguna
        $query = Result::with(['quiz', 'answer_user']);

        // Filter hasil berdasarkan parameter 'filter' dari request
        if (request('filter') === 'teratas') {
            $query = $query->orderBy('score', 'desc'); // Urutkan nilai dari tertinggi ke terendah
        }
        if (request('filter') === 'terendah') {
            $query = $query->orderBy('score', 'asc'); // Urutkan nilai dari terendah ke tertinggi
        }

        // Kirim data ke view dengan hasil sesuai user yang sedang login
        return view('users.nilai.index', [
            'app' => Application::all(),
            'title' => 'Nilai Quiz',
            'histories' => $query->where('user_id', auth()->user()->id)->latest()->paginate(10)
        ]);
    }

    /**
     * Melakukan pencarian nilai quiz berdasarkan kata kunci.
     */
    public function search()
    {
        // Jika kolom pencarian kosong, arahkan kembali ke halaman utama nilai
        if (request('q') === null) {
            return redirect('/nilai');
            exit;
        }

        // Tampilkan hasil pencarian berdasarkan input user
        return view('users.nilai.search', [
            'app' => Application::all(),
            'title' => 'Nilai Quiz',
            'histories' => Result::with(['quiz', 'answer_user'])
                ->where('user_id', auth()->user()->id)
                ->latest()
                ->searching(request('q')) // Memanggil scope searching dari model
                ->paginate(10)
        ]);
    }

    /**
     * Menampilkan detail dari hasil quiz tertentu, termasuk pertanyaan, jawaban, dan skor.
     */
    public function show(Result $nilai)
    {
        // Ambil ID quiz dari hasil yang dipilih
        $quiz_id = Result::find($nilai->id)->quiz_id;

        // Kirim data detail ke halaman detail quiz
        return view('users.nilai.detail', [
            'app' => Application::all(),
            'title' => 'Detail Quiz',
            'tanggalMengerjakanQuiz' => Result::find($nilai->id)->created_at, // Tanggal user mengerjakan quiz
            'titleQuiz' => Quiz::find($quiz_id)->title, // Judul quiz
            'correct' => Answer_user::where('result_id', $nilai->id)->where('correct', 1), // Jawaban benar
            'totalScore' => Result::find($nilai->id)->score, // Total skor yang didapat
            'scores' => Answer_user::with(['answer', 'result', 'user', 'question'])
                ->where('result_id', $nilai->id)->get(), // Data jawaban user untuk setiap pertanyaan
            'questions' => Question::with(['answer'])
                ->where('quiz_id', $quiz_id)->get() // Daftar pertanyaan dari quiz terkait
        ]);
    }

    /**
     * Menghapus histori hasil quiz beserta data jawaban user yang terkait.
     */
    public function destroy(Result $result)
    {
        // Hapus semua jawaban user berdasarkan ID hasil quiz
        Answer_user::where('result_id', $result->id)->delete();

        // Hapus data hasil quiz dari tabel result
        Result::destroy($result->id);

        // Kembalikan ke halaman nilai dengan pesan sukses
        return redirect('/nilai')->with('messages', 'Histori quiz berhasil dihapus!');
    }
}
