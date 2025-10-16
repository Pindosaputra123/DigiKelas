<!DOCTYPE html>
<html lang="id" class="light-style layout-menu-fixed" dir="ltr" data-theme="theme-default">
<!-- Deklarasi dokumen HTML standar dengan bahasa Indonesia + atribut tema -->

<head>
  <meta charset="utf-8" />
  <!-- Set karaktek UTF-8 agar mendukung semua karakter -->

  <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
  <!-- Responsif agar tampilan bagus di semua perangkat -->

  <title>404 - Page Not Found</title>
  <!-- Judul halaman browser -->

  <meta name="description" content="Error 404 Page Not Found" />
  <!-- Deskripsi halaman untuk SEO -->

  <link rel="icon" type="image/x-icon" href="{{ asset('assets/img/favicon-error/icon.svg') }}" />
  <!-- Favicon icon untuk tab browser -->

  <!-- Import font Google -->
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap" rel="stylesheet" />
  <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap" rel="stylesheet" />
  <link href="https://fonts.googleapis.com/css2?family=Lobster&display=swap" rel="stylesheet">

  <!-- CSS Vendor/assets (tema dashboard) -->
  <link rel="stylesheet" href="{{ asset('assets/vendors/assets/vendor/fonts/boxicons.css') }}" />
  <link rel="stylesheet" href="{{ asset('assets/vendors/assets/vendor/css/core.css') }}" />
  <link rel="stylesheet" href="{{ asset('assets/vendors/assets/vendor/css/theme.css') }}" />
  <link rel="stylesheet" href="{{ asset('assets/vendors/assets/css/demos.css') }}" />
  <link rel="stylesheet" href="{{ asset('assets/vendors/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css') }}" />
  <link rel="stylesheet" href="{{ asset('assets/vendors/assets/vendor/css/pages/page-misc.css') }}">
  <!-- CSS tambahan untuk halaman misc (error) -->

  <script src="{{ asset('assets/vendors/assets/vendor/js/helpers.js') }}"></script>
  <script src="{{ asset('assets/vendors/assets/js/config.js') }}"></script>
  <!-- JavaScript helper & konfigurasi tema -->
</head>

<div class="container-xxl container-p-y">
  <!-- Wrapper konten error -->

  <div class="misc-wrapper">
    <!-- Container konten error -->

    <h2 class="mb-2 mx-2">404 | NOT FOUND</h2>
    <!-- Pesan judul error -->

    <p class="mb-4 mx-2">Oops! Halaman Tidak Ditemukan</p>
    <!-- Pesan penjelasan -->

    <button onclick="window.location.href = '/'" class="btn btn-dark">
      <i class='bx bxs-home bx-tada fs-6 me-1' style="margin-bottom: 3.4px;"></i> Back to Home
    </button>
    <!-- Tombol kembali ke beranda -->

    <div class="mt-3">
      <img src="{{ asset('assets/vendors/assets/img/illustrations/page-misc-error-light.png') }}" alt="404 Error Not Found" width="500" class="img-fluid">
      <!-- Gambar ilustrasi error -->
    </div>
  </div>
</div>

<!-- Javascript vendor -->
<script src="{{ asset('assets/vendors/assets/vendor/libs/popper/popper.js') }}"></script>
<script src="{{ asset('assets/vendors/assets/vendor/js/bootstrap.js') }}"></script>
<script src="{{ asset('assets/vendors/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js') }}"></script>
<script src="{{ asset('assets/vendors/assets/vendor/js/menu.js') }}"></script>
<script src="{{ asset('assets/vendors/assets/js/main.js') }}"></script>
<script src="{{ asset('assets/vendors/js/buttons.js') }}"></script>
<!-- Script UI tambahan -->
</body>
</html>
