# Plan: Menghilangkan Gradient di Dashboard Fundraiser (Ganti ke Warna Solid)

**Dibuat**: 2026-03-07  
**Project**: Fundrize (Laravel 12 + Livewire 4)  
**Tujuan**: Mengubah semua tampilan gradasi (`bg-gradient-to-*`) di area Dashboard Fundraiser menjadi warna solid (menggunakan warna `primary` yang sudah ada di setting yayasan) agar tampilan lebih konsisten dan profesional.

---

## Lingkup Perbaikan (Scope)

**File View yang akan Diubah:**
1. `resources/views/livewire/front/fundraiser-dashboard.blade.php`
2. `resources/views/livewire/front/fundraiser-withdrawal.blade.php`
3. `resources/views/livewire/front/fundraiser-history.blade.php`

---

## Detail Perubahan

### 1. `resources/views/livewire/front/fundraiser-dashboard.blade.php`

| Lokasi | Sebelum (Gradient) | Sesudah (Solid) |
|---|---|---|
| Card Header (Saldo) | `bg-gradient-to-br from-primary to-secondary` | `bg-primary` |
| Card Menu (Donasi) | `bg-gradient-to-br from-white to-orange-50` | `bg-white border-orange-100` |
| Card Menu (Ujroh) | `bg-gradient-to-br from-white to-orange-50` | `bg-white border-orange-100` |
| Card Menu (Tautan) | `bg-gradient-to-br from-white to-orange-50` | `bg-white border-orange-100` |

### 2. `resources/views/livewire/front/fundraiser-withdrawal.blade.php`

| Lokasi | Sebelum (Gradient) | Sesudah (Solid) |
|---|---|---|
| Header Card (Ujroh) | `bg-gradient-to-br from-primary to-secondary` | `bg-primary` |

### 3. `resources/views/livewire/front/fundraiser-history.blade.php`

| Lokasi | Sebelum (Gradient) | Sesudah (Solid) |
|---|---|---|
| Summary Card (Saldo) | `bg-gradient-to-br from-primary to-secondary` | `bg-primary` |

---

## Langkah-Langkah Eksekusi (Implementation Tasks)

### TASK 1: Update `fundraiser-dashboard.blade.php`
Ganti `bg-gradient-to-br from-primary to-secondary` menjadi `bg-primary`.  
Ganti `bg-gradient-to-br from-white to-orange-50` menjadi `bg-white border border-orange-100`.

### TASK 2: Update `fundraiser-withdrawal.blade.php`
Ganti `bg-gradient-to-br from-primary to-secondary` menjadi `bg-primary`.

### TASK 3: Update `fundraiser-history.blade.php`
Ganti `bg-gradient-to-br from-primary to-secondary` menjadi `bg-primary`.

---

## Verifikasi Akhir

Setelah semua file diubah, jalankan:
1. `php artisan view:clear` untuk memastikan view terbaru yang di-load.
2. Cek visual di halaman `/fundraiser/dashboard` dan halaman terkait lainnya.
3. Pastikan warna `bg-primary` mengikuti warna yang diset di Admin (Setting Yayasan).

---

## Konfirmasi

Apakah Anda ingin saya segera mengeksekusi perbaikan ini satu per satu menggunakan agent implementer?
