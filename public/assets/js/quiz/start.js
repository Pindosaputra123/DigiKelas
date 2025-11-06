// ---------------------------------------------------------------------------
// Menampilkan Modal Konfirmasi Submit Quiz
// ---------------------------------------------------------------------------

// Ketika tombol submit quiz diklik
$(".buttonSumbitQuiz").on("click", function (e) {
    e.preventDefault(); 
    // Mencegah form langsung terkirim, supaya user melihat konfirmasi dulu

    $("#submitQuiz").modal("show"); 
    // Tampilkan modal konfirmasi submit quiz
});


// ---------------------------------------------------------------------------
// Aksi Konfirmasi Submit Quiz
// ---------------------------------------------------------------------------

$(".confirmQuiz").on("click", function () {
    localStorage.clear(); 
    // Hapus semua jawaban yang tersimpan sementara di LocalStorage

    $("#quizForm").submit(); 
    // Submit form quiz setelah user benar-benar mengonfirmasi
});



// ---------------------------------------------------------------------------
// Menyimpan Jawaban Sementara (Autosave) Menggunakan LocalStorage
// ---------------------------------------------------------------------------

// Seleksi semua input radio (jawaban pilihan ganda)
document
    .querySelectorAll('input[type="radio"]')
    .forEach(function (radioButton) {

        // Setiap kali user mengganti jawaban
        radioButton.addEventListener("change", function () {

            // Menggunakan atribut "name" sebagai kunci penyimpanan
            const groupName = radioButton.name;

            // Simpan id jawaban radio ke LocalStorage agar bisa dipulihkan
            localStorage.setItem("quiz_" + groupName, radioButton.id);
        });
    });



// ---------------------------------------------------------------------------
// Mengembalikan Jawaban User yang Pernah Dipilih (Restore Autosave)
// ---------------------------------------------------------------------------

document
    .querySelectorAll('input[type="radio"]')
    .forEach(function (radioButton) {

        const groupName = radioButton.name;

        // Ambil jawaban yang pernah disimpan berdasarkan groupName
        const selectedId = localStorage.getItem("quiz_" + groupName);

        // Jika ada jawaban yang tersimpan, tandai radio tersebut sebagai checked
        if (selectedId) {
            radioButton.checked = radioButton.id === selectedId;
        }
    });



// ---------------------------------------------------------------------------
// Tombol Batalkan Quiz → Menghapus Jawaban yang Tersimpan
// ---------------------------------------------------------------------------

$(".btlQuiz").on("click", function () {
    localStorage.clear(); 
    // Jika user membatalkan quiz, hapus semua jawaban sementara
});
