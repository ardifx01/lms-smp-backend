<p align="center">
  <img src="./public/logoo.png" width="200" alt="Logo SMP Digital">
</p>

<h1 align="center">LMS SMP Digital - Backend</h1>

Ini adalah backend API untuk aplikasi **Learning Management System (LMS) SMP Digital**. Dibangun menggunakan **Laravel**, API ini menyediakan semua fungsionalitas inti yang dibutuhkan oleh aplikasi, termasuk autentikasi pengguna, manajemen konten pembelajaran, dan interaksi antar pengguna.

Backend ini dirancang untuk menjadi *headless*, artinya ia hanya fokus pada penyediaan data dalam format JSON dan tidak memiliki tampilan sendiri. Ia melayani data untuk [**Frontend Next.js**](https://github.com/ardifx01/lms-smp-frontend).

---

## ✨ Fitur Utama

-   **Autentikasi Aman**: Menggunakan Laravel Sanctum untuk autentikasi berbasis token.
-   **Manajemen Peran**: Sistem membedakan antara peran **Guru** dan **Murid** dengan hak akses yang berbeda.
-   **Manajemen Materi**: Guru dapat mengupload file materi (PDF, DOCX, dll.) untuk setiap kelas.
-   **Manajemen Tugas & Penilaian**: Guru dapat membuat tugas, murid dapat mengumpulkan jawaban, dan guru dapat memberikan nilai.
-   **Forum Diskusi**: Pengguna dapat membuat topik diskusi dan saling membalas komentar.
-   **Manajemen Profil**: Pengguna dapat memperbarui informasi pribadi mereka, termasuk foto profil.
-   **Fitur Tambahan**: Termasuk rekap nilai, pencarian, dan ekspor data nilai ke PDF untuk wali kelas.

---

## 🛠️ Teknologi yang Digunakan

-   PHP & Laravel Framework
-   MySQL
-   Laravel Sanctum (untuk Autentikasi API)
-   `barryvdh/laravel-dompdf` (untuk Ekspor PDF)

---

## 🚀 Panduan Instalasi Lokal

Berikut adalah cara untuk menjalankan proyek ini di lingkungan development lokal Anda menggunakan Laragon.

### Prasyarat

-   Laragon (atau environment sejenis seperti XAMPP/WAMP dengan Composer)
-   Git

### Langkah-langkah Instalasi

1.  **Clone Repositori**
    Buka terminal Laragon Anda, masuk ke direktori `www`, lalu clone proyek ini:
    ```bash
    git clone [https://github.com/ardifx01/lms-smp-backend.git](https://github.com/ardifx01/lms-smp-backend.git)
    cd lms-smp-backend
    ```

2.  **Install Dependensi**
    Gunakan Composer untuk menginstal semua paket PHP yang dibutuhkan.
    ```bash
    composer install
    ```

3.  **Setup File Environment (`.env`)**
    Salin file contoh `.env` dan buat file `.env` Anda sendiri.
    ```bash
    copy .env.example .env
    ```
    Buka file `.env` yang baru dan sesuaikan konfigurasi database Anda.
    ```env
    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=lms_smp
    DB_USERNAME=root
    DB_PASSWORD=
    ```

4.  **Generate Kunci Aplikasi**
    ```bash
    php artisan key:generate
    ```

5.  **Jalankan Migrasi Database**
    Perintah ini akan membuat semua tabel di dalam database `lms_smp`.
    ```bash
    php artisan migrate
    ```

6.  **Buat Symbolic Link untuk Storage**
    Ini penting agar file yang diupload (seperti foto profil) bisa diakses dari web.
    ```bash
    php artisan storage:link
    ```

7.  **Jalankan Server Development**
    ```bash
    php artisan serve
    ```
    Server akan berjalan di `http://127.0.0.1:8000`.

### (Opsional) Menambahkan Data Awal

Untuk mengisi database Anda dengan data contoh, Anda bisa menjalankan `php artisan tinker` dan menempelkan kode *seeder* yang telah kita gunakan selama proses development.
