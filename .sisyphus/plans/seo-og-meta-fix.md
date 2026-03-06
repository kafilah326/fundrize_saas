# Plan: Fix SEO OG Metadata — Image Appears When Shared on WhatsApp/Facebook/Twitter

**Created**: 2026-03-07  
**Project**: Fundrize (Laravel 12 + Livewire 4 + Tailwind CSS 4)  
**Goal**: Ensure that when any page is shared on social media (WhatsApp, Facebook, Twitter/X), the correct OG image (og:image) appears in the link preview.

---

## Pre-Conditions (VERIFY BEFORE ANY CODE CHANGES)

### PRE-1 — Verify `APP_URL` is set to production domain
```bash
php artisan tinker --execute="echo config('app.url');"
```
**Assert**: Output is NOT `http://localhost`. Must be `https://yourdomain.com`.  
**Why**: `Storage::disk('public')->url()` returns `rtrim(APP_URL,'/') . '/storage/' . $path`. If APP_URL is localhost, all image URLs are unreachable by crawlers.  
**If wrong**: Update `.env` → `APP_URL=https://yourdomain.com` → `php artisan config:clear`.

### PRE-2 — Verify `default-og.jpg` exists and is under 300KB
```bash
ls -lh public/images/default-og.jpg
```
**Assert**: File exists AND size shown is < 300K.  
**Why**: WhatsApp silently drops OG images over 600KB on mobile networks.  
**If missing or too large**: Regenerate at 1200×630, JPEG quality 80:
```php
// Run in tinker or artisan command:
$img = imagecreatetruecolor(1200, 630);
$bg = imagecolorallocate($img, 255, 107, 53);
imagefill($img, 0, 0, $bg);
imagejpeg($img, public_path('images/default-og.jpg'), 80);
imagedestroy($img);
```

---

## Scope

**IN:**
- `resources/views/layouts/front.blade.php` — main layout meta tags
- `app/Livewire/Front/Home.php`
- `app/Livewire/Front/FoundationProfile.php`
- `app/Livewire/Front/ProgramIndex.php`
- `app/Livewire/Front/QurbanIndex.php`
- `app/Livewire/Front/ProgramDetail.php`

**OUT (do not touch):**
- Any admin Livewire components
- Model accessors (`getLogoAttribute`, `getImageAttribute`)
- Any other front Livewire components (checkout, payment, search, etc.) — they will benefit automatically from the layout-level fix
- Database migrations, routes, or controllers
- CSS/JS assets

---

## Implementation Tasks

### TASK 1 — Fix `resources/views/layouts/front.blade.php`

This is the highest-leverage change. Fixes 24+ components that currently emit ZERO `og:image` tags.

**1a. Add `@php` block at the very top of `<head>` (before any CSS/JS) to compute `$ogImage`:**

Immediately after the existing `@php` block that calculates theme colors (or replace/merge with it), add this logic:
```php
// OG image: ensure always absolute URL, fallback to default-og.jpg
$ogImage = isset($metaImage) && $metaImage ? $metaImage : asset('images/default-og.jpg');
if (!str_starts_with($ogImage, 'http')) {
    $ogImage = url($ogImage);
}
```

**1b. Move the entire OG/Twitter meta block to be FIRST in `<head>`:**

The OG/Twitter/schema meta tags MUST appear immediately after `<meta charset>` and `<meta viewport>`, BEFORE any `<link>` (fonts, CSS) or `<script>` (Tailwind CDN) tags.

**Why**: WhatsApp's crawler can timeout on heavy `<head>` with Tailwind CDN JS before reaching OG tags.

**The correct `<head>` structure:**
```html
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @php
        // ... existing theme color logic ...
        // NEW: compute $ogImage
        $ogImage = isset($metaImage) && $metaImage ? $metaImage : asset('images/default-og.jpg');
        if (!str_starts_with($ogImage, 'http')) {
            $ogImage = url($ogImage);
        }
    @endphp

    {{-- === SEO & OG META — MUST BE FIRST IN <head> === --}}
    <title>{{ $title ?? $foundationName }}</title>
    <meta name="description" content="{{ $metaDescription ?? $defaultDescription }}">
    <link rel="canonical" href="{{ url()->current() }}">

    {{-- Open Graph (Facebook, WhatsApp, LinkedIn) --}}
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:title" content="{{ $title ?? $foundationName }}">
    <meta property="og:description" content="{{ $metaDescription ?? $defaultDescription }}">
    <meta property="og:image" content="{{ $ogImage }}">
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="630">
    <meta property="og:image:alt" content="{{ $title ?? $foundationName }}">
    <meta property="og:locale" content="id_ID">
    <meta property="og:site_name" content="{{ $foundationName }}">

    {{-- Twitter/X Card --}}
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $title ?? $foundationName }}">
    <meta name="twitter:description" content="{{ $metaDescription ?? $defaultDescription }}">
    <meta name="twitter:image" content="{{ $ogImage }}">
    <meta name="twitter:image:alt" content="{{ $title ?? $foundationName }}">

    {{-- Schema.org / Pinterest --}}
    <meta itemprop="image" content="{{ $ogImage }}">
    <link rel="image_src" href="{{ $ogImage }}">

    @stack('meta')
    {{-- === END SEO META === --}}

    {{-- CSS, Fonts, JS below --}}
    <link rel="stylesheet" ...>
    {{-- Tailwind CDN etc. --}}
```

**What MUST be removed/changed:**
- REMOVE `og:image:secure_url` tag entirely (legacy, fragile `str_replace` implementation, redundant for HTTPS sites)
- REMOVE all `@if(isset($metaImage) && $metaImage)` guards — replace with unconditional `$ogImage` variable
- The `og:image`, `twitter:image`, `itemprop:image`, `link:image_src` are now ALL unconditional (always rendered with fallback)

**QA for Task 1:**
```bash
# Restart Laravel dev server first, then:
curl -s http://localhost:8000/ | grep -E 'og:image|og:locale|og:image:alt|twitter:image'
# Assert: ALL 4 grep patterns appear with absolute https:// URLs
curl -s http://localhost:8000/ | grep 'og:image:secure_url'
# Assert: NO output (tag removed)
```

---

### TASK 2 — Fix `app/Livewire/Front/Home.php`

**Problem**: `'metaImage' => $this->foundation->logo` uses the model accessor which returns `Storage::disk('public')->url($value)`. On some server configs this may return a relative path or http:// URL.

**Fix**: Ensure absolute URL at the call site in `render()`:

```php
// In render(), before the return statement:
$logo = $this->foundation->logo;
if ($logo && !str_starts_with($logo, 'http')) {
    $logo = url($logo);
}

return view('livewire.front.home')->layout('layouts.front', [
    'title'           => $this->foundation->name,
    'metaDescription' => Str::limit(strip_tags($this->foundation->about ?? ''), 160),
    'metaImage'       => $logo ?: null,  // null triggers layout-level fallback to default-og.jpg
]);
```

**Also**: Remove the duplicate `#[Layout('layouts.front')]` and `#[Title('Home')]` PHP attributes at the top of the class if they conflict with the `->layout()` call in `render()`. Only one mechanism should be used. The `->layout()` in `render()` is preferred as it allows passing variables.

**QA for Task 2:**
```bash
curl -s http://localhost:8000/ | grep 'og:image'
# Assert: content="https://..." (absolute URL, not /storage/...)
```

---

### TASK 3 — Fix `app/Livewire/Front/FoundationProfile.php`

**Problem**: Same as Home.php — passes `$this->foundation->logo` directly as `metaImage` without ensuring absolute URL. (This file was MISSING from the original fix scope.)

**Fix**: Apply identical pattern as Task 2:

```php
// In render():
$logo = $this->foundation->logo;
if ($logo && !str_starts_with($logo, 'http')) {
    $logo = url($logo);
}

return view('livewire.front.foundation-profile')->layout('layouts.front', [
    'title'           => 'Tentang Kami - ' . $this->foundation->name,
    'metaDescription' => Str::limit(strip_tags($this->foundation->about ?? ''), 160),
    'metaImage'       => $logo ?: null,
]);
```

**QA for Task 3:**
```bash
curl -s http://localhost:8000/foundation/profile | grep 'og:image'
# Assert: content="https://..." absolute URL present
```

---

### TASK 4 — Fix `app/Livewire/Front/ProgramIndex.php`

**Problem**: `'metaImage' => $foundation->logo ?? asset('images/default-og.jpg')` — The `??` operator only catches `null`, not relative string URLs. If `$foundation->logo` returns `/storage/foundation/logo.jpg` (relative), it passes through as a non-null value without becoming absolute.

**Fix**: Replace the `??` pattern with explicit URL check:

```php
// In render(), compute metaImage before return:
$logo = $foundation->logo;
if ($logo && !str_starts_with($logo, 'http')) {
    $logo = url($logo);
}

// In the ->layout() call, replace metaImage line:
'metaImage' => $logo ?: null,
```

**QA for Task 4:**
```bash
curl -s http://localhost:8000/program | grep 'og:image'
# Assert: content="https://..." absolute URL (not /storage/...)
```

---

### TASK 5 — Fix `app/Livewire/Front/QurbanIndex.php`

**Problem**: Identical to ProgramIndex.php — same `?? asset(...)` pattern with same relative URL risk.

**Fix**: Apply identical pattern as Task 4:

```php
$logo = $foundation->logo;
if ($logo && !str_starts_with($logo, 'http')) {
    $logo = url($logo);
}

// In ->layout():
'metaImage' => $logo ?: null,
```

**QA for Task 5:**
```bash
curl -s http://localhost:8000/qurban | grep 'og:image'
# Assert: content="https://..." absolute URL present
```

---

### TASK 6 — Fix `app/Livewire/Front/ProgramDetail.php`

**Problem 1**: When both program image AND foundation logo are missing, `$finalImage` stays as empty string `""`. Empty string is passed to layout as `metaImage`. Before Task 1's layout fix, this meant no og:image was emitted at all for imageless programs.

**Problem 2**: Line 40 has leading whitespace before `public function render()` — cosmetic indentation bug.

**Fix Problem 1**: After the existing absolute URL check block, add a final fallback:
```php
// After: if ($finalImage && !str_starts_with($finalImage, 'http')) { $finalImage = url($finalImage); }
// ADD THIS:
if (!$finalImage) {
    $finalImage = null; // null signals layout to use default-og.jpg fallback
}
```

**Fix Problem 2**: Fix indentation on the `render()` method declaration line — remove leading spaces so it aligns properly with the class body indentation.

**QA for Task 6:**
```bash
# Find a program slug that has no image in the database, then:
curl -s http://localhost:8000/program/{slug-without-image} | grep 'og:image'
# Assert: content="https://.../images/default-og.jpg" (fallback, not empty)

# Find a program slug that has an image:
curl -s http://localhost:8000/program/{slug-with-image} | grep 'og:image'
# Assert: content="https://..." pointing to the program image
```

---

## Final Verification Wave

Run all verification commands sequentially after all 6 tasks are complete:

```bash
# 1. Home — absolute og:image
curl -s http://localhost:8000/ | grep 'og:image'
# Assert: content contains https://

# 2. Home — og:locale present
curl -s http://localhost:8000/ | grep 'og:locale'
# Assert: content="id_ID"

# 3. Home — og:image:alt present
curl -s http://localhost:8000/ | grep 'og:image:alt'
# Assert: non-empty content attribute

# 4. Home — og:image:secure_url REMOVED
curl -s http://localhost:8000/ | grep 'og:image:secure_url'
# Assert: NO output

# 5. Program listing — og:image present
curl -s http://localhost:8000/program | grep 'og:image'
# Assert: content contains https://

# 6. Qurban listing — og:image present
curl -s http://localhost:8000/qurban | grep 'og:image'
# Assert: content contains https://

# 7. Foundation profile — og:image present
curl -s http://localhost:8000/foundation/profile | grep 'og:image'
# Assert: content contains https://

# 8. A page with NO metaImage (e.g. /report, /search) gets fallback default-og.jpg
curl -s http://localhost:8000/report | grep 'og:image'
# Assert: content contains "default-og.jpg"

# 9. Program detail with image — shows program image
# Replace {slug} with an actual program slug from the DB
curl -s http://localhost:8000/program/{slug} | grep 'og:image'
# Assert: content contains https:// (program-specific image)

# 10. Confirm server is running Laravel (not cached)
php artisan config:clear && php artisan view:clear && php artisan cache:clear
```

---

## Post-Deploy Actions (for client/admin)

### Facebook Cache Refresh
After deploying to production, force Facebook to re-scrape all key pages:
```
https://developers.facebook.com/tools/debug/
```
Enter each public URL → click "Scrape Again". Verify og:image thumbnail appears.

### WhatsApp Cache Bust
WhatsApp caches link previews aggressively (no official debug tool). To force a fresh preview for existing links:
- Share the URL with a query parameter: `https://yourdomain.com/program/slug?v=1`
- WhatsApp treats this as a new URL and re-scrapes

### Known Limitations
- `og:type` is hardcoded as `"website"` for all pages including program detail. Ideally program pages would use `"article"`. Not changed now (requires additional article meta fields not in scope).
- `twitter:image` is redundant (Twitter/X falls back to `og:image`). Kept for explicit compatibility.
