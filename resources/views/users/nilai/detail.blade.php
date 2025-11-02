@extends('layouts.main.index')

@section('container')
@section('style')
<style>
  /* Responsivitas ukuran teks breadcrumb dan scrollbar */
  @media screen and (max-width: 1320px) {
    ::-webkit-scrollbar {
      display: none;
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
      font-size: small;
    }
  }

  /* Agar badge bisa menyesuaikan panjang teks */
  .badge {
    word-wrap: break-word;
    white-space: normal;
    display: block;
  }
</style>
@endsection

{{-- Breadcrumb navigasi halaman detail nilai --}}
<nav aria-label="breadcrumb" class="navbreadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item">
      <a href="/nilai" class="text-primary">Nilai</a>
    </li>
    <li class="breadcrumb-item active">Details</li>
    <li class="breadcrumb-item active">{{ $titleQuiz }}</li>
  </ol>
</nav>

{{-- Kartu utama yang menampilkan rincian hasil quiz --}}
<div class="card">
  <div class="card-body">

    {{-- Tombol kembali dan tanggal pengerjaan quiz --}}
    <div class="d-flex justify-content-between mb-3">
      <button type="button" onclick="window.location.href='/nilai'" class="btn btn-primary btn-sm">
        <i class='bx bx-left-arrow-alt bx-xs' style="margin-bottom: 3px;"></i>&nbsp;Kembali
      </button>
      <div style="margin-top: 3px;">
        <i class='bx bx-history bx-spin text-primary mb-1' data-bs-toggle="tooltip" title="Tanggal Sumbit Quiz"></i>&nbsp;
        {{ $tanggalMengerjakanQuiz->locale('id')->isoFormat('D MMMM YYYY | H:mm') }}
      </div>
    </div>

    {{-- Tabel ringkasan hasil quiz --}}
    <div class="table-responsive text-nowrap mb-3">
      <table class="table table-bordered">
        <thead>
          <tr>
            <th>Nama Lengkap</th>
            <th>Judul Quiz</th>
            <th>Total Soal</th>
            <th>Jawaban Benar</th>
            <th>Jawaban Salah</th>
            <th>Nilai</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td>{{ auth()->user()->name }}</td>
            {{-- Menyesuaikan ukuran teks jika karakter non-ASCII --}}
            @if (preg_match("/[\x{0000}-\x{007F}]/u", $titleQuiz))
            <td>{{ Str::limit($titleQuiz, 40, '...') }}</td>
            @else
            <td style="font-size: 18px;">{{ Str::limit($titleQuiz, 31, '...') }}</td>
            @endif
            <td>{{ $questions->count() }} Soal</td>
            <td>{{ $correct->count() }} Soal</td>
            <td>{{ $questions->count() - $correct->count() }} Soal</td>
            <td>{{ $totalScore }}</td>
          </tr>
        </tbody>
      </table>
    </div>

    {{-- Menampilkan daftar pertanyaan dan jawaban --}}
    @foreach ($scores as $index => $score)
    <div class="d-flex">
      <div>{{ $index + 1 }}.</div>
      {{-- Cek apakah pertanyaan berisi karakter latin atau tidak --}}
      @if (preg_match("/[\x{0000}-\x{007F}]/u", $score->question->title))
      <p style="font-size: 1rem; margin-left: 10px;">{{ $score->question->title }}</p>
      @else
      <p style="font-size: 1.3rem; margin-top:-5px; margin-left: 10px;">{{ $score->question->title }}</p>
      @endif
    </div>

    {{-- Daftar pilihan jawaban --}}
    <ol type="A" style="margin-top: -10px; margin-left:10px; font-size: 1rem;">
      @foreach($score->question->answer as $answer)
      @if($score->answer_id !== null)
      <li>
        <label class="form-check-label mb-1 text-capitalize" style="display:block; word-wrap: break-word; white-space: normal;">
          {{-- Menandai jawaban benar dengan badge hijau dan ikon centang --}}
          @if($score->correct && $answer->answer === $score->answer->answer)
          <div class="d-flex">
            <span class="badge bg-label-success text-capitalize">{{ $answer->answer }}</span>
            &nbsp;<i class="bx bx-check bx-tada text-success" title="Jawaban Anda Benar!" style="font-size: 20px;"></i>
          </div>

          {{-- Menandai jawaban salah dengan badge merah dan ikon silang --}}
          @elseif(!$score->correct && $answer->answer === $score->answer->answer)
          <div class="d-flex">
            <span class="badge bg-label-danger text-capitalize">{{ $answer->answer }}</span>
            &nbsp;<i class="bx bx-x bx-tada text-danger" title="Jawaban Anda Salah!" style="font-size: 20px;"></i>
          </div>

          {{-- Jawaban lain yang bukan pilihan pengguna --}}
          @else
          <div>{{ $answer->answer }}</div>
          @endif
        </label>
      </li>
      @else
      {{-- Jika pengguna tidak menjawab soal --}}
      <li>
        <label class="form-check-label mb-1">{{ $answer->answer }}</label>
      </li>
      @endif
      @endforeach
    </ol>

    {{-- Peringatan untuk soal yang tidak dijawab --}}
    @if($score->answer_id == null)
    <div class="d-flex" style="margin-left: 20px; margin-top:-10px; margin-bottom:20px">
      <span class="badge bg-label-warning">Anda tidak menjawab soal ini!</span>
      &nbsp;<i class="bx bx-error text-warning bx-flashing" title="Jawaban Anda Salah!" style="font-size: 20px;"></i>
    </div>
    @endif
    @endforeach

  </div>
</div>
@endsection
