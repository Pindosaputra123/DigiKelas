// ---------------------------------------------------------------------------
// Konfigurasi Toast (SweetAlert2) untuk Notifikasi
// ---------------------------------------------------------------------------

const Toast = Swal.mixin({
    iconColor: "white",
    customClass: {
        popup: "colored-toast", // custom class styling
    },
    didOpen: (toast) => {
        // Hentikan timer ketika mouse berada di atas notifikasi
        toast.addEventListener("mouseenter", Swal.stopTimer);

        // Lanjutkan timer ketika mouse keluar
        toast.addEventListener("mouseleave", Swal.resumeTimer);
    },
});



// ---------------------------------------------------------------------------
// Upload Foto Profil - Preview Image Sebelum Upload
// ---------------------------------------------------------------------------

$("#upload").on("change", function () {

    var file = $(this)[0].files[0]; // Ambil file dari input

    // Validasi: Harus file image
    if (file && file.type.startsWith("image")) {
        var reader = new FileReader();

        // Ketika file selesai dibaca
        reader.onload = function (e) {
            $("#uploadedPhotoProfil")[0].src = e.target.result; // Set image preview
            $("#uploadedPhotoProfil").css("display", "block"); // Tampilkan gambar
        };

        reader.readAsDataURL(file); // Membaca file menjadi base64

    } else {
        // Jika bukan image → tampilkan notifikasi
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

$(".buttonEditEmailUser").on("click", function () {
    $("#formModalUsersEditEmail").modal("show");
});



// ---------------------------------------------------------------------------
// Reset Input Password Verify Saat Cancel
// ---------------------------------------------------------------------------

$(".btnCancelVeify").on("click", function () {

    // Hilangkan tanda invalid
    $("#passwordVerify").removeClass("is-invalid");
    $("#passwordVerify").removeClass("invalid-feedback");

    // Kosongkan input password
    $("#passwordVerify").val("");
});



// ---------------------------------------------------------------------------
// Upload Logo - Preview Image
// (Mirip dengan upload foto profil namun untuk logo)
// ---------------------------------------------------------------------------

$("#uploadLogo").on("change", function () {

    var file = $(this)[0].files[0];

    if (file && file.type.startsWith("image")) {
        var reader = new FileReader();

        reader.onload = function (e) {
            $("#uploadedLogo")[0].src = e.target.result;  // Set preview
            $("#uploadedLogo").css("display", "block");
        };

        reader.readAsDataURL(file);

    } else {
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
// Menampilkan Foto Profil dalam Modal
// ---------------------------------------------------------------------------

$(".fotoProfile").on("click", function () {

    // Ambil URL gambar dari atribut data
    const urlImg = $(this).data("url-img");

    // Set gambar ke modal
    $(".urlShowProfilImg").attr("src", urlImg);

    // Tampilkan modal gambar
    $("#gambarModal").modal("show");
});



// ---------------------------------------------------------------------------
// Menampilkan Logo dalam Modal
// ---------------------------------------------------------------------------

$(".logoImage").on("click", function () {

    const urlImg = $(this).data("url-img");

    // Set gambar ke modal
    $(".urlShowLogo").attr("src", urlImg);

    // Tampilkan modal logo
    $("#logoModal").modal("show");
});
