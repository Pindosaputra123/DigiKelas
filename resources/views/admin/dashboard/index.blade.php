{{-- Menggunakan layout utama --}}
@extends('layouts.main.index')

{{-- Section untuk konten utama --}}
@section('container')

{{-- Section tambahan untuk CSS khusus --}}
@section('css')
<link rel="stylesheet" href="{{ asset('assets/css/custom.css') }}">
<link rel="stylesheet" href="{{ asset('assets/vendors/assets/vendor/libs/apex-charts/apex-charts.css') }}" />
@endsection

<div class="row">
  {{-- Card Total Pengguna --}}
  <div class="col-sm-6 col-lg-4 mb-4">
    <div class="card card-border-shadow-primary h-100">
      <div class="card-body">
        <div class="d-flex align-items-center mb-2 pb-1">
          <div class="avatar me-2">
            {{-- Ikon User --}}
            <span class="avatar-initial rounded bg-label-primary"><i class="bx bx-group"></i></span>
          </div>
          {{-- Menampilkan total pengguna --}}
          <h4 class="ms-1 mb-0">{{ $totalMember }}</h4>
        </div>
        <p class="mb-1 fw-semibold">Total Pengguna</p>
      </div>
    </div>
  </div>

  {{-- Card Total Quiz --}}
  <div class="col-sm-6 col-lg-4 mb-4">
    <div class="card card-border-shadow-warning h-100">
      <div class="card-body">
        <div class="d-flex align-items-center mb-2 pb-1">
          <div class="avatar me-2">
            {{-- Ikon Joystick/Quiz --}}
            <span class="avatar-initial rounded bg-label-warning"><i class='bx bx-joystick'></i></span>
          </div>
          {{-- Menampilkan total quiz --}}
          <h4 class="ms-1 mb-0">{{ $totalQuiz }}</h4>
        </div>
        <p class="mb-1 fw-semibold">Total Quiz</p>
      </div>
    </div>
  </div>

  {{-- Card Jumlah Jawaban Quiz --}}
  <div class="col-sm-6 col-lg-4 mb-4">
    <div class="card card-border-shadow-success h-100">
      <div class="card-body">
        <div class="d-flex align-items-center mb-2 pb-1">
          <div class="avatar me-2">
            {{-- Ikon jawaban --}}
            <span class="avatar-initial rounded bg-label-success"><i class='bx bx-receipt'></i></span>
          </div>
          {{-- Total jawaban quiz --}}
          <h4 class="ms-1 mb-0">{{ $answersQuiz }}</h4>
        </div>
        <p class="mb-1 fw-semibold">Jawab Quiz</p>
      </div>
    </div>
  </div>
</div>

{{-- Section daftar pengguna --}}
<div class="row">
  <div class="col-md-12 col-lg-12 order-0 mb-4">
    <div class="card h-100">
      <div class="card-header">
        <div>
          <h5 class="card-title m-0 me-2 fw-bold mb-2">Data Pengguna</h5>
          <small class="text-muted">Total semua pengguna aplikasi</small>
        </div>
      </div>
      <div class="card-body">
        {{-- Jika data members tidak kosong --}}
        @if(!$members->isEmpty())
        <div class="d-flex justify-content-between align-items-center mb-3">
          <div class="d-flex flex-column align-items-center gap-1">
            <h2 class="mb-2" style="color:#566a7f;">{{ $totalMember}}</h2>
            <span>Total Pengguna</span>
          </div>
        </div>
        <ul class="p-0 m-0">
          {{-- Loop data members --}}
          @foreach($members as $member)
          <li class="d-flex mb-4 pb-1">
            <div class="avatar flex-shrink-0 me-3">
              {{-- Menampilkan foto profil pengguna --}}
              <img src="@if(Storage::disk('public')->exists($member->image)) {{ asset('storage/'. $member->image) }} @else {{ asset('assets/img/'. $member->image) }} @endif" alt="Profile Image" class="rounded">
            </div>
            <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
              <div class="me-2">
                {{-- Nama pengguna --}}
                <h6 class="mb-1 text-capitalize">{{ $member->name }}</h6>
                {{-- Waktu bergabung --}}
                <small class="text-muted d-block">Bergabung {{ $member->created_at->locale('id')->diffForHumans() }}</small>
              </div>
              <div class="user-progress d-flex align-items-center gap-1">
                {{-- Status verifikasi --}}
                <span class="text-primary" title="Terverifikasi"><i class='bx bx-check-double bx-tada'></i></span>
              </div>
            </div>
          </li>
          @endforeach
        </ul>
        @else
        {{-- Tampilkan jika belum ada pengguna --}}
        <p class="text-center"><i class="bx bx-info-circle fs-6"></i> Belum ada pengguna</p>
        @endif
      </div>
    </div>
  </div>
</div>

{{-- CDN Chart --}}
<script src="{{ $chart->cdn() }}"></script>

{{-- Section script tambahan --}}
@section('script')
<script src="{{ asset('assets/vendors/assets/vendor/libs/apex-charts/apexcharts.js') }}"></script>
@endsection

{{-- Script untuk chart --}}
{{ $chart->script() }}

@endsection
