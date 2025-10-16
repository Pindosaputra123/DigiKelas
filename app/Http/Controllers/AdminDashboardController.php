<?php

namespace App\Http\Controllers;

use App\Charts\AnswerQuizChart; // Import class chart untuk menampilkan grafik hasil quiz di dashboard
use App\Models\Quiz;            // Model untuk mengambil data quiz
use App\Models\User;            // Model untuk mengambil data user
use Illuminate\Http\Request;    // Request digunakan untuk menangani permintaan HTTP
use App\Models\Result;          // Model untuk menampilkan jumlah hasil quiz
use App\Models\Thread;          // Model untuk forum atau thread diskusi
use App\Models\Application;     // Model untuk informasi aplikasi (nama app, logo, dll)

class AdminDashboardController extends Controller
{
    /**
     * Menampilkan halaman dashboard admin
     * @param AnswerQuizChart $chart - Dependency Injection untuk membuat grafik quiz
     */
    public function index(AnswerQuizChart $chart)
    {
        // Mengirim data ke view dashboard admin
        return view('admin.dashboard.index', [
            // Mengambil informasi aplikasi
            'app' => Application::all(),

            // Judul halaman
            'title' => 'Dashboard',

            // Menghitung total quiz pada sistem
            'totalQuiz' => Quiz::count(),

            // Menghitung total pengguna laki-laki yang bukan admin
            'totalLakiLaki' => User::where('gender', 'Laki-Laki')->where('is_admin', '!=', 1)->count(),

            // Menghitung total pengguna perempuan yang bukan admin
            'totalPerempuan' => User::where('gender', 'Perempuan')->where('is_admin', '!=', 1)->count(),

            // Mengambil 4 anggota terbaru untuk ditampilkan di dashboard
            'members' => User::where('is_admin', 0)
                            ->latest() // Mengurutkan berdasarkan terbaru
                            ->take(4)  // Membatasi hanya 4 data
                            ->select('name', 'image', 'created_at') // Memilih kolom yang diperlukan
                            ->get(),

            // Total semua member yang bukan admin
            'totalMember' => User::where('is_admin', false)->count(),

            // Total semua jawaban quiz
            'answersQuiz' => Result::all()->count(),

            // Total semua thread forum
            'threads' => Thread::all()->count(),

            // Membuat data chart untuk ditampilkan di dashboard
            'chart' => $chart->build()
        ]);
    }
}
