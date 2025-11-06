// ---------------------------------------------------------------------------
// Reset Form Add & Edit Quiz Ketika Modal Ditutup
// ---------------------------------------------------------------------------

// Event ketika tombol cancel pada modal tambah atau edit quiz ditekan
$(".cancelModalAddQuiz, .cancelModalEditQuiz").on("click", function () {

    // Reset seluruh form quiz (mengembalikan input ke default)
    $(".modalAdminQuiz")[0].reset();

    // Menghapus class error 'is-invalid' pada field input di modal tambah & edit
    $(
        "#formModalAdminQuiz #deskripsi, #formModalAdminQuiz #title, #formEditModalAdminQuiz #deskripsiEdit, #formEditModalAdminQuiz #titleEdit"
    ).removeClass("is-invalid");

    // Menghapus pesan error 'invalid-feedback'
    $(
        "#formModalAdminQuiz #deskripsi, #formModalAdminQuiz #title, #formEditModalAdminQuiz #deskripsiEdit, #formEditModalAdminQuiz #titleEdit"
    ).removeClass("invalid-feedback");

    // Mengosongkan field input pada form tambah quiz
    $("#formModalAdminQuiz #deskripsi, #formModalAdminQuiz #title").val("");
});



// ---------------------------------------------------------------------------
// Fitur Edit Quiz — Menampilkan Data Quiz ke Dalam Modal
// ---------------------------------------------------------------------------

$(".buttonEditQuiz").on("click", function () {

    // Ambil setiap data quiz dari atribut data pada tombol edit
    const title = $(this).data("title-quiz");
    const desc = $(this).data("desc-quiz");
    const codeQuiz = $(this).data("code-quiz");
    const statusQuiz = $(this).data("status-quiz");

    // Set status quiz (Aktif / Nonaktif) pada dropdown select
    statusQuiz == "Aktif"
        ? $("#aktif").attr("selected", true)
        : $("#nonaktif").attr("selected", true);

    // Masukkan data quiz ke dalam field input modal edit
    $("#titleEdit").val(title);
    $("#deskripsiEdit").val(desc);
    $(".codeQuiz").val(codeQuiz);

    // Tampilkan modal edit quiz
    $("#formEditModalAdminQuiz").modal("show");
});



// ---------------------------------------------------------------------------
// Fitur Hapus Quiz — Menampilkan Konfirmasi Delete
// ---------------------------------------------------------------------------

$(".buttonDeleteQuiz").on("click", function () {

    // Data judul quiz yang akan ditampilkan pada pesan konfirmasi
    const data = $(this).data("title-quiz");

    // Kode quiz yang akan digunakan untuk penghapusan
    const action = $(this).data("code-quiz");

    // Menampilkan pesan konfirmasi di dalam modal
    $(".quizMessagesDelete").html(
        "Anda yakin ingin menghapus quiz dengan judul <strong>'" +
            data +
            "'</strong> ? Semua data yang terkait akan ikut terhapus termasuk pertanyaan dan jawaban!"
    );

    // Mengubah URL form agar mengarah ke endpoint delete sesuai kode quiz
    $("#formDeleteQuiz").attr("action", "/admin/data-quiz/delete/" + action);

    // Tampilkan modal konfirmasi delete
    $("#deleteQuizConfirm").modal("show");
});
