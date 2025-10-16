<!DOCTYPE html>
<html lang="id" class="light-style layout-menu-fixed" dir="ltr" data-theme="theme-default">
<!--
  Halaman error 403 Forbidden
  Ditampilkan ketika user mencoba mengakses halaman yang tidak memiliki izin (unauthorized)
-->

<head>
  <meta charset="utf-8" />
  <!-- Mengatur viewport agar responsive di perangkat mobile -->
  <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
  <title>403 - Forbidden</title>

  <!-- Favicon icon -->
  <link rel="icon" type="image/x-icon" href="{{ asset('assets/img/favicon-error/icon.svg') }}" />

  <!-- Deskripsi halaman untuk SEO -->
  <meta name="description" content="Error 403 Forbidden" />

  <!-- Import fonts dari Google -->
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet" />
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet" />
  <link href="https://fonts.googleapis.com/css2?family=Lobster&display=swap" rel="stylesheet">

  <!-- Vendor CSS -->
  <link rel="stylesheet" href="{{ asset('assets/vendors/assets/vendor/fonts/boxicons.css') }}" />
  <link rel="stylesheet" href="{{ asset('assets/vendors/assets/vendor/css/core.css') }}" />
  <link rel="stylesheet" href="{{ asset('assets/vendors/assets/vendor/css/theme.css') }}" />
  <link rel="stylesheet" href="{{ asset('assets/vendors/assets/css/demos.css') }}" />
  <link rel="stylesheet" href="{{ asset('assets/vendors/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css') }}" />
  <link rel="stylesheet" href="{{ asset('assets/vendors/assets/vendor/css/pages/page-misc.css') }}">

  <!-- Helper JS -->
  <script src="{{ asset('assets/vendors/assets/vendor/js/helpers.js') }}"></script>
  <script src="{{ asset('assets/vendors/assets/js/config.js') }}"></script>
</head>

<body>
  <!-- Kontainer utama -->
  <div class="container-xxl container-p-y">
    <div class="misc-wrapper text-center">
      <!-- Judul error -->
      <h2 class="mb-2 mx-2">403 | FORBIDDEN</h2>
      <!-- Pesan error -->
      <p class="mb-4 mx-2">Oops! Anda Tidak Memiliki Hak Akses Kesini!</p>
      <!-- Tombol kembali ke beranda -->
      <button onclick="window.location.href = '/'" class="btn btn-dark">
        <i class='bx bxs-home bx-tada fs-6 me-1' style="margin-bottom: 3.4px;"></i> Back to Home
      </button>
      <!-- Gambar ilustrasi error -->
      <div class="mt-3">
        <img src="{{ asset('assets/vendors/assets/img/illustrations/error-403.svg') }}" alt="403 Error Forbidden" width="430" class="img-fluid">
      </div>
    </div>
  </div>

  <!-- Vendor JS -->
  <script src="{{ asset('assets/vendors/assets/vendor/libs/popper/popper.js') }}"></script>
  <script src="{{ asset('assets/vendors/assets/vendor/js/bootstrap.js') }}"></script>
  <script src="{{ asset('assets/vendors/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js') }}"></script>
  <script src="{{ asset('assets/vendors/assets/vendor/js/menu.js') }}"></script>
  <script src="{{ asset('assets/vendors/assets/js/main.js') }}"></script>
  <script src="{{ asset('assets/vendors/js/buttons.js') }}"></script>
</body>

</html>
