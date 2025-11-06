// ---------------------------------------------------------------------------
// Fungsi Cancel (Reset) Modal Tambah Pertanyaan
// ---------------------------------------------------------------------------
// Ketika tombol batal pada modal tambah soal diklik:
$(".cancelModalAddQuestion").on("click", function () {

    // Reset seluruh input dalam form tambah pertanyaan
    $(".modalAdminAddQuestions")[0].reset();

    // Menghapus class is-invalid (border merah)
    $(
        "#formModalAdminAddQuestions #question, #formModalAdminAddQuestions #option1, #formModalAdminAddQuestions #option2, #formModalAdminAddQuestions #option3, #formModalAdminAddQuestions #option4, #formModalAdminAddQuestions #score, #formModalAdminAddQuestions #correct"
    ).removeClass("is-invalid");

    // Menghapus class invalid-feedback (pesan error)
    $(
        "#formModalAdminAddQuestions #question, #formModalAdminAddQuestions #option1, #formModalAdminAddQuestions #option2, #formModalAdminAddQuestions #option3, #formModalAdminAddQuestions #option4, #formModalAdminAddQuestions #score, #formModalAdminAddQuestions #correct"
    ).removeClass("invalid-feedback");

    // Mengosongkan semua field input
    $(
        "#formModalAdminAddQuestions #question, #formModalAdminAddQuestions #option1, #formModalAdminAddQuestions #option2, #formModalAdminAddQuestions #option3, #formModalAdminAddQuestions #option4, #formModalAdminAddQuestions #score, #formModalAdminAddQuestions #correct"
    ).val("");
});



// ---------------------------------------------------------------------------
// Fungsi Cancel (Reset) Modal Edit Pertanyaan
// ---------------------------------------------------------------------------
$(".cancelModalEditQuestion").on("click", function () {

    // Reset seluruh input di form edit pertanyaan
    $(".modalAdminEditQuestion")[0].reset();

    // Hilangkan class error
    $(
        "#formModalAdminEditQuestion #editquestion, #formModalAdminEditQuestion #editOption1, #formModalAdminEditQuestion #editOption2, #formModalAdminEditQuestion #editOption3, #formModalAdminEditQuestion #editOption4, #formModalAdminEditQuestion #editScore, #formModalAdminEditQuestion #editCorrect"
    ).removeClass("is-invalid");

    // Hilangkan pesan feedback error
    $(
        "#formModalAdminEditQuestion #editquestion, #formModalAdminEditQuestion #editOption1, #formModalAdminEditQuestion #editOption2, #formModalAdminEditQuestion #editOption3, #formModalAdminEditQuestion #editOption4, #formModalAdminEditQuestion #editScore, #formModalAdminEditQuestion #editCorrect"
    ).removeClass("invalid-feedback");
});



// ---------------------------------------------------------------------------
// Fungsi Delete Question
// ---------------------------------------------------------------------------

$(".buttonDeleteQuestion").on("click", function () {

    // Ambil data pertanyaan dari atribut data pada tombol delete
    const data = $(this).data("delete-question");

    // Ambil id/action dari data untuk URL delete
    const action = $(this).data("action");

    // Tampilkan pesan konfirmasi di modal
    $(".questionMessagesDelete").html(
        "Anda yakin ingin menghapus soal <strong>'" + data + "'</strong> ?"
    );

    // Set URL action form delete sesuai id/action
    $("#formDeleteQuestion").attr(
        "action",
        "/admin/data-quiz/q&a/delete/" + action
    );

    // Tampilkan modal konfirmasi
    $("#deleteQuestionConfirm").modal("show");
});



// ---------------------------------------------------------------------------
// AJAX Edit Question — Ambil data jawaban & tampilkan pada form edit
// ---------------------------------------------------------------------------

$(".buttonEditQuestion").on("click", function () {

    // Ambil data dari atribut tombol edit
    const questionID = $(this).data("code-question");
    const question = $(this).data("edit-question");
    const score = $(this).data("edit-score");

    // Request AJAX untuk mendapatkan list jawaban soal
    $.ajax({
        method: "post",
        url: "/admin/data-quiz/getanswer",
        data: {
            id: questionID,
        },
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },

        success: function (data) {

            // Set pertanyaan, id, dan score ke form edit
            $("#editquestion").val(question);
            $("#codeQuestion").val(questionID);
            $("#editScore").val(score);

            // Loop untuk mengisi text jawaban pada 4 option
            for (let i = 0; i < 4; i++) {
                $(`#editOption${i + 1}`).val(data[i].answer);
            }

            // Menentukan option mana yang benar (correct answer)
            $.each(data, function (index, item) {
                if (item.correct == 1) {
                    // Pilih option jawaban yang benar
                    $(".dipilih" + (index + 1)).attr("selected", true);
                }
            });

            // Tampilkan modal edit soal
            $("#formModalAdminEditQuestion").modal("show");
        },
    });
});
