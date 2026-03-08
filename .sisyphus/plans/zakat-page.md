# Plan: Implementasi Halaman Zakat dengan Kalkulator Otomatis

**Dibuat**: 2026-03-08  
**Project**: Fundrize (Laravel 12 + Livewire 4)  
**Tujuan**: Menambahkan halaman khusus Zakat (`/zakat`) yang memiliki kalkulator Zakat Fitrah dan Zakat Mal, serta mengarahkan klik akad "Zakat" dari halaman program ke halaman baru ini.

---

## Analisis Teknis

1.  **Data Model**: Menggunakan model `Program` yang sudah ada. Program khusus zakat akan difilter berdasarkan `AkadType` dengan slug `zakat`.
2.  **Routing**: Menambahkan route baru `/zakat` yang bersifat publik.
3.  **UI/UX**: 
    - Menggunakan layout `layouts.front` agar konsisten dengan tema warna yayasan (primary color).
    - Kalkulator menggunakan **Alpine.js** untuk kecepatan (tanpa reload server saat input angka).
    - Integrasi dengan sistem checkout yang sudah ada untuk pembayaran.

---

## Lingkup Pekerjaan (Scope)

**IN:**
- Route baru `/zakat`.
- Livewire Component & View `ZakatIndex`.
- Kalkulator Zakat Fitrah (nominal per jiwa).
- Kalkulator Zakat Mal (input harta, nisab check, hitung 2.5%).
- Modifikasi navigasi akad pada halaman Daftar Program.

**OUT:**
- Tidak membuat tabel database baru.
- Tidak mengubah alur payment gateway (hanya meneruskan nominal).

---

## Langkah-Langkah Eksekusi

### TASK 1 — Registrasi Route
Tambahkan route di `routes/web.php`:
```php
Route::get('/zakat', \App\Livewire\Front\ZakatIndex::class)->name('zakat.index');
```

### TASK 2 — Membuat Component `ZakatIndex.php`
- Ambil program aktif dengan akad `zakat`.
- Jika ada lebih dari satu, ambil yang paling terbaru/urgent sebagai target default.
- Method `checkout()`: validasi nominal, simpan ke session, lalu redirect ke halaman checkout yang sudah ada.

### TASK 3 — Membuat View `zakat-index.blade.php`
- Gunakan `<x-page-header title="Tunaikan Zakat">`.
- Porting HTML mockup ke dalam Blade:
    - Ganti hardcoded color `#FF6B35` dengan class Tailwind `bg-primary`, `text-primary`, dsb.
    - Implementasi tab switching (Fitrah vs Mal) menggunakan Alpine.js `x-data`.
    - Implementasi logic kalkulator di dalam Alpine object.
- Tambahkan section "Program Zakat Terkait" (mengambil data program dari component).

### TASK 4 — Update Navigasi di `program-index.blade.php`
Cari loop akad chip, tambahkan kondisi:
- Jika slug adalah `zakat`, ubah `wire:click` menjadi redirect ke `route('zakat.index')`.

### TASK 5 — Integrasi Kalkulator ke Checkout
- Pastikan tombol "Bayar Zakat" mengirimkan data yang benar (nominal dan program_id) ke method `checkout()` di component.
- Gunakan pola yang sama dengan `QurbanIndex` untuk inisialisasi session pembayaran.

---

## Verifikasi & QA

1.  **Navigasi**: Buka `/program`, klik chip "Zakat" -> Harus pindah ke `/zakat`.
2.  **Kalkulator Fitrah**: Masukkan 3 jiwa -> Nominal otomatis Rp 135.000.
3.  **Kalkulator Mal**: Masukkan tabungan Rp 100jt -> Status jadi "Wajib Zakat", nominal Rp 2.500.000.
4.  **Checkout**: Klik "Bayar Zakat" -> Harus masuk ke halaman input data diri dengan nominal yang benar.
5.  **Visual**: Pastikan warna tombol dan header sesuai dengan Setting Yayasan (Primary Color).

---

## Keputusan yang Diambil (Decisions)

- **Nisab**: Menggunakan angka statis Rp 85.000.000 (setara 85gr emas) sebagai default di Alpine.js.
- **Target Program**: Mengambil program pertama yang ditemukan dengan akad `zakat` agar dana masuk ke kategori yang tepat di laporan.
- **Tech Stack**: Full Alpine.js untuk kalkulator agar tidak ada delay/loading saat mengetik angka.
