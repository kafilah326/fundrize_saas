### Intervention Image v3 Usage
In Laravel 12 / Livewire 4 setup, Intervention Image v3 is used.
Key patterns:
- Use  with a driver (e.g., ).
-  to load image.
-  for resizing.
-  to encode.
- Cast to  for .

### Livewire 4 File Uploads
- Use  trait.
-  to get the temporary file path for Intervention Image.
### TASK-2: Frontend Zakat Banner Upload UI
- Implemented the banner upload card in `resources/views/livewire/admin/zakat-list.blade.php`.
- Used Alpine.js for live preview.
- Integrated `wire:model="zakatBannerImage"` and `wire:click="deleteZakatBanner"`.
- Followed existing Tailwind design patterns (blue theme for banner section).
- Verified div nesting (inside `space-y-8`, after green config section).
### Intervention Image v3 Usage
In Laravel 12 / Livewire 4 setup, Intervention Image v3 is used.
Key patterns:
- Use ImageManager with a driver (e.g., GdDriver).
- read() to load image.
- cover(width, height) for resizing.
- toJpeg(quality) to encode.
- Cast to (string) for Storage::put().

### Livewire 4 File Uploads
- Use WithFileUploads trait.
- $file->getRealPath() to get the temporary file path for Intervention Image.
### Intervention Image v3 Encoding
- `toJpeg()`, `toPng()`, etc. return an `EncodedImage` object.
- The original `Image` object is NOT mutated into encoded data.
- You must capture the return value of the encoding method and use it (cast to string) for storage.
- Example: `$processed = $image->cover(W, H)->toJpeg(Q); Storage::put($path, (string) $processed);`

### Directory Creation
- Always ensure the target directory exists using `Storage::makeDirectory()` before writing files or processing images that might depend on the directory structure.
### Patterns & Conventions
- Using `AppSetting::get('key')` to fetch configuration values from the database.
- Using `Storage::disk('public')->url($path)` to generate absolute URLs for meta tags (og:image).
- Passing data to Livewire views via the `render()` method's return array for meta-level data like `metaImage`.

### Successes
- Successfully implemented TASK-3: Updated `ZakatIndex.php` to handle zakat banner image for meta tags.
### TASK-4 Implementation - Tue Mar 10 19:44:42 SEAST 2026
- Successfully inserted zakat banner image block into resources/views/livewire/front/zakat-index.blade.php.
- Placed banner exactly after <main> and before tabs section.
- Used Storage::url($zakatBannerPath) for asset resolution.
## TASK-5 Verification
The og:image implementation in  was verified and found to be correct according to requirements:
- It uses `$metaImage` if set, with a fallback to `asset('images/default-og.jpg')`.
- It enforces absolute URLs by checking for 'http' and using `url()` if missing.
- The logic is implemented at the top of the file and correctly used in `og:image`, `twitter:image`, and other meta tags.
## TASK-5 Verification
The og:image implementation in resources/views/layouts/front.blade.php was verified and found to be correct according to requirements:
- It uses $metaImage if set, with a fallback to asset('images/default-og.jpg').
- It enforces absolute URLs by checking for 'http' and using url() if missing.
- The logic is implemented at the top of the file and correctly used in og:image, twitter:image, and other meta tags.
