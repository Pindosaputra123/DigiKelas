// ---------------------------------------------------------------------------
// Konfigurasi Toast (SweetAlert2) untuk Notifikasi Global
// ---------------------------------------------------------------------------

const Toast = Swal.mixin({
    iconColor: "white",
    customClass: {
        popup: "colored-toast", // Menggunakan class custom untuk styling toast
    },
    didOpen: (toast) => {
        // Saat toast dibuka, hentikan timer ketika mouse berada di atasnya
        toast.addEventListener("mouseenter", Swal.stopTimer);

        // Lanjutkan timer ketika mouse keluar dari toast
        toast.addEventListener("mouseleave", Swal.resumeTimer);
    },
});



// ---------------------------------------------------------------------------
// Upload Foto Profil (Preview Image Sebelum Upload)
// ---------------------------------------------------------------------------

// Event ketika user mengganti file input foto profil
$("#upload").on("change", function () {

    var file = $(this)[0].files[0]; // Mengambil file yang dipilih user

    // Validasi: Pastikan file adalah image
    if (file && file.type.startsWith("image")) {
        var reader = new FileReader(); // Inisialisasi pembaca file

        // Event ketika file berhasil dibaca
        reader.onload = function (e) {
            // Menampilkan preview foto ke element img
            $("#uploadedPhotoProfil")[0].src = e.target.result;
            $("#uploadedPhotoProfil").css("display", "block");
        };

        // Membaca file sebagai data base64 agar bisa ditampilkan sebagai gambar
        reader.readAsDataURL(file);

    } else {
        // Jika file bukan gambar → tampilkan peringatan Toast
        Toast.fire({
            icon: "warning",
            title: "Yang diupload harus image!",
            showConfirmButton: false,
            timer: 4000,
            timerProgressBar: true,
            toast: true,
            position: "top-end",
        });
    }
});



// ---------------------------------------------------------------------------
// Menampilkan Modal Edit Email User
// ---------------------------------------------------------------------------

// Ketika tombol edit email ditekan
$(".buttonEditEmailUser").on("click", function () {
    $("#formModalUsersEditEmail").modal("show"); // Tampilkan modal edit email
});



// ---------------------------------------------------------------------------
// Reset Input Password Verify Saat Dibatalkan
// ---------------------------------------------------------------------------

// Ketika tombol batal verifikasi ditekan
$(".btnCancelVeify").on("click", function () {

    // Hilangkan status invalid (border merah + error feedback)
    $("#passwordVerify").removeClass("is-invalid");
    $("#passwordVerify").removeClass("invalid-feedback");

    // Kosongkan input password
    $("#passwordVerify").val("");
});



// ---------------------------------------------------------------------------
// Menampilkan Foto Profil dalam Modal Besar (Zoom View)
// ---------------------------------------------------------------------------

// Ketika foto profil diklik
$(".fotoProfile").on("click", function () {

    // Ambil URL foto dari data attribute
    const urlImg = $(this).data("url-img");

    // Tampilkan foto pada img di modal
    $(".urlShowProfilImg").attr("src", urlImg);

    // Buka modal foto
    $("#gambarModal").modal("show");
});
