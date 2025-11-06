// ---------------------------------------------------------------------------
// Menampilkan Detail Quiz pada Modal
// ---------------------------------------------------------------------------
// Event ketika tombol dengan class .buttonDetails diklik
$(".buttonDetails").on("click", function () {

    // Mengambil judul quiz dari atribut data-title-quiz pada tombol
    const title = $(this).data("title-quiz");

    // Mengambil deskripsi quiz dari atribut data-description-quiz
    const description = $(this).data("description-quiz");

    // Mengambil total jumlah soal dari atribut data-total-soal-quiz
    const totalSoal = $(this).data("total-soal-quiz");

    // Menampilkan judul quiz pada elemen HTML dengan id #titleQuiz
    $("#titleQuiz").html(title);

    // Menampilkan deskripsi quiz pada elemen HTML dengan id #descriptionQuiz
    $("#descriptionQuiz").html(description);

    // Menampilkan jumlah total soal pada elemen HTML dengan id #totalSoalQuiz
    $("#totalSoalQuiz").html(totalSoal);
});
