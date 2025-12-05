# Anggota Kelompok
1. Pindo Saputra Harmanto
2. Dunik Andriyani
3. Ilham Syihabudin


## Tentang DigiKelas

<b>DigiKelas</b> adalah aplikasi belajar Bahasa Jepang yang menghadirkan pengalaman belajar yang mudah, interaktif, dan menyenangkan. Dirancang untuk pemula hingga tingkat menengah, DigiKelas membantu kamu menguasai aksara Jepang dan memahami bahasa Jepang melalui metode belajar yang ringan tetapi efektif.

### Fitur DigiKelas:

#### Admin

-   <b>Dashboard</b><br>
    Di dalam 'Dashboard', admin bisa melihat statistik pengguna yang mengerjakan quiz, total pengguna, dan total quiz.

-   <b>Data Materi</b><br>
    Dalam menu 'Data Materi', admin dapat melakukan insert data materi, update data materi, dan juga delete data materi. Selain itu, terdapat juga fitur searching untuk melakukan filter saat mencari data materi. Data materi berisi gambar, judul, dan juga audio.

-   <b>Data Quiz</b><br>
    Di dalam menu 'Data Quiz', admin dapat melakukan insert data quiz, update data quiz, delete data quiz, lihat pertanyaan, tambah pertanyaan, update pertanyaan, dan juga delete pertanyaan. Terdapat juga fitur pencarian data quiz dan juga data pertanyaan.

-   <b>Laporan</b><br>
    Dalam menu 'Laporan', admin dapat melihat siapa saja yang sudah mengerjakan quiz, serta dapat melihat jawaban dan juga nilai pengguna aplikasi.

-   <b>Pengaturan</b><br>
    Di menu 'Pengaturan', admin dapat melihat dan mengubah informasi identitas pribadi, termasuk perubahan identitas, perubahan password, dan pengaturan lain yang terkait dengan aplikasi.

#### Member

-   <b>Materi</b><br>
    Di menu 'Materi', pengguna dapat mempelajari berbagai aksara Jepang secara lengkap. Materi disajikan dengan visual yang jelas, contoh kata, serta audio pelafalan untuk membantu memahami cara baca yang benar

-   <b>Quiz</b><br>
    Dalam menu 'Quiz', terdapat soal-soal yang bisa dikerjakan untuk melatih pemahaman tentang aksara Jepang. Quiz ini bisa dikerjakan secara berulang-ulang.

-   <b>Nilai</b><br>
    Setiap selesai menjawab quiz, pengguna dapat melihat nilainya langsung di menu 'Nilai'. Pengguna juga dapat melihat pertanyaan mana saja yang jawabannya benar dan juga salah. Selain itu, pengguna juga bisa menghapus histori mengerjakan quiznya.

-   <b>Pengaturan</b><br>
    Dalam menu 'Pengaturan', pengguna dapat melihat dan mengubah identitas diri seperti nama, alamat, foto profil dan lain-lain. Pengguna juga dapat mengubah password.

## Installation

#### 1. Clone the repository

```sh
git clone https://github.com/Pindosaputra123/DigiKelas.git
```

#### 2. Change Directory

```sh
cd DigiKelas
```

#### 3. Copy .env

```sh
cp .env.example .env
```

#### 4. Configure .env

```sh
FILESYSTEM_DISK=public
```

#### 5. Install depedencies

```sh
composer install
```

#### 6. Generate Key

```sh
php artisan key:generate
```

#### 7. Run Symlink

```sh
php artisan storage:link
```

#### 8. Migrate database

```sh
php artisan migrate
```

#### 9. Database seeders

```sh
php artisan db:seed
```

#### 10. Run application

```sh
php artisan serve
```

## Demo
<p align="center">
  <video src="https://private-user-images.githubusercontent.com/113245491/522844642-5cb3105d-ac02-425e-b113-d9f875633f2e.mp4" 
         controls 
         width="700">
  </video>
</p>

<p align="center">
  <video src="https://private-user-images.githubusercontent.com/113245491/522848547-80e21c04-686c-439d-b5a2-9c3a82b3ffa4.mp4" 
         controls 
         width="700">
  </video>
</p>

##### <i><b>Note:<br>username: pindo & password: pindo123 <br> username: dunik & password: dunik123</b></i>
