# Plan: Perbaikan Notifikasi WhatsApp pada Konfirmasi Pembayaran Manual

**Dibuat**: 2026-03-07  
**Project**: Fundrize (Laravel 12 + Livewire 4)  
**Masalah**: Saat Admin melakukan konfirmasi pembayaran manual (Bank Transfer) dari dashboard Admin, donatur tidak menerima pesan WhatsApp notifikasi berhasil, padahal jika menggunakan Payment Gateway pesan tersebut terkirim otomatis.

---

## Analisis Masalah

- **Sistem Otomatis (Payment Gateway)**: Memanggil `notifyPaymentSuccess()` saat menerima callback lunas.
- **Sistem Manual (Admin)**: Hanya mengupdate status di database tanpa memanggil `WhatsAppNotificationService`.
- **Service yang Digunakan**: `App\Services\WhatsAppNotificationService`.
- **Method yang Digunakan**: `notifyPaymentSuccess(Payment $payment)`.

---

## Lingkup Perbaikan (Scope)

**File yang akan Diubah:**
1. `app/Livewire/Admin/DonationList.php` (Konfirmasi Donasi Program)
2. `app/Livewire/Admin/Qurban.php` (Konfirmasi Qurban Langsung & Tabungan Qurban)

---

## Detail Perubahan

### 1. `app/Livewire/Admin/DonationList.php`
Di dalam method `confirmPayment($id)`:
- Panggil `WhatsAppNotificationService` setelah status berhasil diupdate ke `paid`.

### 2. `app/Livewire/Admin/Qurban.php`
Di dalam method `confirmOrderPayment($orderId)` dan `confirmDepositPayment($depositId)`:
- Panggil `WhatsAppNotificationService` setelah status berhasil diupdate.

---

## Langkah-Langkah Eksekusi (Implementation Tasks)

### TASK 1: Update `DonationList.php`
Sisipkan kode berikut di akhir method `confirmPayment`:
```php
$waService = app(\App\Services\WhatsAppNotificationService::class);
$waService->notifyPaymentSuccess($payment);
```

### TASK 2: Update `Qurban.php`
Sisipkan kode yang sama di akhir method `confirmOrderPayment` dan `confirmDepositPayment` setelah proses update database selesai.

---

## Verifikasi Akhir

1. Melakukan simulasi donasi dengan metode Transfer Bank.
2. Login ke Admin, lalu klik **Konfirmasi** pada donasi tersebut.
3. Pastikan log sistem (`storage/logs/laravel.log`) menunjukkan "WA Notify Sent" atau cek HP donatur apakah menerima pesan.

---

**Apakah Anda ingin saya segera mengeksekusi perbaikan ini?**
