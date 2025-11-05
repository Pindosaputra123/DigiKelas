// Event listener untuk setiap elemen dengan class .box ketika diklik
$(".box").on("click", function () {
    // Mengambil nilai atribut data-source-audio dari elemen yang diklik
    // Nilai ini digunakan sebagai id elemen <audio> yang ingin diputar
    let text = $(this).data("source-audio");

    // Mendapatkan elemen <audio> berdasarkan id yang diambil di atas
    let audio = document.getElementById(text);

    // Mengecek apakah elemen audio ditemukan
    if (audio) {
        // Jika audio dalam keadaan pause (belum diputar)
        if (audio.paused) {
            // Hentikan semua audio lain yang mungkin sedang diputar
            stopAllAudio();

            // Putar audio yang sesuai dengan box yang diklik
            audio.play();

            // Hapus semua highlight (warna biru dan teks putih) dari semua box
            $(".box").removeClass("bg-primary text-white");

            // Kembalikan warna teks <h2> pada semua box ke kondisi normal (tanpa filter invert)
            $(".box").find("h2").css({
                filter: "invert(0%)",
            });

            // Beri efek highlight pada box yang sedang aktif (yang diklik)
            // Mengubah warna teks jadi putih dan background jadi biru
            $(this).find("h2").css({
                filter: "invert(100%)",
            });
            $(this).addClass("bg-primary text-white");

        } else {
            // Jika audio sedang diputar dan box diklik lagi, hentikan audio
            audio.pause();

            // Kembalikan waktu audio ke awal
            audio.currentTime = 0;

            // Hilangkan highlight pada box tersebut
            $(this).removeClass("bg-primary text-white");

            // Kembalikan warna teks <h2> ke normal
            $(this).find("h2").css({
                filter: "invert(0%)",
            });
        }

        // Event listener ketika audio selesai diputar
        audio.addEventListener("ended", function () {
            // Ketika audio berakhir, semua box direset ke kondisi awal (tanpa highlight)
            $(".box").removeClass("bg-primary text-white");
            $(".box").find("h2").css({
                filter: "invert(0%)",
            });
        });
    }
});

// Fungsi untuk menghentikan semua audio yang sedang diputar
function stopAllAudio() {
    // Ambil semua elemen <audio> di halaman
    var allAudio = document.querySelectorAll("audio");

    // Loop setiap audio dan hentikan pemutarannya
    allAudio.forEach(function (a) {
        a.pause();
    });
}
