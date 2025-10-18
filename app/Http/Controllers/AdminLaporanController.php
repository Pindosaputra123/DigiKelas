<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Application;
use App\Models\Quiz;
use App\Models\Result;
use App\Models\Answer_user;
use App\Models\Question;

/**
 * Controller untuk mengelola laporan data akses quiz oleh admin.
 */
class AdminLaporanController extends Controller
{
    /**
     * Menampilkan daftar semua quiz yang memiliki laporan akses.
     * Ditampilkan secara paginasi 10 data per halaman.
     */
    public function index()
    {
        return view('admin.laporan.index', [
            'app' => Application::all(), // Data informasi aplikasi
            'title' => 'Laporan Data Akses Quiz', // Judul halaman
            'reports' => Quiz::with(['result'])->latest()->paginate(10) // Ambil semua quiz beserta hasilnya
        ]);
    }

    /**
     * Menampilkan data laporan akses untuk quiz tertentu berdasarkan slug.
     * Terdapat fitur filter berdasarkan nilai tertinggi atau terendah.
     */
    public function show(Quiz $quiz)
    {
        // Query dasar untuk mengambil hasil quiz (tabel results)
        $query = Result::with(['quiz', 'answer_user']);

        // Filter nilai tertinggi
        if (request('filter') === 'tertinggi') {
            $query = $query->orderBy('score', 'desc');
        }

        // Filter nilai terendah
        if (request('filter') === 'terendah') {
            $query = $query->orderBy('score', 'asc');
        }

        // Tampilkan view laporan akses quiz
        return view('admin.laporan.access', [
            'app' => Application::all(),
            'title' => 'Laporan Data Akses Quiz',
            'dataquiz' => $quiz, // Informasi quiz yang dipilih
            'reports' => $query
                ->where('quiz_id', $quiz->id) // Filter berdasarkan quiz terkait
                ->with(['user', 'answer_user', 'quiz'])
                ->latest()
                ->paginate(10)
        ]);
    }

    /**
     * Menampilkan detail pengerjaan quiz oleh pengguna tertentu.
     */
    public function details(Result $nilai)
    {
        // Ambil quiz_id berdasarkan hasil quiz (result)
        $quiz_id = Result::find($nilai->id)->quiz_id;

        return view('admin.laporan.details', [
            'app' => Application::all(),
            'title' => 'Detail Quiz',
            'dataresult' => Result::find($nilai->id), // Data hasil satu pengguna
            'dataquiz' => Quiz::find($quiz_id), // Data quiz terkait
            'correct' => Answer_user::where('result_id', $nilai->id)->where('correct', 1), // Jawaban benar
            'totalScore' => Result::find($nilai->id)->score, // Total nilai
            'scores' => Answer_user::with(['answer', 'result', 'user', 'question'])
                ->where('result_id', $nilai->id)->get(), // Detail jawaban user
            'questions' => Question::with(['answer'])
                ->where('quiz_id', $quiz_id)->get() // List soal quiz
        ]);
    }

    /**
     * Fitur pencarian data peserta yang mengakses quiz berdasarkan nama.
     */
    public function searchAccess(Quiz $quiz)
    {
        // Jika input pencarian kosong, redirect kembali ke halaman laporan quiz
        if (request('q') === null) {
            return redirect('/admin/laporan/' . $quiz->slug);
            exit;
        }

        // Tampilkan hasil pencarian data peserta quiz
        return view('admin.laporan.search_access', [
            'app' => Application::all(),
            'title' => 'Laporan Data Akses Quiz',
            'dataquiz' => $quiz,
            'reports' => Result::where('quiz_id', $quiz->id)
                ->with(['user', 'answer_user', 'quiz'])
                ->latest()
                ->searchingAccess(request('q')) // Scope pencarian
                ->paginate(10)
        ]);
    }
}
