# Plan: Default SEO OG Image — Fallback ke Logo Yayasan

**Status**: Ready for Execution  
**Created**: 2026-03-16  
**Scope**: Perbaiki fallback `og:image` / `twitter:image` di layout utama (`front.blade.php`) agar menggunakan logo yayasan dari database, bukan file statis hardcoded.

---

## Latar Belakang

Saat ini, `og:image` di semua halaman front-end menggunakan logika berikut di `resources/views/layouts/front.blade.php`:

```php
// BUGGY (line 9):
$ogImage = isset($metaImage) && $metaImage ? $metaImage : asset('images/default-og.jpg');
```

**Masalah**:
- Halaman yang TIDAK meng-set `$metaImage` (26 halaman dari 32) akan memakai `default-og.jpg` (file statis)
- Yang seharusnya dipakai adalah **logo yayasan** yang tersimpan di database (`FoundationSetting::first()->logo`)
- `$foundation` sudah di-load di baris 2 layout tersebut — tinggal digunakan

### Halaman yang SUDAH set metaImage (6 halaman — tidak terpengaruh bug)
`Home`, `ProgramDetail`, `ProgramIndex`, `QurbanIndex`, `ZakatIndex`, `FoundationProfile`

### Halaman yang TERPENGARUH (26 halaman — mendapat default-og.jpg, seharusnya logo yayasan)
`ChangePassword`, `DynamicProgramCheckout`, `FoundationLegality`, `FundraiserBankManage`, `FundraiserDashboard`, `FundraiserHistory`, `FundraiserPrograms`, `FundraiserRegister`, `FundraiserWithdrawal`, `LoginRequired`, `MyDonation`, `PaymentMethod`, `PaymentStatus`, `Profile`, `ProfileEdit`, `ProgramCheckout`, `QurbanCheckout`, `QurbanHistory`, `QurbanSavingsDetail`, `QurbanTabungan`, `QurbanTabunganCheckout`, `QurbanTransactionDetail`, `Report`, `SearchPage`, `TransactionStatus`, `ZakatHistory`

---

## Decision Log

| Keputusan | Pilihan | Alasan |
|-----------|---------|--------|
| Fix location | `front.blade.php` line 9 saja | Satu tempat = fix semua 26 halaman |
| Fallback chain | logo DB → default-og.jpg | Jika DB belum diisi, tidak error |
| Storage URL | `Storage::url($logoPath)` | Logo disimpan di `storage/app/public/` |
| URL absoluteness | wrap dengan `url()` jika bukan http | Agar link bisa dibaca oleh OpenGraph scraper eksternal |

---

## Scope

**IN (1 file saja):**
- `resources/views/layouts/front.blade.php` — ganti baris 9 (fallback OG image)

**OUT:**
- Setiap Livewire component Front — tidak perlu disentuh satu per satu
- File `images/default-og.jpg` — dibiarkan sebagai last fallback
- CSS/JS — tidak disentuh

---

## Tasks

### TASK 1 — Fix fallback OG image di `front.blade.php`
**File**: `resources/views/layouts/front.blade.php`  
**Location**: Sekitar baris 8-12 (dalam `@php` block di awal file)

**Current (BUGGY):**
```php
    // OG Image: ensure always absolute URL, fallback to default-og.jpg
    $ogImage = isset($metaImage) && $metaImage ? $metaImage : asset('images/default-og.jpg');
    if (!str_starts_with($ogImage, 'http')) {
        $ogImage = url($ogImage);
    }
```

**Target (FIXED):**
```php
    // OG Image: fallback ke logo yayasan (DB), lalu ke default-og.jpg
    if (isset($metaImage) && $metaImage) {
        $ogImage = $metaImage;
    } elseif ($foundation && $foundation->logo) {
        $logoPath = $foundation->logo;
        $ogImage = str_starts_with($logoPath, 'http')
            ? $logoPath
            : url(\Illuminate\Support\Facades\Storage::url($logoPath));
    } else {
        $ogImage = asset('images/default-og.jpg');
    }
    if (!str_starts_with($ogImage, 'http')) {
        $ogImage = url($ogImage);
    }
```

**QA**: 
```bash
# Pastikan "default-og.jpg" hanya muncul sebagai fallback terakhir, bukan fallback pertama
php -r "echo file_get_contents('resources/views/layouts/front.blade.php');" | grep -c "default-og.jpg"
# ASSERT: 1 (satu kemunculan, di dalam else terakhir)

php -r "echo substr_count(file_get_contents('resources/views/layouts/front.blade.php'), '\$foundation->logo');"
# ASSERT: >= 1

php -r "echo substr_count(file_get_contents('resources/views/layouts/front.blade.php'), 'elseif');"
# ASSERT: >= 1
```

---

## Final Verification Wave

```bash
# QA-1: file berubah (bukan default-og di baris pertama fallback)
php -r "
\$c = file_get_contents('resources/views/layouts/front.blade.php');
echo (strpos(\$c, '\$foundation->logo') !== false) ? 'OK' : 'FAIL - foundation->logo not found';
"
# ASSERT: OK

# QA-2: fallback chain benar — ada elseif untuk logo
php -r "
\$c = file_get_contents('resources/views/layouts/front.blade.php');
echo (strpos(\$c, 'elseif (\$foundation && \$foundation->logo)') !== false) ? 'OK' : 'FAIL';
"
# ASSERT: OK

# QA-3: metaImage masih digunakan sebagai prioritas pertama
php -r "
\$c = file_get_contents('resources/views/layouts/front.blade.php');
echo (strpos(\$c, 'isset(\$metaImage) && \$metaImage') !== false) ? 'OK' : 'FAIL';
"
# ASSERT: OK

# QA-4: default-og.jpg masih ada sebagai last resort
php -r "
\$c = file_get_contents('resources/views/layouts/front.blade.php');
echo (strpos(\$c, 'default-og.jpg') !== false) ? 'OK' : 'FAIL';
"
# ASSERT: OK

# QA-5: syntax valid PHP (blade adalah PHP)
php -r "
\$c = file_get_contents('resources/views/layouts/front.blade.php');
echo 'Checked';
"
# ASSERT: Checked (tidak ada fatal error)
```

---

## Execution Order

1. **TASK 1** — Edit `resources/views/layouts/front.blade.php` (ganti 4 baris → 11 baris)
2. **Final Verification Wave** (QA-1 s/d QA-5)

---

## Guardrails

- JANGAN ubah baris lain di `front.blade.php` (ada variabel lain yang penting)
- JANGAN mengubah Livewire components — fix layout saja sudah cukup
- JANGAN hapus `default-og.jpg` fallback — tetap dibutuhkan jika logo yayasan kosong
- Pastikan `Storage::url()` dipakai (bukan `Storage::path()`) agar URL-nya bisa diakses publik
