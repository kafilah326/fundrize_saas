# Fundrize SaaS Platform

**Fundrize** adalah platform SaaS (Software as a Service) multi-tenant yang dirancang untuk yayasan dan organisasi nonprofit dalam mengelola penggalangan dana (donasi) dan layanan Qurban (pembelian & tabungan). Dibangun dengan teknologi **Laravel 12** dan **Livewire 4**.

---

## 🚀 Fitur Utama

- **Multi-Tenancy**: Isolasi data antar yayasan dengan pengatuan subdomain unik (.fundrize.test).
- **Core Fundraising**: Manajemen program donasi, kategori, dan laporan donasi.
- **Qurban Module**: Sistem pembelian qurban dan program tabungan qurban bagi muzzaki.
- **Add-on System**: Sistem fleksibel untuk menambah fitur atau limit (programs, users, storage) secara mandiri bagi tenant.
- **Billing & Subscription**: Pembayaran otomatis untuk aktivasi tenant dan add-on.
- **WhatsApp Integration**: Notifikasi otomatis via StarSender.
- **Payment Gateway**: Dukungan Duitku untuk pembayaran otomatis.
- **Web Push Notification**: Notifikasi real-time ke browser pengelola.

---

## 💻 1. Persyaratan Sistem

- **PHP** >= 8.2
- **Composer** (versi terbaru)
- **Node.js** & **NPM**
- **Database**: MySQL/MariaDB
- **Local Domain Support**: Diperlukan pengaturan file `hosts` untuk simulasi subdomain.

---

## 🛠️ 2. Panduan Instalasi Lokal

### A. Clone & Setup
1. Clone repository: `git clone <url-repo>` dan masuk ke folder proyek.
2. Instal dependensi: `composer install` & `npm install`.
3. Buat file environment: `copy .env.example .env`.
4. Generate key: `php artisan key:generate`.
5. Buat tautan storage: `php artisan storage:link`.

### B. Konfigurasi Domain
Karena menggunakan sistem multi-tenant, Anda perlu mendaftarkan domain utama dan superadmin di file `hosts` komputer Anda:
1. Buka Notepad sebagai Administrator.
2. Edit file: `C:\Windows\System32\drivers\etc\hosts` (Windows).
3. Tambahkan baris berikut:
   ```text
   127.0.0.1 fundrize.test
   127.0.0.1 superadmin.fundrize.test
   127.0.0.1 demo.fundrize.test
   ```

### C. Konfigurasi Database
1. Buat database kosong (misal: `fn_saas`).
2. Update `.env` dengan kredensial database Anda.
3. Jalankan migrasi dan seeder awal:
   ```bash
   php artisan migrate --seed
   ```
   *Seeder akan membuat akun SuperAdmin default dan beberapa data paket/plan.*

### D. Generate Web Push Keys
```bash
php artisan webpush:generate-keys
```

---

## 🚀 3. Menjalankan Aplikasi

Jalankan perintah berikut di terminal terpisah:

**1. Web Server**
```bash
php artisan serve --host=fundrize.test --port=80
```
*Akses SuperAdmin di `superadmin.fundrize.test` dan Landing Page di `fundrize.test`.*

**2. Queue Worker** (Untuk notifikasi & callback)
```bash
php artisan queue:work
```

**3. Vite Dev Server**
```bash
npm run dev
```

---

## ⏰ 4. Scheduler & Maintenance
Daftarkan scheduler di server Anda (Cron Job) atau jalankan secara manual untuk testing:
- **Cleanup Add-on**: `php artisan addons:cleanup` (Menonaktifkan add-on bulanan yang habis masa berlakunya).
- **Simulasi Task**: `php artisan schedule:run`.

---

## 🔗 5. Integrasi Pihak Ketiga
- **Duitku**: Masukkan Merchant Code dan API Key di `.env`.
- **StarSender**: Masukkan API Key di `.env`.
- **Xendit**: Pengaturan ini disimpan di tabel `app_settings` dan dapat diubah melalui **Web Settings** di Admin Panel.

---
**Selesai! Platform Fundrize SaaS Anda siap digunakan.**
