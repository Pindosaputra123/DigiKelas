@extends('layouts.main.index') 
{{-- Menggunakan layout utama dari folder layouts/main --}}

@section('container')
{{-- Bagian konten utama halaman dimulai --}}

@section('style')
<style>
  /* Styling responsif untuk ukuran font breadcrumb */
  @media screen and (max-width: 1320px) {
    ::-webkit-scrollbar {
      display: none; /* Hilangkan scrollbar pada layar kecil */
    }

    .navbreadcrumb {
      font-size: 14px;
    }
  }

  @media screen and (min-width: 1320px) {
    .navbreadcrumb {
      font-size: 14px;
    }
  }

  @media screen and (max-width: 576px) {
    .navbreadcrumb {
      font-size: small; /* Ukuran font breadcrumb lebih kecil di layar HP */
    }
  }

  /* Membuat teks jawaban agar rapi dan tidak terpotong */
  .answer {
    word-wrap: break-word;
    white-space: normal;
    display: block;
  }
</style>
@endsection

<div class="card">
  <div class="card-body">
    {{-- Breadcrumb navigasi dan tombol petunjuk --}}
    <div class="d-flex justify-content-between mb-2">
      <nav aria-label="breadcrumb" class="navbreadcrumb">
        <ol class="breadcrumb">
          <li class="breadcrumb-item">
            <a href="/quiz" class="text-primary">Quiz</a>
          </li>
          <li class="breadcrumb-item active">{{ $titleQuiz }}</li>
        </ol>
      </nav>
      {{-- Tombol untuk membuka modal petunjuk --}}
      <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="modal" data-bs-target="#petunjukQuiz">
        <i class="bx bx-bulb text-primary mb-3 iconPetunjukQuiz" data-bs-toggle="tooltip" data-popup="tooltip-custom" data-bs-placement="auto" title="Petunjuk Quiz" style="font-size: 20px;"></i>
      </button>
    </div>

    {{-- Pesan flash jika ada notifikasi --}}
    <div class="flash-message" data-flash-message="@if(session()->has('messages')) {{ session('messages') }} @endif"></div>

    {{-- Form utama untuk mengerjakan quiz --}}
    <form action="/quiz" method="post" id="quizForm">
      @csrf
      {{-- Input tersembunyi untuk keamanan dan data unik --}}
      <input type="hidden" name="quizCode" value="{{ encrypt($codeQuiz) }}">
      <input type="hidden" name="bubblesmart" value="{{ encrypt($bubblesmart) }}">

      {{-- Loop pertanyaan quiz secara acak --}}
      @foreach ($quizzes->shuffle() as $index => $quiz)
      <div class="d-flex">
        {{-- Nomor urut pertanyaan --}}
        <div>{{ $index + 1 }}.</div>

        {{-- Tampilkan pertanyaan, dengan ukuran font disesuaikan jika non-latin --}}
        @if (preg_match("/[\x{0000}-\x{007F}]/u", $quiz->title))
        <p style="font-size: 1rem; margin-left: 10px;">{{ $quiz->title }}</p>
        @else
        <p class="mb-4" style="font-size: 1.3rem; margin-top:-5px; margin-left: 10px;">{{ $quiz->title }}</p>
        @endif
      </div>

      {{-- Daftar jawaban pilihan ganda --}}
      <ol type="A" style="margin-top: -10px; margin-left: 10px; font-size: 1rem;">
        @foreach($quiz->answer->shuffle() as $answer)
        <div class="d-flex">
          <li>
            {{-- Input radio tersembunyi untuk memilih jawaban --}}
            <input type="radio" id="{{ $answer->id }}" name="answer[{{ $quiz->id }}]" value="{{ encrypt($answer->id) }}" class="form-check-input" hidden>

            {{-- Label jawaban dengan format berbeda untuk teks latin & non-latin --}}
            @if (preg_match("/[\x{0000}-\x{007F}]/u", $answer->answer))
              <label for="{{ $answer->id }}" class="form-check-label mb-1 answer cursor-pointer text-capitalize">{{ $answer->answer }}</label>
            @else
              <label for="{{ $answer->id }}" style="font-size:1.2rem;" class="form-check-label mb-1 answer cursor-pointer mt-1">{{ $answer->answer }}</label>
            @endif
          </li>
        </div>
        @endforeach
      </ol>
      @endforeach

      {{-- Tombol aksi di bawah soal --}}
      <button type="button" onclick="window.location.href='/quiz'" class="btn btn-danger mt-2 me-1 mb-2 btlQuiz">
        <i class='bx bx-share fs-5' style="margin-bottom: 3px;"></i>&nbsp;Batal
      </button>
      <button type="submit" class="btn btn-primary mt-2 mb-2 buttonSumbitQuiz">
        <i class='bx bx-task fs-5' style="margin-bottom: 3px;"></i>&nbsp;Selesai
      </button>
    </form>

    {{-- Jika tidak ada pertanyaan --}}
    @if($quizzes->isEmpty())
    <div class="d-flex justify-content-center align-items-center" style="height: 50vh;">
      <span style="font-size: medium;">
        <i class='bx bx-info-circle fs-5' style="margin-bottom: 2px;"></i>&nbsp;Belum ada pertanyaan disini!
      </span>
    </div>
    @endif
  </div>
</div>

<!-- Modal Petunjuk -->
<div class="modal fade" id="petunjukQuiz" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header d-flex justify-content-between">
        <h5 class="modal-title text-primary fw-bold">
          Petunjuk Quiz&nbsp;<i class='bx bx-bulb fs-5' style="margin-bottom: 3px;"></i>
        </h5>
        <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-dismiss="modal">
          <i class="bx bx-x-circle text-danger fs-4" title="Tutup"></i>
        </button>
      </div>
      <div class="modal-body" style="margin-top: -10px;">
        <div class="row mb-2">
          <div class="col-sm">
            {{-- Petunjuk cara mengerjakan quiz --}}
            <div class="mb-1">1. Klik pada jawaban untuk menjawab soal.</div>
            <div class="mb-1">2. Minimal mengerjakan satu soal.</div>
            <div class="mb-1">3. Tidak ada batas waktu saat mengerjakan.</div>
            <div>4. Klik tombol <b>Selesai</b> jika anda sudah selesai mengerjakan.</div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Modal Konfirmasi sebelum kirim -->
<div class="modal fade" id="submitQuiz" data-bs-backdrop="static" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header d-flex justify-content-between">
        <h5 class="modal-title text-primary fw-bold">
          Konfirmasi&nbsp;<i class='bx bx-check-shield fs-5' style="margin-bottom: 3px;"></i>
        </h5>
        <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-dismiss="modal">
          <i class="bx bx-x-circle text-danger fs-4" title="Tutup"></i>
        </button>
      </div>
      <div class="modal-body" style="margin-top: -10px;">
        <div class="col-sm fs-6">
          Jika Anda sudah yakin dengan jawaban Anda, tekan <strong>'Kirim'</strong> untuk mengirim jawaban Anda.
        </div>
      </div>
      <div class="modal-footer" style="margin-top: -5px;">
        {{-- Tombol batal dan kirim jawaban --}}
        <button type="button" class="btn btn-outline-danger cancelConfirmQuiz" data-bs-dismiss="modal">
          <i class='bx bx-share fs-6'></i>&nbsp;Batal
        </button>
        <button type="button" class="btn btn-primary confirmQuiz">
          <i class='bx bx-paper-plane fs-6'></i>&nbsp;Kirim
        </button>
      </div>
    </div>
  </div>
</div>

{{-- Load file JavaScript untuk fungsi interaktif --}}
@section('script')
<script src="{{ asset('assets/js/quiz/start.js') }}"></script>
@endsection

@endsection