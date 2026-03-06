# Plan: Fix OG Image "Jenis Konten Tidak Valid" Error

**Created**: 2026-03-07  
**Project**: Fundrize (Laravel 12 + Livewire 4)  
**Problem**: Facebook Sharing Debugger error: "Jenis Konten Gambar Tidak Valid" — URL gambar `https://fundrize.kafilahdigital.com/storage/programs/XXX.jpg` tidak dapat diproses.

---

## Root Cause Analysis

**Penyebab #1 (Primer — Paling Mungkin)**: Binary file mismatch.  
User sering upload file PNG tapi diberi ekstensi `.jpg`. Laravel validation `image` hanya cek bahwa itu file gambar, tapi TIDAK memaksa format konsisten. File tersimpan sebagai PNG binary dengan path `.jpg`. Server mengembalikan `Content-Type: image/jpeg` (dari ekstensi), tapi Facebook bot membaca binary signature (`\x89PNG`) dan mendeteksi ketidakcocokan → reject.

**Penyebab #2 (Primer — Tied)**: Dimensi hardcoded `1200x630` di HTML tidak cocok dengan dimensi gambar asli yang diupload. Facebook memvalidasi apakah dimensi yang diklaim di `og:image:width/height` sesuai dengan gambar aktual. Ketidakcocokan → error.

**Penyebab #3 (Sekunder)**: Tidak ada `og:image:type` meta tag → Facebook tidak punya hint MIME type eksplisit.

---

## Solusi

**Solusi utama**: Saat gambar diupload di admin, proses gambar dengan **Intervention Image v3** untuk:
- Resize & crop ke **1200×630** (dimensi OG standar)
- Konversi ke **JPEG** (memaksa binary signature yang benar, apapun format aslinya)
- Simpan dengan kualitas **85** (output ~150–400KB, jauh di bawah limit 8MB Facebook)

Satu langkah ini menyelesaikan KEDUA root cause sekaligus.

---

## Scope

**IN:**
- Install `intervention/image` v3
- `app/Livewire/Admin/Program.php` — ganti raw upload dengan image processing
- `resources/views/layouts/front.blade.php` — tambah `og:image:type`
- `app/Livewire/Admin/FoundationSetting.php` — sama, untuk logo yayasan (opsional, high impact)

**OUT:**
- Tidak mengubah model, migration, atau routes
- Tidak mengubah gambar yang sudah ada di storage (hanya gambar baru)
- Tidak mengubah validasi di frontend Livewire (hanya admin)
- Tidak mengubah QurbanIndex, ProgramIndex (sudah pakai foundation logo)

---

## Pre-Conditions

### PRE-1 — Cek apakah Intervention Image sudah terinstall
```bash
php artisan tinker --execute="echo class_exists('Intervention\Image\ImageManager') ? 'INSTALLED' : 'NOT INSTALLED';" 2>&1
```
**Jika NOT INSTALLED**: Jalankan `composer require intervention/image`

### PRE-2 — Cek apakah GD extension aktif
```bash
php -m | grep -i gd
```
**Assert**: Output menampilkan `gd`. Jika tidak ada, aktifkan di `php.ini`.

---

## Implementation Tasks

### TASK 1 — Install Intervention Image v3

```bash
composer require intervention/image
```

**Verifikasi:**
```bash
php artisan tinker --execute="echo class_exists('Intervention\Image\ImageManager') ? 'OK' : 'FAIL';" 2>&1
```
Assert: `OK`

---

### TASK 2 — Ganti Upload Logic di `app/Livewire/Admin/Program.php`

**Lokasi**: Method `store()`, baris 137–139 (kode saat ini):
```php
if ($this->image) {
    $imageName = $this->image->store('programs', 'public');
    $data['image'] = $imageName;
}
```

**Ganti dengan:**
```php
if ($this->image) {
    use Intervention\Image\ImageManager;
    use Intervention\Image\Drivers\Gd\Driver;

    $manager = new ImageManager(new Driver());
    $image = $manager->read($this->image->getRealPath());

    // Resize + crop ke 1200x630 (standar OG image), konversi ke JPEG
    $processed = $image->cover(1200, 630)->toJpeg(85);

    // Simpan ke storage/app/public/programs/
    $filename = 'programs/' . uniqid() . '.jpg';
    \Illuminate\Support\Facades\Storage::disk('public')->put($filename, $processed);

    // Hapus gambar lama jika ada (update)
    if ($this->programId) {
        $oldProgram = \App\Models\Program::find($this->programId);
        if ($oldProgram) {
            $oldPath = $oldProgram->getRawOriginal('image');
            if ($oldPath && !str_starts_with($oldPath, 'http')) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($oldPath);
            }
        }
    }

    $data['image'] = $filename;
}
```

**Tambahkan import di atas class** (jika belum ada):
```php
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
```

**Validasi rule** — perketat dari `nullable|image|max:2048` menjadi:
```php
'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
```

**QA untuk Task 2:**
- Upload gambar PNG via admin → verifikasi file yang tersimpan di `/storage/programs/` adalah file `.jpg` valid
- Upload gambar landscape 1920×1080 → verifikasi output di-crop ke 1200×630
- Upload gambar portrait → verifikasi output di-crop ke 1200×630 (tidak stretch)
- Cek `curl -sI https://domain/storage/programs/newfile.jpg | grep Content-Type` → Assert: `image/jpeg`

---

### TASK 3 — Tambah `og:image:type` di `resources/views/layouts/front.blade.php`

**Lokasi**: Setelah baris `<meta property="og:image:height" content="630">`, tambahkan:
```blade
<meta property="og:image:type" content="image/jpeg">
```

Ini memberi Facebook petunjuk eksplisit bahwa semua OG image adalah JPEG, konsisten dengan hasil pemrosesan di TASK 2.

**QA untuk Task 3:**
```bash
curl -s http://localhost:8000/ | grep 'og:image:type'
# Assert: content="image/jpeg" muncul
```

---

### TASK 4 — (Opsional tapi Direkomendasikan) Sama untuk Foundation Logo di `app/Livewire/Admin/FoundationSetting.php`

Cari method `save()` atau `update()` yang menyimpan field `logo`. Terapkan pattern yang sama: resize ke dimensi yang wajar (misal 800×800 atau 1200×630), konversi ke JPEG.

---

## Final Verification

Setelah semua task selesai:

```bash
# 1. Pastikan file gambar baru adalah JPEG valid
php artisan tinker --execute="
\$p = App\Models\Program::latest()->first();
\$path = storage_path('app/public/' . \$p->getRawOriginal('image'));
echo mime_content_type(\$path);
" 2>&1
# Assert: image/jpeg

# 2. Pastikan dimensi 1200x630
php artisan tinker --execute="
\$p = App\Models\Program::latest()->first();
\$path = storage_path('app/public/' . \$p->getRawOriginal('image'));
\$size = getimagesize(\$path);
echo \$size[0] . 'x' . \$size[1];
" 2>&1
# Assert: 1200x630

# 3. OG tags di halaman
curl -s http://localhost:8000/program/{slug} | grep -E 'og:image'
# Assert: URL absolute, og:image:type=image/jpeg

# 4. Commit
git add -A && git commit -m "fix: proses gambar program ke JPEG 1200x630 saat upload untuk OG image valid" && git push
```

---

## Post-Deploy

Setelah deploy ke production:
1. Upload ulang 1-2 gambar program via admin untuk menghasilkan file baru yang sudah diproses
2. Buka [Facebook Sharing Debugger](https://developers.facebook.com/tools/debug/) → masukkan URL program → klik **Scrape Again**
3. Assert: Tidak ada error "Jenis Konten Tidak Valid"

**Catatan**: Gambar lama yang sudah tersimpan di storage TIDAK akan diproses ulang secara otomatis. Hanya gambar yang diupload ulang yang akan diproses dengan format baru.
