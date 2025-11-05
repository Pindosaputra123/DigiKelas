// Event listener untuk tombol batal (cancel) pada modal tambah dan edit materi
// Tujuannya adalah untuk mereset form, menghapus kelas error (validasi), 
// dan mengosongkan field input ketika modal dibatalkan atau ditutup.
$(".cancelModalAddMateri, .cancelModalEditMateri").on("click", function () {
    // Reset form utama modal admin materi ke kondisi awal
    $(".modalAdminMateri")[0].reset();

    // Hapus kelas "is-invalid" dari semua input yang digunakan pada form tambah dan edit
    $(
        "#formModalAdminMateri #title, #formModalAdminMateri #image, #formModalAdminMateri #audio, #formModalAdminMateri #category, #formEditModalAdminMateri #titleEdit, #formEditModalAdminMateri #imageEdit, #formEditModalAdminMateri #audioEdit, #formEditModalAdminMateri #categoryEdit"
    ).removeClass("is-invalid");

    // Hapus juga kelas "invalid-feedback" agar pesan error tidak muncul lagi
    $(
        "#formModalAdminMateri #title, #formModalAdminMateri #image, #formModalAdminMateri #audio, #formModalAdminMateri #category, #formEditModalAdminMateri #titleEdit, #formEditModalAdminMateri #imageEdit, #formEditModalAdminMateri #audioEdit, #formEditModalAdminMateri #categoryEdit"
    ).removeClass("invalid-feedback");

    // Kosongkan nilai input judul dan kategori pada form tambah materi
    $("#formModalAdminMateri #title, #formModalAdminMateri #category").val("");
});


// Event listener untuk tombol edit materi
// Ketika tombol edit diklik, data dari atribut HTML (data-*) akan diambil 
// dan dimasukkan ke dalam form edit materi di dalam modal.
$(".buttonEditMateri").on("click", function () {
    // Ambil data dari atribut data-* pada tombol edit
    const code = $(this).data("code-materi");
    const title = $(this).data("title-materi");
    const category = $(this).data("category-materi");

    // Tentukan kategori yang dipilih berdasarkan nilai category
    if (category == "huruf") {
        $("#huruf").attr("selected", true);
    } else if (category == "pasangan") {
        $("#pasangan").attr("selected", true);
    } else {
        $("#sandhangan").attr("selected", true);
    }

    // Isi nilai ke dalam form edit materi
    $(".codeMateri").val(code);
    $("#titleEdit").val(title);

    // Tampilkan modal edit materi
    $("#formEditModalAdminMateri").modal("show");
});


// Event listener untuk tombol hapus materi
// Menampilkan modal konfirmasi penghapusan dan menampilkan nama materi yang akan dihapus.
$(".buttonDeleteMateri").on("click", function () {
    // Ambil data nama dan kode materi dari atribut data-* tombol hapus
    const data = $(this).data("title-materi");
    const code = $(this).data("code-materi");

    // Tampilkan pesan konfirmasi penghapusan dengan nama materi yang bersangkutan
    $(".materiMessagesDelete").html(
        "Anda yakin ingin menghapus materi dengan nama <strong>'" +
            data +
            "'</strong> ?"
    );

    // Simpan kode materi yang akan dihapus ke input tersembunyi (hidden)
    $(".codeMateri").val(code);

    // Tampilkan modal konfirmasi hapus
    $("#deleteMateriConfirm").modal("show");
});
