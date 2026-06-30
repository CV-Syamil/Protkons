# PROTKONS

**Protocol Konsuler** — Sistem informasi manajemen layanan konsuler untuk KBRI Kuala Lumpur.

Versi: **0.5.1**

---

## Tentang Aplikasi

PROTKONS adalah aplikasi web berbasis [CodeIgniter 3](https://codeigniter.com) yang mengelola seluruh siklus layanan konsuler: pencatatan identitas pelapor, pengajuan layanan, verifikasi, penandatanganan dokumen, pembayaran, arsip, hingga pelaporan. Aplikasi ini mendukung multi-peran pengguna, pembuatan dokumen otomatis dari template Word, tanda tangan elektronik (TTE), verifikasi dokumen via QR code, serta integrasi dengan layanan konsuler online.

---

## Fitur Utama

### Manajemen Pelayanan Konsuler
- Alur kerja layanan multi-tahap: Draft → Pengajuan → Terverifikasi → Pengambilan Dokumen → Dokumen Diambil → TerArsip
- Penolakan verifikasi dan penolakan penandatanganan (status khusus)
- Soft delete dengan fitur recycle bin
- Filter berdasarkan status, tanggal, jenis layanan, pelapor, dan field kustom
- Pembuatan dokumen otomatis dari template Word (`.docx`) ke PDF
- Cetak kwitansi dan bukti penerimaan
- Integrasi TTE (Tanda Tangan Elektronik) untuk penandatanganan PDF
- Halaman informasi publik dengan QR code untuk verifikasi dokumen

### Multi-Peran Pengguna
| Peran | Keterangan |
|-------|------------|
| **Petugas Loket** | Menerima dan memproses pengajuan layanan |
| **Petugas Verifikasi** | Memverifikasi kelengkapan dan kebenaran data |
| **Pejabat Penanda Tangan (HS)** | Menandatangani dokumen resmi |
| **Petugas Kasir** | Mengelola pembayaran layanan berbayar |
| **Administrator** | Mengelola master data dan konfigurasi |
| **Super User** | Akses penuh termasuk manajemen fungsi |

### Master Data
- Jenis layanan konsuler beserta field dinamis per layanan
- Data identitas pelapor (Indonesia & Malaysia)
- Wilayah administratif Indonesia (provinsi, kota, kecamatan, desa)
- Wilayah administratif Malaysia (negeri, daerah, distrik)
- Jenis identitas, fungsi/unit kerja, dan pengguna

### Integrasi & Sinkronisasi
- **Pelayanan Online** — menerima pengajuan dari portal publik (`KonsulerPublic`)
- **Main Server** — sinkronisasi data identitas dengan server pusat
- **TTE API** — penandatanganan dan verifikasi dokumen elektronik

### Laporan
- Laporan pelayanan (rekapitulasi per jenis layanan)
- Laporan per-pelayanan (detail transaksi)
- Laporan pelapor
- Ekspor/cetak laporan

### Fitur Pendukung
- Dashboard statistik dengan grafik
- Notifikasi real-time
- Chat antar pengguna
- Import data pelayanan (JSON/Excel)
- Input manual jumlah pelayanan
- Dark mode

---

## Teknologi

| Komponen | Teknologi |
|----------|-----------|
| Backend | PHP, CodeIgniter 3 |
| Database | MySQL / MariaDB (utf8mb4) |
| Frontend | AdminLTE 3, Bootstrap 4, jQuery |
| Tabel data | DataTables |
| Dokumen | PHPWord, mPDF, Gears PDF |
| QR Code | phpqrcode |
| Grafik | ApexCharts, CanvasJS |

---

## Persyaratan Sistem

- PHP **7.4+** (minimum 5.3.7)
- MySQL **5.7+** atau MariaDB **10.3+**
- Apache dengan **mod_rewrite** aktif
- Ekstensi PHP: `mysqli`, `curl`, `gd`, `mbstring`, `zip`, `xml`
- [Composer](https://getcomposer.org/)

---

## Instalasi

### 1. Clone repositori

```bash
git clone <url-repositori> protkons
cd protkons
```

### 2. Install dependensi PHP

```bash
composer install
```

### 3. Import database

Buat database MySQL, lalu import skema dan data awal:

```bash
mysql -u root -p -e "CREATE DATABASE konsuler2 CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;"
mysql -u root -p konsuler2 < db/konsuler2.sql
```

### 4. Konfigurasi database

Edit `application/config/database.php`:

```php
$db['default'] = array(
    'hostname' => 'localhost',
    'username' => 'root',
    'password' => 'password_anda',
    'database' => 'konsuler2',
    'dbdriver' => 'mysqli',
    'char_set' => 'utf8mb4',
    'dbcollat' => 'utf8mb4_general_ci',
    // ...
);
```

### 5. Konfigurasi aplikasi

Edit `application/config/constants.php` sesuai lingkungan deployment:

```php
define('APP_NAME', 'PROTKONS');
define('APP_DESC', 'PROTOCOL KONSULER');
define('APP_VERSION', '0.5.1');

// URL API server pusat (sinkronisasi identitas)
define('MAIN_SERVER', 'http://<host-server-pusat>/api/');
define('TOKEN_SERVER', '<token-terenkripsi>');
```

Edit `application/config/config.php` jika perlu menyesuaikan `base_url_qrcode` untuk URL verifikasi QR code publik.

### 6. Izin direktori

Pastikan direktori berikut dapat ditulis oleh web server:

```bash
chmod -R 775 application/cache application/logs assets/uploads
```

### 7. Konfigurasi web server

Pastikan `mod_rewrite` aktif. File `.htaccess` sudah disertakan di root proyek:

```apache
RewriteEngine On
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule ^(.*)$ index.php/$1 [L]
```

Arahkan document root Apache/Nginx ke folder root proyek (tempat `index.php` berada).

### 8. Akses aplikasi

Buka browser dan navigasi ke URL instalasi. Halaman login akan muncul sebagai halaman utama.

---

## Struktur Direktori

```
protkons/
├── application/
│   ├── config/          # Konfigurasi aplikasi, database, routes
│   ├── controllers/     # Controller (Auth, Pelayanan, Laporan, dll.)
│   ├── core/            # BASE_Controller (autentikasi & layout)
│   ├── helpers/         # Helper functions
│   ├── libraries/       # TTE, KonsulerPublic, Datatables, QR Code
│   ├── models/          # Model database
│   └── views/           # Template tampilan
├── assets/              # Upload file, gambar, dokumen
├── db/                  # Dump database (konsuler2.sql)
├── style/               # AdminLTE, plugin frontend
├── system/              # Core CodeIgniter
├── vendor/              # Dependensi Composer
├── index.php            # Entry point
└── .htaccess            # URL rewriting
```

---

## Alur Kerja Pelayanan

```
┌─────────┐    ┌───────────┐    ┌──────────────┐    ┌─────────────────────┐
│  Draft  │───▶│ Pengajuan │───▶│ Terverifikasi│───▶│ Pengambilan Dokumen │
│  (0)    │    │    (1)    │    │     (2)      │    │        (3)          │
└─────────┘    └───────────┘    └──────────────┘    └─────────────────────┘
                     │                  │                        │
                     ▼                  ▼                        ▼
              Tolak Verifikasi   Tolak Penandatangan     Dokumen Diambil (4)
                  (91)               (92)                       │
                                                                ▼
                                                          TerArsip (5)
```

| Status | Kode | Keterangan |
|--------|------|------------|
| Draft | 0 | Pengajuan belum disubmit |
| Pengajuan | 1 | Menunggu verifikasi |
| Terverifikasi | 2 | Sudah diverifikasi, menunggu tanda tangan |
| Pengambilan Dokumen | 3 | Siap diambil / menunggu pembayaran |
| Dokumen Diambil | 4 | Dokumen sudah diserahkan ke pelapor |
| TerArsip | 5 | Proses selesai dan terarsip |
| Tolak Verifikasi | 91 | Ditolak pada tahap verifikasi |
| Tolak Penandatangan | 92 | Ditolak pada tahap penandatangan |

---

## API Endpoints (Internal)

Aplikasi menyediakan endpoint AJAX untuk kebutuhan frontend:

| Endpoint | Keterangan |
|----------|------------|
| `api/jenis_identitas_str` | Daftar jenis identitas |
| `api/petugas/{tipe}` | Pencarian petugas berdasarkan peran |
| `api/negeri_provinsi/{ref}` | Data wilayah (indonesia/malaysia) |
| `informasi/pelayanan/{id}` | Halaman verifikasi publik (QR code) |
| `pelayanan/file/{id}/{tipe}` | Unduh/baca dokumen layanan |

---

## Integrasi Eksternal

### TTE (Tanda Tangan Elektronik)
Konfigurasi di `application/libraries/TTE.php`. Setiap pengguna HS memiliki kredensial TTE (`tte_nik`, `tte_pwd`) yang disimpan di profil pengguna.

### Portal Pelayanan Online
Konfigurasi di `application/libraries/KonsulerPublic.php`. Petugas verifikasi dapat menerima dan memproses pengajuan dari portal publik konsuler online.

### Main Server
Sinkronisasi data identitas dengan server pusat KBRI. Konfigurasi URL dan token di `application/config/constants.php`.

---

## Keamanan

- Autentikasi berbasis session dengan `password_hash` / `password_verify`
- Kontrol akses per-peran di `BASE_Controller` dan helper `can_access()`
- Akses pelayanan dibatasi per pengguna via field `akses_pelayanan`
- Soft delete mencegah kehilangan data permanen
- Log scan QR code untuk audit verifikasi dokumen

> **Catatan:** Jangan commit kredensial database, token API, atau password TTE ke repositori publik. Gunakan konfigurasi environment terpisah untuk production.

---

## Lisensi

Aplikasi ini dibangun di atas framework CodeIgniter yang dilisensikan under [MIT License](https://codeigniter.com/user_guide/license.html).

---

## Hak Cipta

Copyright © KBRI Kuala Lumpur. All rights reserved.
