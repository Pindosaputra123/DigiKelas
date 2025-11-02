// Inisialisasi SweetAlert2 untuk membuat notifikasi (toast) dengan gaya khusus.
// Toast ini digunakan untuk menampilkan pesan singkat (seperti kesalahan login atau keberhasilan registrasi).
const Toast = Swal.mixin({
    iconColor: "white", // Warna ikon notifikasi.
    customClass: {
        popup: "colored-toast", // Menggunakan kelas CSS khusus agar tampilannya konsisten.
    },
    // Event ketika toast muncul — untuk menghentikan dan melanjutkan timer jika pengguna mengarahkan mouse.
    didOpen: (toast) => {
        toast.addEventListener("mouseenter", Swal.stopTimer);
        toast.addEventListener("mouseleave", Swal.resumeTimer);
    },
});

// Mengambil pesan flash dari elemen HTML dengan kelas .flash-message (biasanya diatur oleh backend Laravel)
const flashMessage = $(".flash-message").data("flash-message");
// Mengambil pesan flash khusus untuk registrasi sukses
const flashMessageRegister = $(".flash-message-register").data("flash-message");

/**
 * Fungsi setMessage digunakan untuk menampilkan notifikasi menggunakan SweetAlert2 Toast.
 * @param {string} message - Pesan yang akan ditampilkan.
 * @param {string} status - Jenis ikon notifikasi ('success', 'error', 'info', dll).
 */
function setMessage(message, status) {
    Toast.fire({
        icon: status,
        title: message,
        showConfirmButton: false,
        timer: 4000, // Durasi tampil 4 detik
        timerProgressBar: true,
        toast: true,
        width: "auto",
        position: "top-end", // Posisi di pojok kanan atas layar
    });
}

// Jika terdapat pesan flash error (misal: login gagal), tampilkan notifikasi error
if (flashMessage) {
    setMessage(flashMessage, "error");
}

// Jika terdapat pesan flash sukses (misal: registrasi berhasil), tampilkan notifikasi sukses
if (flashMessageRegister) {
    setMessage(flashMessageRegister, "success");
}

// Event listener untuk input username
$("#username").on("input", function () {
    let username = $(this).val();
    // Hapus spasi, karakter non-alfanumerik, dan ubah huruf menjadi lowercase
    $(this).val(
        username
            .replace(/\s/g, "") // Hapus semua spasi
            .replace(/[^a-zA-Z0-9]/g, "") // Hapus karakter selain huruf dan angka
            .toLowerCase() // Jadikan huruf kecil semua
    );
});

// Event listener untuk input password
$("#password").on("input", function () {
    let password = $(this).val();
    // Hilangkan spasi di awal dan akhir input password
    $(this).val(password.trim());
});

// Event listener untuk menonaktifkan tombol Enter jika field kosong
$("#username, #password").on("keydown", function (event) {
    if ($("#username").val() == "") {
        // Jika username kosong dan tekan Enter, jangan lanjutkan
        if (event.which === 13) {
            event.preventDefault();
        }
    } else {
        // Jika username terisi tapi password kosong, jangan izinkan Enter
        if ($("#password").val() == "") {
            if (event.which === 13) {
                event.preventDefault();
            }
        }
    }
});

// Event listener untuk mengaktifkan tombol login hanya jika kedua input terisi
$("#username, #password").on("keyup", function () {
    if ($("#username").val() !== "") {
        if ($("#password").val() !== "") {
            // Jika keduanya terisi, tombol login aktif
            $(".tombolLogin").removeClass("disabled");
            $(".divBtn").removeAttr("style");
        } else {
            // Jika password kosong, tombol login dinonaktifkan
            $(".tombolLogin").addClass("disabled", true);
            $(".divBtn").attr("style", "cursor: not-allowed;");
        }
    } else {
        // Jika username kosong, tombol login tetap nonaktif
        $(".tombolLogin").addClass("disabled", true);
        $(".divBtn").attr("style", "cursor: not-allowed;");
    }
});
