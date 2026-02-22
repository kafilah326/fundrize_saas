# Fundrising App & Layanan Qurban

**Fundrising App** adalah platform penggalangan dana (donasi reguler) dan layanan pembelian serta tabungan Qurban. Aplikasi ini dibangun dengan teknologi **Laravel 12** dan **Livewire 4**. 

Aplikasi ini sudah terintegrasi dengan berbagai layanan pihak ketiga, antara lain:
- **Xendit**: Untuk *payment gateway* / pembayaran otomatis.
- **StarSender**: Untuk notifikasi WhatsApp otomatis.
- **Web Push (VAPID)**: Untuk notifikasi berbasis *push notification* ke peramban (browser).

Ikuti panduan ini langkah demi langkah agar aplikasi dapat berjalan sempurna di *local development* maupun server produksi Anda.

---

## 💻 1. Persyaratan Sistem (System Requirements)

Pastikan lingkungan server/komputer lokal Anda memiliki spesifikasi minimum berikut:
- **PHP** >= 8.2
- **Composer** (versi terbaru)
- **Node.js** & **NPM** (untuk kompilasi *asset* via Vite)
- **Database**: MySQL, MariaDB, atau PostgreSQL (Disarankan MySQL/MariaDB)

---

## 📥 2. Instalasi Proyek (Source Code)

1. Buka terminal (CMD/Powershell/Terminal).
2. Lakukan *clone repository* Git aplikasi ini:
   ```bash
   git clone <url-repository-anda>
   cd fundrisingApp
   ```
*(Catatan: ganti `<url-repository-anda>` dengan tautan repository Github/Gitlab proyek ini).*

---

## 📦 3. Instalasi Dependensi PHP

Aplikasi ini menggunakan banyak library PHP (termasuk Livewire dan Xendit PHP Client). Unduh semua dependencies dengan perintah:

```bash
composer install
```

---

## ⚙️ 4. Pengaturan Lingkungan (Environment Setup)

1. Salin file *template environment* menjadi `.env`.
   - Untuk Mac/Linux: `cp .env.example .env`
   - Untuk Windows: `copy .env.example .env`

2. Buka file `.env` di teks editor (misal: VS Code) dan sesuaikan konfigurasi berikut:

   **A. Konfigurasi Database**
   Buat database kosong terlebih dahulu di MySQL (misal: `fundrising_db`). Lalu sesuaikan baris ini:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=fundrising_db
   DB_USERNAME=root
   DB_PASSWORD=secret_password_anda
   ```

   **B. Konfigurasi Notifikasi (Opsional tapi Direkomendasikan)**
   Aplikasi menggunakan StarSender untuk WhatsApp. Masukkan API Key StarSender Anda, dan atur `SYSTEM_FEE_PERCENTAGE` jika ada potongan sistem:
   ```env
   STARSENDER_API_KEY=api_key_starsender_anda_disini
   SYSTEM_FEE_PERCENTAGE=2 
   ```

   **C. Konfigurasi Queue (Sangat Penting)**
   Karena aplikasi ini mengirimkan notifikasi WA dan email (secara otomatis lewat *background job*), pastikan koneksi *queue* diatur ke database:
   ```env
   QUEUE_CONNECTION=database
   ```

3. Generate Laravel Application Key:
   ```bash
   php artisan key:generate
   ```

---

## 🗄️ 5. Migrasi Database dan Data Awal

Jalankan perintah ini untuk membangun seluruh tabel database:

```bash
php artisan migrate
```
*(Catatan: Jika Anda memiliki `DatabaseSeeder` khusus untuk membuat akun admin default atau data kategori awal, jalankan `php artisan migrate --seed`)*.

---

## 🔑 6. Pengaturan Layanan Pihak Ketiga Tambahan

Beberapa konfigurasi pengaturan platform disimpan di dalam database (tabel `app_settings`), bukan di `.env`. 

**A. Generate VAPID Keys (Web Push Notification)**
Jalankan perintah khusus buatan sistem ini untuk membuat kunci notifikasi web (VAPID Keys) yang akan otomatis disave ke database setting:
```bash
php artisan vapid:generate
```

**B. Pengaturan Xendit & WhatsApp Lainnya**
Pengaturan *Secret Key* Xendit dan detail StarSender lainnya akan diatur melalui menu **Admin Panel -> Settings** atau **Admin Panel -> Meta Setting** dengan Web GUI sesudah aplikasi dijalankan, karena sistem menyimpanya di `AppSetting` model.

---

## 🎨 7. Instalasi Aset Frontend (NPM & Vite)

Untuk *compile* file CSS dan JS (berbasis Livewire dan Vite):

```bash
npm install
npm run build
```
*(Jika sedang berfokus pada pengembangan tampilan/Livewire, gunakan `npm run dev` pada terminal baru agar auto-reload berfungsi).*

---

## 📁 8. Publikasi Storage (Storage Link)

Izinkan akses publik untuk direktori unggahan file (foto user, gambar program donasi, bukti tabungan qurban) dengan perintah:

```bash
php artisan storage:link
```

---

## 🚀 9. Menjalankan Aplikasi

Aplikasi utama menggunakan setidaknya **dua terminal** (proses) agar bisa berjalan secara optimal di lokal, terutama karena adanya *webhook* dan notifikasi antrian.

**Terminal 1: Menjalankan Server Web Utama**
```bash
php artisan serve
```
Aplikasi kini dapat diakses melalui browser: **http://127.0.0.1:8000**

**Terminal 2: Menjalankan Queue Worker (Background Jobs / Worker)**
Untuk memastikan WhatsApp (StarSender), Webhooks Xendit, dan sinkronisasi donasi langsung diproses di belakang layar, biarkan terminal ini tetap berjalan:
```bash
php artisan queue:work
```

---

## 🌐 10. Pengaturan Webhook Xendit Lokal (Opsional untuk Testing Development)

Xendit Payment Gateway membutuhkan rute aplikasi *live* atau *public internet* untuk mengirimkan Webhook ketika ada donasi dibayar. 

Jika berjalan di `localhost`, install [Ngrok](https://ngrok.com/) atau layanan semacamnya:
1. Jalankan ngrok di terminal baru: `ngrok http 8000`
2. Copy *Forwarding URL* HTTPS milik Ngrok, masukkan ke Dashboard Xendit Anda beserta *Endpoint*:
   **`<url-ngrok>/webhooks/xendit/invoice`**

*(Endpoint ini ditangani secara khusus oleh `XenditWebhookController`)*.

---
**Selesai! Aplikasi Fundrising & Qurban Anda telah berhasil diinstal dan siap digunakan secara penuh.**
