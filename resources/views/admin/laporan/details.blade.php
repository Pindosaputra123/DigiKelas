@extends('layouts.main.index') {{-- Menggunakan layout utama --}}
@section('container') {{-- Bagian konten utama --}}

@section('style')
<style>
  /* Responsive styling untuk tampilan breadcrumbs */
  @media screen and (max-width: 1320px) {
    ::-webkit-scrollbar {
      display: none; /* Hilangkan scrollbar pada ukuran tertentu */
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
      font-size: small; /* Ukuran kecil untuk layar mobile */
    }
  }

  /* Supaya badge tidak memotong teks */
  .badge {
    word-wrap: break-word;
    white-space: normal;
    display: block;
  }
</style>
@endsection

{{-- Breadcrumb Navigasi --}}
<nav aria-label="breadcrumb" class="navbreadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item">
      <a href="/admin/laporan" class="text-primary">Laporan</a>
    </li>
    <li class="breadcrumb-item">
      {{-- Kembali ke laporan quiz tertentu --}}
      <a href="/admin/laporan/{{ $dataquiz->slug }}" class="text-primary">{{ $dataquiz->title }}</a>
    </li>
    <li class="breadcrumb-item active">Details</li> {{-- Halaman sekarang --}}
  </ol>
</nav>

<div class="card">
  <div class="card-body">
    {{-- Tombol kembali & info tanggal submit --}}
    <div class="d-flex justify-content-between mb-3">
      <button type="button" onclick="window.location.href='/admin/laporan/{{ $dataquiz->slug }}'" class="btn btn-primary btn-sm">
        <i class='bx bx-left-arrow-alt bx-xs'></i>&nbsp;Kembali
      </button>
      <div>
        <i class='bx bx-history bx-spin text-primary' title="Tanggal Submit Quiz"></i>&nbsp;
        {{ $dataresult->created_at->locale('id')->isoFormat('D MMMM YYYY | H:mm') }}
      </div>
    </div>

    {{-- Tabel Ringkasan Quiz --}}
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
            <td>{{ $dataresult->user->name }}</td> {{-- Nama user --}}
            {{-- Batasi panjang judul agar tidak overflow --}}
            @if (preg_match("/[\x{0000}-\x{007F}]/u", $dataquiz->title))
              <td>{{ Str::limit($dataquiz->title, 40, '...') }}</td>
            @else
              <td style="font-size: 18px;">{{ Str::limit($dataquiz->title, 31, '...') }}</td>
            @endif
            <td>{{ $questions->count() }} Soal</td>
            <td>{{ $correct->count() }} Soal</td>
            <td>{{ $questions->count() - $correct->count() }} Soal</td>
            <td>{{ $totalScore }}</td>
          </tr>
        </tbody>
      </table>
    </div>

    {{-- Loop setiap soal --}}
    @foreach ($scores as $index => $score)
    <div class="d-flex">
      <div>{{ $index + 1 }}.</div> {{-- Nomor soal --}}
      {{-- Tampilkan soal --}}
      @if (preg_match("/[\x{0000}-\x{007F}]/u", $score->question->question))
        <p style="font-size: 1rem; margin-left: 10px;">{{ $score->question->question }}</p>
      @else
        <p style="font-size: 1.3rem; margin-top:-5px; margin-left: 10px;">{{ $score->question->question }}</p>
      @endif
    </div>

    {{-- Pilihan jawaban --}}
    <ol type="A" style="margin-top: -10px; margin-left:10px; font-size: 1rem;">
      @foreach($score->question->answer as $answer)
      @if($score->answer_id !== null)
      <li>
        {{-- Jika ada jawaban user --}}
        <label class="form-check-label mb-1" style="display:block;">
          {{-- Jawaban benar --}}
          @if($score->correct && $answer->answer === $score->answer->answer)
          <div class="d-flex">
            <span class="badge bg-label-success">{{ $answer->answer }}</span>
            <i class="bx bx-check bx-tada text-success" title="Jawaban Benar"></i>
          </div>

          {{-- Jawaban salah --}}
          @elseif(!$score->correct && $answer->answer === $score->answer->answer)
          <div class="d-flex">
            <span class="badge bg-label-danger">{{ $answer->answer }}</span>
            <i class="bx bx-x bx-tada text-danger" title="Jawaban Salah"></i>
          </div>

          {{-- Jawaban lain --}}
          @else
          <div>{{ $answer->answer }}</div>
          @endif
        </label>
      </li>
      @else
      {{-- Jika user tidak menjawab --}}
      <li><label>{{ $answer->answer }}</label></li>
      @endif
      @endforeach
    </ol>

    {{-- Info jika tidak menjawab --}}
    @if($score->answer_id == null)
    <div class="d-flex" style="margin-left: 20px; margin-top:-10px; margin-bottom:20px">
      <span class="badge bg-label-warning">Anda tidak menjawab soal ini!</span>
      <i class="bx bx-error text-warning"></i>
    </div>
    @endif
    @endforeach
  </div>
</div>
@endsection
