@extends('layouts.main.index') 
{{-- Menggunakan layout utama dari folder layouts/main/index.blade.php --}}

@section('container')
{{-- Bagian utama konten halaman --}}

<h1 class="fs-5">Daftar Materi Aksara</h1>

<div class="row">
  <div class="d-flex flex-wrap" id="icons-container">
    {{-- Menampilkan semua data materi dalam bentuk kartu (card) --}}

    @forelse ($materis as $materi)
      {{-- Looping setiap data materi dari variabel $materis --}}
      <div 
        class="box card icon-card cursor-pointer text-center mb-4 mx-2" 
        data-source-audio="{{ $materi->audio }}"
        {{-- Atribut custom untuk menyimpan sumber audio yang akan diputar saat diklik --}}
      >
        <div class="card-body">
          <h2>
            {{-- Menampilkan gambar untuk setiap materi --}}
            <img 
              src="@if(Storage::disk('public')->exists($materi->image)) 
                {{ asset('storage/'. $materi->image) }} 
                @else 
                  {{ asset($materi->image) }} 
                @endif" 
              style="width: 45px;" 
              alt="{{ $materi->title }}">
          </h2>

          {{-- Menampilkan judul materi, mengganti underscore menjadi spasi --}}
          <p class="icon-name text-capitalize text-truncate mb-0">
            {{ str_replace('_',' ', $materi->title) }}
          </p>
        </div>
      </div>
    @empty
      {{-- Jika tidak ada data materi --}}
      <p class="text-muted">Belum ada data materi.</p>
    @endforelse

  </div>
</div>

{{-- ===================== DAFTAR AUDIO ===================== --}}
{{-- Setiap materi yang memiliki file audio akan dibuatkan elemen <audio> tersembunyi --}}
@foreach($materis as $audio)
  @if($audio->audio)
    <audio class="d-none" id="{{ $audio->audio }}" controls>
      <source 
        src="@if(Storage::disk('public')->exists($audio->audio)) 
                {{ asset('storage/'. $audio->audio) }} 
              @else 
                {{ asset($audio->audio) }} 
              @endif"
        type="audio/mpeg">
    </audio>
  @endif
@endforeach

@endsection

@section('script')
  {{-- Memuat file JavaScript khusus halaman materi --}}
  <script src="{{ asset('assets/js/materi/index.js') }}"></script>
@endsection
