@extends('layouts.main.index') {{-- Menggunakan layout utama --}}
@section('container')
@section('style')
<style>
  /* Menghilangkan scrollbar */
  ::-webkit-scrollbar {
    display: none;
  }

  /* Style untuk desktop lebar */
  @media screen and (min-width: 1320px) {
    #search {
      width: 250px;
    }
    .navbreadcrumb {
      font-size: 14px;
    }
  }

  /* Style untuk laptop */
  @media screen and (max-width: 1320px) {
    .navbreadcrumb {
      font-size: 14px;
    }
  }

  /* Style untuk mobile */
  @media screen and (max-width: 575px) {
    .pagination-mobile {
      display: flex;
      justify-content: end;
    }
    .navbreadcrumb {
      font-size: small;
    }
  }
</style>
@endsection

{{-- Breadcrumb navigasi halaman --}}
<nav aria-label="breadcrumb" class="navbreadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item">
      <a href="/admin/laporan" class="text-primary">Laporan</a>
    </li>
    <li class="breadcrumb-item active">{{ $dataquiz->title }}</li> {{-- Menampilkan judul quiz --}}
  </ol>
</nav>

<div class="row">
  <div class="col-md-12 col-lg-12 order-2 mb-4">
    <div class="card h-100">
      {{-- Header Card: berisi filter dan pencarian --}}
      <div class="card-header d-flex align-items-center justify-content-between" style="margin-bottom: -0.7rem;">
        <div class="justify-content-start">
          {{-- Dropdown filter nilai --}}
          <select data-url="{{ $dataquiz->slug }}" id="score-filter" class="form-select" style="border: 1px solid #d9dee3;">
            <option disabled selected>Filter Nilai</option>
            <option value="tertinggi" @if(request('filter')==='tertinggi' ) selected @endif>Nilai Tertinggi</option>
            <option value="terendah" @if(request('filter')==='terendah' ) selected @endif>Nilai Terendah</option>
          </select>
        </div>

        {{-- Form pencarian data --}}
        <div class="justify-content-end">
          <form action="/admin/laporan/{{ $dataquiz->slug }}/search">
            <div class="input-group">
              <input type="search" class="form-control" name="q" id="search" style="border: 1px solid #d9dee3;" placeholder="Cari Data Pengguna..." autocomplete="off" />
            </div>
          </form>
        </div>
      </div>

      {{-- Body tabel laporan --}}
      <div class="card-body">
        <ul class="p-0 m-0">
          <div class="table-responsive text-nowrap" style="border-radius: 3px;">
            <table class="table table-striped">
              <thead class="table-dark">
                <tr>
                  <th>No</th>
                  <th>Nama Lengkap</th>
                  <th>Jenis Kelamin</th>
                  <th>Soal Yang Dikerjakan</th>
                  <th>Soal Tidak Dikerjakan</th>
                  <th>Tanggal Submit Quiz</th>
                  <th class="text-center">Nilai</th>
                  <th class="text-center">Aksi</th>
                </tr>
              </thead>
              <tbody>
                {{-- Looping data laporan --}}
                @foreach($reports as $index => $data)
                <tr>
                  {{-- Nomor baris --}}
                  <td>{{ $reports->firstItem() + $index }}</td>
                  {{-- Nama pengguna --}}
                  <td>{{ $data->user->name }}</td>
                  {{-- Badge gender --}}
                  <td>
                    @if($data->user->gender == 'Laki-Laki')
                      <span class="badge bg-label-primary fw-bold">Laki-Laki</span>
                    @else
                      <span class="badge fw-bold" style="color: #ff6384; background-color: #ffe5eb;">Perempuan</span>
                    @endif
                  </td>

                  {{-- Jumlah soal dikerjakan --}}
                  <td>
                    <i class="bx bx-task bx-tada text-primary"></i>
                    <span class="badge bg-label-primary">
                      {{ $data->answer_user->where('answer_id', !null)->count() }} Soal dikerjakan
                    </span>
                  </td>

                  {{-- Jumlah soal tidak dikerjakan --}}
                  <td>
                    {!! (($data->quiz->load('question')->question->count() - $data->answer_user->where('answer_id', !null)->count()) > 0)
                      ? '<i class="bx bx-error bx-flashing text-danger"></i>&nbsp;<span class="badge bg-label-danger">'.
                        $data->quiz->load('question')->question->count() - $data->answer_user->where('answer_id', !null)->count() .
                        ' Soal tidak dikerjakan</span>'
                      : '<i class="bx bx-check-double bx-tada text-success"></i>&nbsp;<span class="badge bg-label-dark">Dikerjakan Semua</span>'
                    !!}
                  </td>

                  {{-- Tanggal submit --}}
                  <td>{{ $data->created_at->locale('id')->isoFormat('D MMMM YYYY | H:mm') }}</td>

                  {{-- Nilai --}}
                  <td class="text-center">
                    <span class="badge badge-center bg-dark rounded-pill">{{ $data->score }}</span>
                  </td>

                  {{-- Tombol aksi --}}
                  <td class="text-center">
                    <button onclick="window.location.href='/admin/laporan/details/{{ $data->code }}'" class="btn btn-icon btn-primary btn-sm" title="Details">
                      <span class="bx bx-show"></span>
                    </button>
                  </td>
                </tr>
                @endforeach

                {{-- Jika data kosong --}}
                @if($reports->isEmpty())
                <tr>
                  <td colspan="100" class="text-center">Data tidak ditemukan!</td>
                </tr>
                @endif
              </tbody>
            </table>
          </div>
        </ul>

        {{-- Pagination --}}
        @if(!$reports->isEmpty())
          <div class="mt-3 pagination-mobile">{{ $reports->withQueryString()->onEachSide(1)->links() }}</div>
        @endif
      </div>
    </div>
  </div>
</div>

{{-- Script filter --}}
@section('script')
<script>
  $("#score-filter").on("change", function() {
    var selectedOption = this.value;
    var url = $("#score-filter").data('url');
    // Redirect berdasarkan filter
    window.location.href = "/admin/laporan/" + url + "?filter=" + selectedOption;
  });
</script>
@endsection
@endsection
