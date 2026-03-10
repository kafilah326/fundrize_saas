# Plan: Zakat Banner Feature

**Status**: Ready for Execution  
**Date**: 2026-03-10  
**Scope**: Add banner image upload (admin) + banner display (frontend) + og:image support for /zakat page

---

## Objective

1. **Admin** `/admin/zakat?activeTab=settings` — tambahkan form upload banner (1 gambar) di tab Settings
2. **Frontend** `/zakat` — tampilkan banner jika ada, posisi di atas tab section
3. **SEO** — banner otomatis digunakan sebagai `og:image` saat halaman di-share di sosmed

---

## Decisions Made

| Decision | Choice | Rationale |
|----------|--------|-----------|
| Storage location | `AppSetting` key `zakat_banner_image` | Consistent with existing pattern (zakat_fitrah_price, dll). Tidak perlu model baru. |
| Subdirectory | `storage/public/zakat/` | Namespace yang jelas, diawali `makeDirectory` guard |
| Image processing | Intervention Image GD, `cover(1200,630)->toJpeg(85)` | Exact sama dengan Program image, optimal untuk og:image 1200×630 |
| Validation rule | `nullable\|image\|mimes:jpg,jpeg,png,webp\|max:5120` | Mengikuti pola Program component (5MB limit, mime whitelist) |
| Save behavior | **Unified save button** — banner upload + price settings dalam 1 `saveZakat()` | UX lebih simpel; banner diproses DULU (fail-first), baru price disimpan |
| og:image | Pass `$metaImage` dari `ZakatIndex::render()` via `Storage::url()` (absolute URL) | `front.blade.php` sudah punya mekanisme `$metaImage` dengan fallback default |
| Old file cleanup | Delete old file from storage sebelum simpan yang baru | Cegah orphan files |

---

## Scope

**IN:**
- [x] Upload banner 1 gambar di admin settings zakat
- [x] Preview gambar existing di admin (jika ada)
- [x] Delete banner button di admin
- Preview gambar existing di admin (jika ada)
- Delete banner button di admin
- Tampil banner di `/zakat` front page (full-width, sebelum tabs)
- og:image support via `$metaImage` di front layout

**OUT:**
- Alt text / caption field
- Link URL di banner (tidak clickable)
- Banner untuk halaman lain (qurban, tabungan, dll)
- Image cropping UI
- WebP output (tetap JPEG sesuai pola existing)

---

## Files to Modify

| File | Perubahan |
|------|-----------|
| `app/Livewire/Admin/ZakatList.php` | +trait, +props, +mount logic, +saveZakat banner logic, +deleteZakatBanner() |
| `resources/views/livewire/admin/zakat-list.blade.php` | +banner upload card di settings tab |
| `app/Livewire/Front/ZakatIndex.php` | +banner path di mount(), +metaImage di render() |
| `resources/views/livewire/front/zakat-index.blade.php` | +banner img block sebelum tabs |

---

## Tasks

### TASK-1: Modifikasi ZakatList.php (Backend Admin)

**File**: `app/Livewire/Admin/ZakatList.php`

**Step 1.1 — Tambah trait `WithFileUploads`**

Tambahkan di blok `use` di atas class:
```php
use Livewire\WithFileUploads;
```

Di dalam class body, tambahkan ke trait list:
```php
use WithPagination, WithFileUploads;
```

**Step 1.2 — Tambah properties**

Setelah property `$zakat_gold_price_per_gram`, tambahkan:
```php
public $zakatBannerImage;        // TemporaryUploadedFile | null
public $existingZakatBanner;     // string path | null
```

**Step 1.3 — Update mount()**

Tambahkan setelah load `zakat_gold_price_per_gram`:
```php
$this->existingZakatBanner = AppSetting::get('zakat_banner_image');
```

**Step 1.4 — Update saveZakat()**

Tambahkan validation rule di array validate() untuk banner:
```php
'zakatBannerImage' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
```

Tambahkan banner processing block di `saveZakat()` SEBELUM price saving:
```php
// Banner upload processing (fail-first: process before price settings)
if ($this->zakatBannerImage) {
    // Delete old file if exists
    if ($this->existingZakatBanner) {
        Storage::disk('public')->delete($this->existingZakatBanner);
    }
    
    // Process and store new banner
    Storage::disk('public')->makeDirectory('zakat');
    $img = Image::make($this->zakatBannerImage->getRealPath())
        ->driver(new GdDriver)
        ->cover(1200, 630)
        ->toJpeg(85);
    $filename = uniqid() . '.jpg';
    $path = 'zakat/' . $filename;
    Storage::disk('public')->put($path, $img->toString());
    
    AppSetting::set('zakat_banner_image', $path, 'zakat', 'text', 'Banner Halaman Zakat');
    $this->existingZakatBanner = $path;
    $this->zakatBannerImage = null; // Reset Livewire temp file
}
```

> **Note imports needed** (add to top of file):
> ```php
> use Illuminate\Support\Facades\Storage;
> use Intervention\Image\Image;
> use Intervention\Image\Drivers\Gd\Driver as GdDriver;
> ```
> Check existing imports in ZakatList.php — hindari duplikat.

**Step 1.5 — Tambah method `deleteZakatBanner()`**

Tambahkan sebagai method baru setelah `saveZakat()`:
```php
public function deleteZakatBanner(): void
{
    if (!$this->existingZakatBanner) {
        return; // guard: nothing to delete
    }
    
    Storage::disk('public')->delete($this->existingZakatBanner);
    AppSetting::where('key', 'zakat_banner_image')->delete();
    // AppSetting::set() calls Cache::forget internally, but delete() does not:
    \Illuminate\Support\Facades\Cache::forget('app_setting_zakat_banner_image');
    
    $this->existingZakatBanner = null;
    $this->zakatBannerImage = null;
    session()->flash('success', 'Banner berhasil dihapus.');
}
```

**QA Checks TASK-1:**
- [x] `php artisan tinker` → `AppSetting::get('zakat_banner_image')` returns path string after upload
- [x] Storage file physically exists: `Storage::disk('public')->exists($path)` → true
- [x] After delete: `AppSetting::get('zakat_banner_image')` returns null
- [x] Re-upload: old file no longer exists in storage (no orphan)
- [x] Validation error shown if non-image file submitted
 - [x] Storage file physically exists: `Storage::disk('public')->exists($path)` → true
 - [x] After delete: `AppSetting::get('zakat_banner_image')` returns null
 - [x] Re-upload: old file no longer exists in storage (no orphan)
 - [x] Validation error shown if non-image file submitted

---

### TASK-2: Admin Blade — Banner Upload Card

**File**: `resources/views/livewire/admin/zakat-list.blade.php`

**Position**: Insert BEFORE line 298 (closing `</div>` of `space-y-8`), AFTER line 297 (closing `</div>` of green config section).

Insert the following banner card as a new sibling inside `space-y-8`:

```blade
{{-- Banner Card --}}
<div class="bg-green-50/50 rounded-2xl border border-green-100 p-6">
    <div class="flex items-center gap-3 mb-6">
        <div class="w-10 h-10 bg-green-100 rounded-xl flex items-center justify-center">
            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
            </svg>
        </div>
        <div>
            <h3 class="text-base font-semibold text-gray-800">Banner Halaman Zakat</h3>
            <p class="text-xs text-gray-500 mt-0.5">Gambar ditampilkan di halaman /zakat dan digunakan sebagai og:image saat dibagikan</p>
        </div>
    </div>

    {{-- Preview existing banner --}}
    @if($existingZakatBanner)
        <div class="mb-4">
            <p class="text-xs font-medium text-gray-500 mb-2">Banner saat ini:</p>
            <div class="relative group w-full rounded-xl overflow-hidden border border-green-200">
                <img src="{{ Storage::url($existingZakatBanner) }}"
                     alt="Banner Zakat"
                     class="w-full object-cover"
                     style="max-height: 180px; object-fit: cover;">
                <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                    <button type="button"
                            wire:click="deleteZakatBanner"
                            wire:confirm="Yakin ingin menghapus banner ini?"
                            class="bg-red-500 hover:bg-red-600 text-white text-xs font-medium px-3 py-1.5 rounded-lg flex items-center gap-1.5">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                        Hapus Banner
                    </button>
                </div>
            </div>
        </div>
    @endif

    {{-- Upload input --}}
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-2">
            {{ $existingZakatBanner ? 'Ganti Banner' : 'Upload Banner' }}
            <span class="text-xs text-gray-400 font-normal ml-1">(JPG/PNG/WebP, maks. 5MB, rasio 1200×630 direkomendasikan)</span>
        </label>
        <div class="border-2 border-dashed border-green-200 rounded-xl p-6 text-center hover:border-green-400 transition-colors">
            <input type="file"
                   wire:model="zakatBannerImage"
                   accept="image/jpg,image/jpeg,image/png,image/webp"
                   class="hidden"
                   id="zakatBannerUpload">
            <label for="zakatBannerUpload" class="cursor-pointer">
                {{-- Live preview via Alpine --}}
                <div x-data="{ preview: null }"
                     @change.capture="const f=$el.querySelector('input[type=file]').files[0]; if(f) preview=URL.createObjectURL(f)">
                    <template x-if="preview">
                        <img :src="preview" class="w-full max-h-40 object-cover rounded-lg mb-3">
                    </template>
                    <template x-if="!preview">
                        <div>
                            <svg class="w-8 h-8 text-green-400 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                            </svg>
                            <p class="text-sm text-gray-500">Klik untuk pilih gambar</p>
                        </div>
                    </template>
                </div>
            </label>
        </div>
        @error('zakatBannerImage')
            <p class="mt-1.5 text-xs text-red-500 flex items-center gap-1">
                <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                </svg>
                {{ $message }}
            </p>
        @enderror

        {{-- Livewire upload progress --}}
        <div wire:loading wire:target="zakatBannerImage" class="mt-2">
            <p class="text-xs text-green-600 flex items-center gap-1.5">
                <svg class="animate-spin w-3.5 h-3.5" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                </svg>
                Mengupload gambar...
            </p>
        </div>
    </div>
</div>
{{-- End Banner Card --}}
```

**QA Checks TASK-2:**
- [x] Banner card tampil di bawah green config section, sebelum tombol save
- [x] Preview existing banner tampil jika sudah ada gambar
- [x] Hover on existing banner → delete button muncul
- [x] Pilih file baru → local preview tampil sebelum save
- [x] Wire loading indicator tampil saat upload berlangsung
- [x] Error message tampil jika file invalid
- [x] `wire:confirm` dialog muncul saat klik hapus banner
 - [x] Preview existing banner tampil jika sudah ada gambar
 - [x] Hover on existing banner → delete button muncul
 - [x] Pilih file baru → local preview tampil sebelum save
 - [x] Wire loading indicator tampil saat upload berlangsung
 - [x] Error message tampil jika file invalid
 - [x] `wire:confirm` dialog muncul saat klik hapus banner

---

### TASK-3: Modifikasi ZakatIndex.php (Backend Frontend)

**File**: `app/Livewire/Front/ZakatIndex.php`

**Step 3.1 — Tambah property**

Setelah property yang ada (tambah bersama property lainnya):
```php
public $zakatBannerPath; // string|null — path dari AppSetting
```

**Step 3.2 — Update mount()**

Tambahkan setelah load `goldPricePerGram`:
```php
$this->zakatBannerPath = AppSetting::get('zakat_banner_image');
```

**Step 3.3 — Update render()**

Ubah return di `render()` untuk pass `$metaImage`:
```php
return view('livewire.front.zakat-index', [
    'metaImage' => $this->zakatBannerPath
        ? Storage::disk('public')->url($this->zakatBannerPath)
        : null,
]);
```

> **Note**: Pastikan `use Illuminate\Support\Facades\Storage;` ada di import (kemungkinan belum ada di ZakatIndex.php — cek existing imports).

**QA Checks TASK-3:**
- [x] `ZakatIndex::render()` passes `$metaImage` ke view
- [x] Jika banner ada: `$metaImage` berisi absolute URL (starts with `http`)
- [x] Jika banner tidak ada: `$metaImage` null (front layout fallback ke default-og.jpg)
- [x] Banner path berubah di render() tanpa perlu refresh manual
 - [x] Jika banner ada: `$metaImage` berisi absolute URL (starts with `http`)
 - [x] Jika banner tidak ada: `$metaImage` null (front layout fallback ke default-og.jpg)
 - [x] Banner path berubah di render() tanpa perlu refresh manual

---

### TASK-4: Frontend Blade — Tampilkan Banner

**File**: `resources/views/livewire/front/zakat-index.blade.php`

**Position**: Setelah baris `<main class="pb-32">` (line 30), SEBELUM `{{-- Tabs --}}` comment (line 31).

Insert:
```blade
{{-- Zakat Banner --}}
@if(isset($zakatBannerPath) && $zakatBannerPath)
    <div class="w-full">
        <img src="{{ Storage::url($zakatBannerPath) }}"
             alt="Banner Zakat"
             class="w-full object-cover"
             loading="lazy">
    </div>
@endif
{{-- End Zakat Banner --}}
```

> **Variable**: gunakan `$zakatBannerPath` yang di-pass dari component property (Livewire auto-exposes public properties ke view, jadi tidak perlu explicit pass dari render() untuk display — hanya `$metaImage` yang harus di-pass karena dipakai oleh layout, bukan view blade sendiri).
>
> **Alternatively**: jika Livewire version tidak auto-expose ke blade, gunakan `$this->zakatBannerPath` di component, atau pastikan explicit pass dari render(): `['zakatBannerPath' => $this->zakatBannerPath, 'metaImage' => ...]`

**QA Checks TASK-4:**
 - [x] Buka `/zakat` setelah upload banner → gambar tampil full-width di atas tab section
 - [x] Buka `/zakat` tanpa banner → tidak ada blank space / broken img tag
 - [x] Gambar responsive di mobile (max-width 480px sesuai layout front)
 - [x] `curl -s http://localhost:8000/zakat | grep 'banner'` mengembalikan img tag dengan src yang benar

---

### TASK-5: Verifikasi og:image di Front Layout

**File**: `resources/views/layouts/front.blade.php`  
**Action**: Verifikasi saja, tidak perlu edit.

Konfirmasi bahwa:
- `$metaImage` variable digunakan untuk `og:image` meta tag
- Fallback ke `asset('images/default-og.jpg')` jika null
- URL diformat sebagai absolute URL

Jika `front.blade.php` belum ada `$metaImage` support:
```blade
@php
    $ogImage = isset($metaImage) && $metaImage 
        ? $metaImage 
        : asset('images/default-og.jpg');
@endphp
<meta property="og:image" content="{{ $ogImage }}">
<meta property="og:image:width" content="1200">
<meta property="og:image:height" content="630">
```

**QA Checks TASK-5:**
 - [x] `curl -s http://localhost:8000/zakat | grep og:image` → menampilkan URL banner (bukan default)
 - [x] URL adalah absolute (starts with `http://` atau `https://`)
 - [x] Setelah delete banner: fallback ke `default-og.jpg`
 - [x] Test dengan Facebook Sharing Debugger atau og:image validator

---

## Final Verification Wave

Jalankan setelah semua tasks selesai:

```bash
# 1. Banner tersimpan di DB
php artisan tinker --execute="echo \App\Models\AppSetting::get('zakat_banner_image');"

# 2. File fisik ada di disk
php artisan tinker --execute="echo \Illuminate\Support\Facades\Storage::disk('public')->exists(\App\Models\AppSetting::get('zakat_banner_image')) ? 'EXISTS' : 'MISSING';"

# 3. og:image ada di halaman /zakat
curl -s http://localhost:8000/zakat | grep -o 'og:image.*'

# 4. Banner img tag ada di halaman /zakat
curl -s http://localhost:8000/zakat | grep -o 'src="[^"]*zakat[^"]*\.jpg"'

# 5. Setelah delete banner — fallback ke default
# (lakukan delete via admin UI, kemudian)
curl -s http://localhost:8000/zakat | grep og:image
# Expected: default-og.jpg
```

---

## Risk Register (dari Metis)

| Risk | Mitigation | Status |
|------|-----------|--------|
| Old file orphan saat re-upload | `Storage::delete($old)` before store new | ✅ Di plan |
| `$zakatBannerImage` tidak di-reset | `$this->zakatBannerImage = null` after save | ✅ Di plan |
| og:image relative URL (broken sosmed) | `Storage::disk('public')->url($path)` | ✅ Di plan |
| deleteZakatBanner() null guard | `if (!$this->existingZakatBanner) return;` | ✅ Di plan |
| `zakat/` directory belum ada | `makeDirectory('zakat')` before put | ✅ Di plan |
| AppSetting delete tidak clear cache | Manual `Cache::forget()` di deleteZakatBanner | ✅ Di plan |
| Portrait image break layout | Intervention Image `cover(1200,630)` force landscape | ✅ Di plan |
| Missing `WithFileUploads` trait | Tambahkan di class | ✅ Di plan |
