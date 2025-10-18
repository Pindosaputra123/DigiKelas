@extends('layouts.main.index') {{-- Menggunakan layout utama --}}
@section('container') {{-- Section konten utama --}}
@section('style')
<style>
  /* Sembunyikan scrollbar */
  ::-webkit-scrollbar {
    display: none;
  }

  /* Atur lebar input search untuk layar besar */
  @media screen and (min-width: 1320px) {
    #search {
      width: 250px;
    }
  }

  /* Atur posisi pagination untuk tampilan mobile */
  @media screen and (max-width: 575px) {
    .pagination-mobile {
      display: flex;
      justify-content: end;
    }
  }
</style>
@endsection

<div class="row">
  <div class="col-md-12 col-lg-12 order-2 mb-4">
    <div class="card h-100">
      <div class="card-body">
        <ul class="p-0 m-0">
          <div class="table-responsive text-nowrap" style="border-radius: 3px;">
            {{-- Tabel daftar laporan quiz --}}
            <table class="table table-striped">
              <thead class="table-dark">
                <tr>
                  <th class="text-white">No</th> {{-- Nomor urut --}}
                  <th class="text-white">Judul Quiz</th> {{-- Judul quiz --}}
                  <th class="text-white">Deskripsi Quiz</th> {{-- Deskripsi quiz --}}
                  <th class="text-white text-center">Total Yang Mengerjakan Quiz</th> {{-- Total peserta --}}
                  <th class="text-white text-center">Aksi</th> {{-- Aksi lihat detail --}}
                </tr>
              </thead>
              <tbody class="table-border-bottom-0">
                {{-- Looping data laporan --}}
                @foreach($reports as $index => $data)
                <tr>
                  {{-- Nomor urut dari pagination --}}
                  <td>{{ $reports->firstItem() + $index }}</td>

                  {{-- Validasi karakter agar judul tidak terlalu panjang --}}
                  @if (preg_match("/[\x{0000}-\x{007F}]/u", $data->title))
                  <td>{{ Str::limit($data->title, 30, '...') }}</td>
                  @else
                  <td style="font-size: 18px;">{{ Str::limit($data->title, 20, '...') }}</td>
                  @endif

                  {{-- Deskripsi quiz dengan limit --}}
                  <td>{{ Str::limit($data->description, 50, '...')}}</td>

                  {{-- Total yang mengerjakan quiz --}}
                  <td class="text-center"><span class="badge bg-label-primary fw-bold">{{ $data->result->count() }}&nbsp;Orang</span></td>

                  {{-- Tombol aksi detail laporan --}}
                  <td class="text-center">
                    <button type="button" class="btn btn-icon btn-primary btn-sm"
                      onclick="window.location.href='/admin/laporan/{{ $data->slug }}'"
                      data-bs-toggle="tooltip" data-popup="tooltip-custom" data-bs-placement="auto"
                      title="Lihat yang mengerjakan">
                      <span class="tf-icons bx bx-show" style="font-size: 15px;"></span>
                    </button>
                  </td>
                </tr>
                @endforeach

                {{-- Jika data kosong --}}
                @if($reports->isEmpty())
                <tr>
                  <td colspan="100" class="text-center">Data laporan tidak ditemukan!</td>
                </tr>
                @endif
              </tbody>
            </table>
          </div>
        </ul>

        {{-- Pagination --}}
        @if(!$reports->isEmpty())
        <div class="mt-3 pagination-mobile">
          {{ $reports->withQueryString()->onEachSide(1)->links() }}
        </div>
        @endif
      </div>
    </div>
  </div>
</div>
@endsection
