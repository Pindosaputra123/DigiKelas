<!DOCTYPE html>
<html lang="id" class="light-style customizer-hide" dir="ltr" data-theme="theme-default">

<head>
  <!-- Set encoding karakter halaman -->
  <meta charset="utf-8" />

  <!-- Membuat tampilan responsive di semua device -->
  <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

  <!-- Judul tab browser: nama aplikasi + judul halaman -->
  <title>{{ $app[0]->name_app }} - {{ $title }}</title>

  <!-- Deskripsi aplikasi untuk SEO -->
  <meta name="description" content="{{ $app[0]->description_app }}" />

  <!-- Favicon atau ikon di tab browser -->
  <link rel="icon" type="image/x-icon" href="@if(Storage::disk('public')->exists('logo-aplikasi')) {{ asset('storage/' . $app[0]->logo) }} @else {{ asset('assets/img/logo-aplikasi/logo.png') }} @endif" />

  <!-- 🔤 Google Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet" />
  <link href="https://fonts.googleapis.com/css2?family=Lobster&display=swap" rel="stylesheet">

  <!-- ✅ CSS vendor bawaan template admin -->
  <link rel="stylesheet" href="{{ asset('assets/vendors/assets/vendor/fonts/boxicons.css') }}" />
  <link rel="stylesheet" href="{{ asset('assets/vendors/assets/vendor/css/core.css') }}" />
  <link rel="stylesheet" href="{{ asset('assets/vendors/assets/vendor/css/theme.css') }}" />
  <link rel="stylesheet" href="{{ asset('assets/vendors/assets/css/demo.css') }}" />
  <link rel="stylesheet" href="{{ asset('assets/vendors/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css') }}" />
  <link rel="stylesheet" href="{{ asset('assets/vendors/assets/vendor/css/pages/page-auth.css') }}" />

  <!-- JavaScript helper template -->
  <script src="{{ asset('assets/vendors/assets/vendor/js/helpers.js') }}"></script>

  <!-- SweetAlert untuk notifikasi alert -->
  <link rel="stylesheet" href="{{ asset('assets/vendors/libs/sweetalert2/sweetalert.css') }}">
  <script src="{{ asset('assets/vendors/libs/sweetalert2/sweetalert.js') }}"></script>

  <!-- Custom CSS untuk toast notif -->
  <link rel="stylesheet" href="{{ asset('assets/css/toast.css') }}">

  <!-- Config Global JS (tema, layout, dll) -->
  <script src="{{ asset('assets/vendors/assets/js/config.js') }}"></script>
</head>

<body>
  <!-- Container utama halaman -->
  <div class="container-xxl">
    <div class="authentication-wrapper authentication-basic container-p-y">
      <div class="authentication-inner">
        <!-- Kartu login -->
        <div class="card">
          <div class="card-body">

            <!-- Logo dan nama aplikasi -->
            <div class="app-brand justify-content-center">
              <a href="/" class="app-brand-link gap-2">
                <img src="@if(Storage::disk('public')->exists('logo-aplikasi')) {{ asset('storage/' . $app[0]->logo) }} @else {{ asset('assets/img/logo-aplikasi/logo.png') }} @endif" class="h-auto bx-tada" style="width: 28px;" alt="Logo {{ $app[0]->name_app }}">
                <span class="app-brand-text text-body fw-bolder text-primary" style="font-size: 1.7rem; font-family: 'Lobster', cursive; letter-spacing:1px;">
                  {{ $app[0]->name_app }}
                </span>
              </a>
            </div>

            <!-- Heading login -->
            <h4 class="mb-2">Welcome Back!</h4>
            <p class="mb-3">Silahkan login dan mulai belajar.</p>

            <!-- Pesan flash dari session -->
            <div class="flash-message" data-flash-message="@if(session()->has('loginError')) {{ session('loginError') }} @endif"></div>
            <div class="flash-message-register" data-flash-message="@if(session()->has('registerBerhasil')) {{ session('registerBerhasil') }} @endif"></div>

            <!-- Form Login -->
            <form id="formAuthentication" class="mb-3" action="/login" method="POST">
              @csrf <!-- Token keamanan Laravel untuk POST -->

              <!-- Input Username -->
              <div class="mb-3">
                <label for="username" class="form-label">Username</label>
                <input type="text" class="form-control @error('username') is-invalid @enderror"
                  id="username" name="username" value="{{ old('username') }}"
                  placeholder="Enter your username" autocomplete="off" required />
                @error('username')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>

              <!-- Input Password -->
              <div class="mb-4 form-password-toggle">
                <label class="form-label" for="password">Password</label>
                <div class="input-group input-group-merge">
                  <input type="password" id="password" class="form-control" name="password"
                    placeholder="************" autocomplete="off" required />
                  <span class="input-group-text cursor-pointer">
                    <i class="bx bx-hide"></i>
                  </span>
                </div>
              </div>

              <!-- Tombol Login -->
              <div class="mb-3 divBtn" style="cursor: not-allowed;">
                <button class="btn btn-primary d-grid w-100 tombolLogin disabled" type="submit">
                  Log in
                </button>
              </div>
            </form>

            <!-- Link Register -->
            <p class="text-center">
              <span>Belum punya akun?</span>
              <a href="/register">Sign Up</a>
            </p>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Script JS vendor -->
  <script src="{{ asset('assets/vendors/assets/vendor/libs/jquery/jquery.js') }}"></script>
  <script src="{{ asset('assets/vendors/assets/vendor/libs/popper/popper.js') }}"></script>
  <script src="{{ asset('assets/vendors/assets/vendor/js/bootstrap.js') }}"></script>
  <script src="{{ asset('assets/vendors/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js') }}"></script>
  <script src="{{ asset('assets/vendors/assets/vendor/js/menu.js') }}"></script>
  <script src="{{ asset('assets/vendors/assets/js/main.js') }}"></script>

  <!-- Custom JS -->
  <script src="{{ asset('assets/vendors/js/buttons.js') }}"></script>
  <script src="{{ asset('assets/js/login.js') }}"></script>
</body>

</html>
