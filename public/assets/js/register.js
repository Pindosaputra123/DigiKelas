// Inisialisasi komponen Toast dari SweetAlert2 untuk menampilkan notifikasi kecil (toast message)
const Toast = Swal.mixin({
    iconColor: "white",
    customClass: {
        popup: "colored-toast", // Menentukan class CSS kustom untuk gaya toast
    },
    didOpen: (toast) => {
        // Menghentikan dan melanjutkan timer saat mouse masuk/keluar toast
        toast.addEventListener("mouseenter", Swal.stopTimer);
        toast.addEventListener("mouseleave", Swal.resumeTimer);
    },
});

// Mengambil data flash message dari elemen HTML yang berisi data flash (biasanya dari backend Laravel)
const flashMessage = $(".flash-data").data("flash-message");
const flashAksi = $(".flash-data").data("flashaksi");
const flashTipe = $(".flash-data").data("flashtipe");

// Jika terdapat flash message, tampilkan toast notifikasi
if (flashMessage) {
    Toast.fire({
        icon: flashTipe, // Menentukan jenis icon (success, error, warning, dsb)
        title: flashMessage + " " + flashAksi, // Menampilkan pesan dan aksi
        showConfirmButton: false,
        timer: 3000, // Durasi tampil (3 detik)
        timerProgressBar: true,
        toast: true,
        position: "top-end", // Posisi di pojok kanan atas
    });
}

// Validasi input username
$("#username").on("input", function () {
    let username = $(this).val();
    $(this).val(
        username
            .replace(/\s/g, "") // Menghapus spasi
            .replace(/[^a-zA-Z0-9]/g, "") // Hanya boleh huruf dan angka
            .toLowerCase() // Ubah ke huruf kecil
    );
});

// Validasi input nama lengkap agar hanya berisi huruf dan spasi
$("#namaLengkap").on("input", function () {
    let nama = $(this).val();
    var lettersAndSpace = /^[a-zA-Z\s]*$/; // Hanya huruf dan spasi
    if (!nama.match(lettersAndSpace)) {
        let namaClear = nama.replace(/[^a-zA-Z\s]/g, ""); // Hapus karakter tidak valid
        $(this).val(namaClear);
    }
});

// Membersihkan spasi di awal/akhir password
$("#password").on("input", function () {
    let password = $(this).val();
    $(this).val(password.trim());
});

// Normalisasi input email: huruf kecil, tanpa spasi
$("#email").on("input", function () {
    let email = $(this).val();
    $(this).val(email.toLowerCase().trim().replace(/\s/g, ""));
});

// Logika untuk mengaktifkan tombol daftar hanya jika semua input terisi
$("#username, #password, #email, #namaLengkap, #password2").on(
    "keyup",
    function () {
        if ($("#username").val() !== "") {
            if ($("#password").val() !== "") {
                if ($("#email").val() !== "") {
                    if ($("#namaLengkap").val() !== "") {
                        if ($("#password2").val() !== "") {
                            // Semua field terisi, tombol aktif
                            $(".tombolDaftar").removeClass("disabled");
                            $(".divBtn").removeAttr("style");
                        } else {
                            // Field password2 kosong → tombol tidak aktif
                            $(".tombolDaftar").addClass("disabled", true);
                            $(".divBtn").attr("style", "cursor: not-allowed;");
                        }
                    } else {
                        $(".tombolDaftar").addClass("disabled", true);
                        $(".divBtn").attr("style", "cursor: not-allowed;");
                    }
                } else {
                    $(".tombolDaftar").addClass("disabled", true);
                    $(".divBtn").attr("style", "cursor: not-allowed;");
                }
            } else {
                $(".tombolDaftar").addClass("disabled", true);
                $(".divBtn").attr("style", "cursor: not-allowed;");
            }
        } else {
            $(".tombolDaftar").addClass("disabled", true);
            $(".divBtn").attr("style", "cursor: not-allowed;");
        }
    }
);
