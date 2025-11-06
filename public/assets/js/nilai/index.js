// ---------------------------------------------------------------------------
// Filter Nilai Berdasarkan Score (Dropdown)
// ---------------------------------------------------------------------------
// Event ketika dropdown #score-filter berubah
$("#score-filter").on("change", function () {

    // Ambil nilai option yang dipilih user
    var selectedOption = this.value;

    // Jika user memilih 'teratas', arahkan ke URL dengan parameter filter=teratas
    if (selectedOption === "teratas") {
        window.location.href = "/nilai?filter=teratas";

    // Jika user memilih 'terendah', arahkan ke URL dengan parameter filter=terendah
    } else if (selectedOption === "terendah") {
        window.location.href = "/nilai?filter=terendah";
    }
});



// ---------------------------------------------------------------------------
// Konfirmasi Hapus History Nilai
// ---------------------------------------------------------------------------
// Ketika tombol hapus histori diklik
$(".buttonDeleteHistory").on("click", function () {

    // Mengambil ID atau action yang akan digunakan untuk URL penghapusan
    const dataAction = $(this).data("action");

    // Mengambil data tanggal dan jam dari atribut data-delete-tanggal
    const data = $(this).data("delete-tanggal");

    // Data dikirim dalam bentuk "tanggal|jam", maka kita split
    const tanggalJam = data.split("|");

    // Menampilkan pesan konfirmasi dalam modal
    $(".tanggalHistory").html(
        "Anda yakin ingin menghapus histori quiz tanggal <strong>" +
            tanggalJam[0] +
            " jam " +
            tanggalJam[1] +
            "</strong> ?"
    );

    // Menentukan URL delete sesuai action yang dikirim dari tombol
    $("#formDeleteNilai").attr("action", "/nilai/delete/" + dataAction);
});
