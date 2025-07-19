# E-Prescription - Sipatex Developer Assignment

Aplikasi E-Prescription sederhana ini dibangun sebagai bagian dari _developer assignment_ untuk Sipatex. Aplikasi ini memungkinkan pengguna (seperti dokter atau apoteker) untuk mencatat resep obat digital, baik dalam bentuk non-racikan maupun racikan, dengan manajemen stok yang terintegrasi.

Aplikasi ini dikembangkan menggunakan **PHP 8.2** dan **Laravel 12**, dengan antarmuka yang responsif dibangun menggunakan **TailwindCSS**. Interaktivitas ditingkatkan dengan sedikit JavaScript, dan fitur pencarian canggih untuk data obat/signa yang besar dioptimalkan menggunakan library **Choices.js**.

---

## Fitur Utama

-   **Formulir Resep Dinamis**: Mudah beralih antara input obat **Non-Racikan** dan **Racikan**.
-   **Manajemen Draft**: Susun resep secara bertahap dalam _draft_ yang disimpan di sisi server (Session). Item dapat dihapus satu per satu dari draft.
-   **Pencarian Cepat & Performa Tinggi**: Dropdown untuk memilih **Obat** dan **Signa** (aturan pakai) menggunakan pencarian via API, memastikan performa tetap cepat meskipun data master berisi ribuan baris.
-   **Manajemen Stok Real-time**: Stok obat di dropdown diperbarui secara dinamis berdasarkan item yang sudah ada di draft.
-   **Validasi Stok**: Aplikasi secara otomatis mencegah penambahan obat jika jumlah yang diminta melebihi stok yang tersedia.
-   **Simpan & Kurangi Stok**: Saat resep disimpan, stok obat di database akan berkurang secara otomatis dalam satu transaksi yang aman.
-   **Cetak ke PDF**: Resep digital dapat dicetak ke dalam format PDF dengan layout yang profesional.
-   **Error Handling & Logging**: Dilengkapi dengan penanganan error dan pencatatan log yang informatif.

---

## Prasyarat (Requirements)

Sebelum memulai, pastikan sistem memenuhi persyaratan berikut:

-   **PHP 8.2 atau lebih tinggi**
-   **Composer 2.x**
-   Web Server (misalnya, Laragon, XAMPP)
-   Database MySQL

---

## 🚀 Panduan Instalasi Cepat

Berikut adalah langkah-langkah untuk menjalankan aplikasi ini secara lokal.

### 1. Clone Repository

Buka terminal dan clone repository ini ke direktori lokal .

```bash
git clone https://github.com/synchhans/test-sipatex-eresep.git
```

### 2. Masuk ke Direktori Proyek

Pindah ke direktori proyek yang baru saja dibuat.

```bash
cd test-sipatex-eresep
```

### 3. Install Dependency PHP

Install semua paket PHP yang dibutuhkan melalui Composer.

```bash
composer i
```

### 4. Konfigurasi Environment

Salin file `.env.example` menjadi file `.env` baru.

Buka file `.env` dan sesuaikan konfigurasi database dengan lokal komputer anda, terutama `DB_USERNAME`, dan `DB_PASSWORD`, default nya seperti ini.

**Konfigurasi `.env`:**

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=sipatex_eresep
DB_USERNAME=root
DB_PASSWORD=
```

### 5. Generate Application Key

Buat kunci enkripsi unik untuk aplikasi Laravel .

```bash
php artisan key:generate
```

### 6. Migrasi Database

Jalankan migrasi untuk membuat tabel-tabel yang diperlukan oleh aplikasi (`resep`, `resep_item`, `resep_racikan_item`).

```bash
php artisan migrate
```

> **Catatan**: Jika database `sipatex_eresep` belum ada, Artisan akan menanyakan apakah ingin membuatnya. Pilih **Yes**.

### 7. Import Data Master (Langkah Penting!)

Aplikasi ini membutuhkan dua tabel master yang datanya sudah disediakan. ** harus mengimpornya secara manual** melalui tool database (misalnya, phpMyAdmin, HeidiSQL, TablePlus).

-   Buka phpMyAdmin dan pilih database `sipatex_eresep`.
-   Klik tab **Import**.
-   Pilih dan impor file `database/sql/obatalkes_m.sql`.
-   Ulangi proses, pilih dan impor file `database/sql/signa_m.sql`.

Setelah langkah ini, akan memiliki 5 tabel di database : `obatalkes_m`, `signa_m`, `resep`, `resep_item`, dan `resep_racikan_item`.

### 8. Jalankan Server Development

Sekarang, siap menjalankan aplikasi!

```bash
php artisan serve
```

Aplikasi akan tersedia di **http://127.0.0.1:8000**. Buka URL tersebut di browser .

---

## Teknologi yang Digunakan

-   **Backend**: PHP 8.2, Laravel 12
-   **Frontend**: TailwindCSS (via CDN)
-   **JavaScript**: Vanilla JS, Choices.js (untuk select searchable)
-   **Database**: MySQL
-   **PDF Generation**: `barryvdh/laravel-dompdf`

---
