// =====================================================================
// RESET FORM TAMBAH & EDIT PENGGUNA
// =====================================================================
$(".cancelModalAddUser, .cancelModalEditUser").on("click", function () {
    // Reset seluruh form pada modal tambah dan edit pengguna
    $(".modalAdminAddPengguna, .modalAdminEditPengguna")[0].reset();

    // Hapus class is-invalid dari semua input di modal tambah pengguna
    $(
        "#formModalAdminAddPengguna #nama_lengkap_user, #formModalAdminAddPengguna #username_user, #formModalAdminAddPengguna #profil_user, #formModalAdminAddPengguna #email_user, #formModalAdminAddPengguna #gender_user, #formModalAdminAddPengguna #password_user"
    ).removeClass("is-invalid");

    // Hapus class is-invalid dari semua input di modal edit pengguna
    $(
        "#formModalAdminEditPengguna #edit_nama_lengkap_user, #formModalAdminEditPengguna #edit_username_user, #formModalAdminEditPengguna #edit_email_user, #formModalAdminEditPengguna #edit_profil_user, #formModalAdminEditPengguna #edit_gender_user, #formModalAdminEditPengguna #edit_password_user"
    ).removeClass("is-invalid");

    // Tampilkan kembali teks bantuan (form-text) yang sebelumnya disembunyikan karena error
    $(".form-text").removeClass("d-none");

    // Hapus nilai dari semua input di modal tambah pengguna
    $(
        "#formModalAdminAddPengguna #nama_lengkap_user, #formModalAdminAddPengguna #username_user, #formModalAdminAddPengguna #email_user, #formModalAdminAddPengguna #gender_user, #formModalAdminAddPengguna #password_user"
    ).val("");
});


// =====================================================================
// EDIT DATA PENGGUNA
// =====================================================================
$(".buttonEditPengguna").on("click", function () {
    // Ambil ID pengguna dari atribut data-id tombol edit
    const id = $(this).data("id");

    // Kirim permintaan AJAX ke server untuk mengambil data pengguna berdasarkan ID
    $.ajax({
        method: "post",
        url: "/admin/pengguna/getuser",
        data: { id: id },
        headers: {
            // Kirim token CSRF agar request POST aman
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
        success: function (data) {
            // Isi field edit form dengan data pengguna yang diterima dari server
            $("#codeUser").val(id);
            $("#edit_nama_lengkap_user").val(data[0].name);
            $("#edit_username_user").val(data[0].username);
            $("#edit_email_user").val(data[0].email);

            // Atur nilai gender berdasarkan data pengguna
            data[0].gender == "Laki-Laki"
                ? $("#gender_laki-laki").attr("selected", true)
                : $("#gender_perempuan").attr("selected", true);

            // Tampilkan modal edit pengguna
            $("#formModalAdminEditPengguna").modal("show");
        },
    });
});


// =====================================================================
// HAPUS DATA PENGGUNA
// =====================================================================
$(".buttonDeletePengguna").on("click", function () {
    // Ambil nama dan ID pengguna dari atribut data tombol delete
    const data = $(this).data("name");
    const action = $(this).data("id");

    // Tampilkan pesan konfirmasi penghapusan
    $(".userMessagesDelete").html(
        "Anda yakin ingin menghapus pengguna bernama <strong>'" +
            data +
            "'</strong> ? Semua data yang terkait dengan pengguna tersebut akan ikut terhapus!"
    );

    // Set action form delete agar sesuai dengan ID pengguna yang dipilih
    $("#formDeleteUser").attr("action", "/admin/pengguna/delete/" + action);

    // Tampilkan modal konfirmasi hapus
    $("#deleteUserConfirm").modal("show");
});


// =====================================================================
// LIHAT PROFIL PENGGUNA (POPUP GAMBAR PROFIL)
// =====================================================================
$(".fotoProfile").on("click", function () {
    // Ambil URL gambar profil dan nama pengguna dari atribut data
    const urlImg = $(this).data("url-img");
    const name = $(this).data("name-user");

    // Tampilkan nama dan gambar ke dalam modal profil
    $(".nameShowProfilImg").html(name);
    $(".urlShowProfilImg").attr("src", urlImg);

    // Tampilkan modal gambar profil
    $("#gambarModal").modal("show");
});


// =====================================================================
// VALIDASI OTOMATIS INPUT FORM TAMBAH/EDIT PENGGUNA
// =====================================================================

// Validasi input username agar:
// - Tidak boleh ada spasi
// - Hanya huruf dan angka
// - Semua huruf otomatis jadi huruf kecil
$("#username_user, #edit_username_user").on("input", function () {
    let username = $(this).val();
    $(this).val(
        username
            .replace(/\s/g, "") // hapus spasi
            .replace(/[^a-zA-Z0-9]/g, "") // hapus karakter selain huruf dan angka
            .toLowerCase() // ubah ke huruf kecil
    );
});


// Validasi input nama lengkap agar hanya berisi huruf dan spasi
$("#nama_lengkap_user, #edit_nama_lengkap_user").on("input", function () {
    let nama = $(this).val();
    var lettersAndSpace = /^[a-zA-Z\s]*$/; // RegEx: hanya huruf dan spasi

    // Jika nama berisi karakter tidak valid, hapus karakter tersebut
    if (!nama.match(lettersAndSpace)) {
        let namaClear = nama.replace(/[^a-zA-Z\s]/g, "");
        $(this).val(namaClear);
    }
});


// Validasi input password agar:
// - Tidak ada spasi di awal/akhir
$("#password_user, #edit_password_user").on("input", function () {
    let password = $(this).val();
    $(this).val(password.trim());
});


// Validasi input email agar:
// - Semua huruf kecil
// - Tidak ada spasi
// - Hilangkan spasi di awal/akhir
$("#email_user, #edit_email_user").on("input", function () {
    let email = $(this).val();
    $(this).val(email.toLowerCase().trim().replace(/\s/g, ""));
});
