<!DOCTYPE html>
<html lang="id" class="light-style customizer-hide" dir="ltr" data-theme="theme-default">

<head>
  <meta charset="utf-8" />
  <!-- Pengaturan viewport agar responsif di mobile -->
  <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

  <!-- Title website mengambil nama aplikasi dari database -->
  <title>{{ $app[0]->name_app }} - {{ $title }}</title>

  <!-- Deskripsi aplikasi diambil dari database -->
  <meta name="description" content="{{ $app[0]->description_app }}" />

  <!-- Favicon (icon tab browser) - cek dulu apakah ada logo di storage -->
  <link rel="icon" type="image/x-icon"
    href="@if(Storage::disk('public')->exists('logo-aplikasi')) {{ asset('storage/' . $app[0]->logo) }} @else {{ asset('assets/img/logo-aplikasi/logo.png') }} @endif" />

  <!-- Import font dari Google -->
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet" />
  <link href="https://fonts.googleapis.com/css2?family=Lobster&display=swap" rel="stylesheet">

  <!-- Vendor CSS -->
  <link rel="stylesheet" href="{{ asset('assets/vendors/assets/vendor/fonts/boxicons.css') }}" />
  <link rel="stylesheet" href="{{ asset('assets/vendors/assets/vendor/css/core.css') }}" />
  <link rel="stylesheet" href="{{ asset('assets/vendors/assets/vendor/css/theme.css') }}" />
  <link rel="stylesheet" href="{{ asset('assets/vendors/assets/css/demo.css') }}" />
  <link rel="stylesheet" href="{{ asset('assets/vendors/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css') }}" />
  <link rel="stylesheet" href="{{ asset('assets/vendors/assets/vendor/css/pages/page-auth.css') }}" />

  <!-- Helper config JS -->
  <script src="{{ asset('assets/vendors/assets/vendor/js/helpers.js') }}"></script>

  <!-- SweetAlert (popup) -->
  <link rel="stylesheet" href="{{ asset('assets/vendors/libs/sweetalert2/sweetalert.css') }}">
  <script src="{{ asset('assets/vendors/libs/sweetalert2/sweetalert.js') }}"></script>

  <!-- Custom Toast -->
  <link rel="stylesheet" href="{{ asset('assets/css/toast.css') }}">

  <!-- Konfigurasi tema -->
  <script src="{{ asset('assets/vendors/assets/js/config.js') }}"></script>
</head>

<body>
  <!-- Custom CSS langsung di file -->
  <style>
    /* Menandai label wajib dengan tanda bintang merah */
    .required-label::after {
      content: " *";
      color: red;
    }

    /* Style scrollbar */
    ::-webkit-scrollbar {
      width: 5px;
    }
    ::-webkit-scrollbar-thumb {
      background: #696cff !important;
      border-radius: 6px;
    }
  </style>

  <!-- Wrapper halaman autentikasi -->
  <div class="container-xxl">
    <div class="authentication-wrapper authentication-basic container-p-y">
      <div class="authentication-inner">

        <!-- Card register -->
        <div class="card">
          <div class="card-body">

            <!-- Logo aplikasi -->
            <div class="app-brand justify-content-center">
              <a href="/" class="app-brand-link gap-2">
                <img src="@if(Storage::disk('public')->exists('logo-aplikasi')) {{ asset('storage/' . $app[0]->logo) }} @else {{ asset('assets/img/logo-aplikasi/logo.png') }} @endif"
                  class="h-auto bx-tada" style="width: 28px;" alt="Logo-{{ $app[0]->name_app }}">
                <span class="app-brand-text text-body fw-bolder text-primary"
                  style="font-size: 1.7rem; font-family: 'Lobster', cursive;">
                  {{ $app[0]->name_app }}
                </span>
              </a>
            </div>

            <!-- Judul halaman -->
            <h4 class="mb-2">Welcome to {{ $app[0]->name_app }}</h4>
            <p class="mb-3">Silahkan daftar dan mulai belajar.</p>

            <!-- Form Register -->
            <form action="/register" method="POST">
              @csrf

              <!-- Nama lengkap -->
              <div class="mb-2">
                <label class="form-label required-label">Nama Lengkap</label>
                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                  value="{{ old('name') }}" placeholder="Enter your name" required />
                @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
              </div>

              <!-- Username -->
              <div class="mb-2">
                <label class="form-label required-label">Username</label>
                <input type="text" name="username" class="form-control @error('username') is-invalid @enderror"
                  value="{{ old('username') }}" placeholder="Enter your username" required />
                @error('username') <div class="invalid-feedback">{{ $message }}</div>
                @else <div class="form-text">Username minimal 5 karakter</div> @enderror
              </div>

              <!-- Email -->
              <div class="mb-2">
                <label class="form-label required-label">Email</label>
                <input type="text" name="email" class="form-control @error('email') is-invalid @enderror"
                  value="{{ old('email') }}" placeholder="Enter your email" required />
                @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
              </div>

              <!-- Gender -->
              <div class="mb-2">
                <label class="form-label required-label">Jenis Kelamin</label>
                <select name="gender" class="form-select @error('gender') is-invalid @enderror">
                  <option disabled selected>Pilih Jenis Kelamin</option>
                  <option @if(old('gender')=='Laki-Laki') selected @endif>Laki-Laki</option>
                  <option @if(old('gender')=='Perempuan') selected @endif>Perempuan</option>
                </select>
                @error('gender') <div class="invalid-feedback">{{ $message }}</div> @enderror
              </div>

              <!-- Password -->
              <div class="mb-2">
                <label class="form-label required-label">Password</label>
                <input type="password" name="password"
                  class="form-control @error('password') is-invalid @enderror" required />
                @error('password') <div class="form-text text-danger">{{ $message }}</div>
                @else <div class="form-text">Minimal 8 karakter</div> @enderror
              </div>

              <!-- Konfirmasi Password -->
              <div class="mb-3">
                <label class="form-label required-label">Ulangi Password</label>
                <input type="password" name="password_confirmation" class="form-control" required />
              </div>

              <!-- Terms -->
              <div class="mb-4">
                <input type="checkbox" name="terms" class="form-check-input" />
                <label>Saya setuju dengan <a href="/terms">syarat & ketentuan</a></label>
              </div>

              <!-- Tombol submit -->
              <button class="btn btn-primary w-100" type="submit">Sign Up</button>

            </form>

            <p class="text-center mt-3">Sudah punya akun? <a href="/login">Log in</a></p>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Vendor JS -->
  <script src="{{ asset('assets/vendors/assets/vendor/libs/jquery/jquery.js') }}"></script>
  <script src="{{ asset('assets/vendors/assets/vendor/js/bootstrap.js') }}"></script>
  <script src="{{ asset('assets/js/register.js') }}"></script>
</body>
</html>
