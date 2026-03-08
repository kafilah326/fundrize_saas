# Plan: Artisan Command `gambar:resize` untuk Resize Ulang Gambar Program

**Created**: 2026-03-07  
**Project**: Fundrize (Laravel 12 + Livewire 4)  
**Tujuan**: Buat command `php artisan gambar:resize` yang otomatis memproses semua gambar program yang sudah ada di storage — resize ke 1200×630, konversi ke JPEG — sehingga semua gambar lama pun menjadi valid untuk OG Facebook/WhatsApp.

---

## Konteks

- **Library**: `intervention/image` v3 sudah terinstall ✅
- **File target**: Hanya gambar program lokal (`programs/{filename}`) di `storage/app/public/`
- **Gambar eksternal** (`https://...`) di-skip (tidak bisa diproses)
- **Gambar placehold.co** di-skip
- **Pattern command existing**: `app/Console/Commands/GenerateVapidKeys.php` — menggunakan `extends Command` dengan `$signature` dan `handle()`
- **Laravel 12 registrasi command**: otomatis di-discover dari folder `app/Console/Commands/`

---

## Scope

**IN:**
- Buat file baru: `app/Console/Commands/ResizeProgramImages.php`

**OUT:**
- Tidak mengubah model, migration, routes
- Tidak menyentuh gambar Qurban, gambar Foundation logo, avatar user
- Tidak mengubah file yang sudah diproses (deteksi via dimensi)

---

## Spesifikasi Command

```
php artisan gambar:resize          → proses semua gambar program lokal
php artisan gambar:resize --dry-run → hanya tampilkan daftar, tidak proses
php artisan gambar:resize --force   → proses ulang meski gambar sudah 1200x630
```

---

## TASK 1 — Buat file `app/Console/Commands/ResizeProgramImages.php`

Buat file baru dengan isi berikut:

```php
<?php

namespace App\Console\Commands;

use App\Models\Program;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class ResizeProgramImages extends Command
{
    protected $signature = 'gambar:resize
                            {--dry-run : Tampilkan daftar gambar tanpa memprosesnya}
                            {--force : Proses ulang semua gambar meski sudah berukuran 1200x630}';

    protected $description = 'Resize dan konversi semua gambar program lama ke format JPEG 1200x630 untuk OG Facebook/WhatsApp';

    public function handle(): int
    {
        $dryRun = $this->option('dry-run');
        $force  = $this->option('force');

        // Ambil semua program yang punya gambar lokal
        $programs = Program::whereNotNull('image')
            ->whereNot('image', 'like', 'http%')
            ->whereNot('image', 'like', '%placehold%')
            ->get(['id', 'title', 'image']);

        if ($programs->isEmpty()) {
            $this->info('Tidak ada gambar program lokal yang perlu diproses.');
            return Command::SUCCESS;
        }

        $this->info("Ditemukan {$programs->count()} gambar program lokal.");
        $this->newLine();

        $processed = 0;
        $skipped   = 0;
        $failed    = 0;

        $manager = new ImageManager(new Driver());

        foreach ($programs as $program) {
            $rawPath     = $program->getRawOriginal('image');
            $storagePath = storage_path('app/public/' . $rawPath);

            // Cek file exist
            if (!file_exists($storagePath)) {
                $this->warn("  [SKIP] ID {$program->id} | File tidak ditemukan: {$rawPath}");
                $skipped++;
                continue;
            }

            // Cek dimensi — skip jika sudah 1200x630 dan tidak --force
            if (!$force) {
                [$width, $height] = getimagesize($storagePath);
                if ($width === 1200 && $height === 630) {
                    $this->line("  [SKIP] ID {$program->id} | Sudah 1200x630: {$rawPath}");
                    $skipped++;
                    continue;
                }
            }

            // Tampilkan info
            $this->line("  [PROSES] ID {$program->id} | {$program->title} | {$rawPath}");

            if ($dryRun) {
                $processed++;
                continue;
            }

            try {
                // Proses gambar
                $image     = $manager->read($storagePath);
                $processed_img = $image->cover(1200, 630)->toJpeg(85);

                // Tentukan path baru (ganti ekstensi ke .jpg)
                $newRelativePath = 'programs/' . pathinfo($rawPath, PATHINFO_FILENAME) . '.jpg';
                $newStoragePath  = storage_path('app/public/' . $newRelativePath);

                // Hapus file lama jika ekstensi berbeda (misal .png → .jpg)
                if ($rawPath !== $newRelativePath && file_exists($storagePath)) {
                    unlink($storagePath);
                }

                // Simpan file baru
                file_put_contents($newStoragePath, $processed_img);

                // Update DB jika path berubah (ekstensi berubah)
                if ($rawPath !== $newRelativePath) {
                    Program::where('image', $rawPath)->update(['image' => $newRelativePath]);
                    $this->line("    → Path diupdate ke: {$newRelativePath}");
                }

                $this->info("    ✓ Berhasil diproses");
                $processed++;

            } catch (\Throwable $e) {
                $this->error("    ✗ Gagal: " . $e->getMessage());
                $failed++;
            }
        }

        $this->newLine();
        $this->table(
            ['Status', 'Jumlah'],
            [
                ['✓ Diproses', $processed],
                ['- Di-skip',  $skipped],
                ['✗ Gagal',    $failed],
            ]
        );

        if ($dryRun) {
            $this->warn('Mode --dry-run aktif. Tidak ada file yang diubah.');
        }

        return $failed > 0 ? Command::FAILURE : Command::SUCCESS;
    }
}
```

---

## TASK 2 — Verifikasi command terdaftar

```bash
php artisan list | grep gambar
```
**Assert**: Output menampilkan `gambar:resize`

---

## TASK 3 — Test dry-run

```bash
php artisan gambar:resize --dry-run
```
**Assert**:
- Menampilkan daftar gambar yang akan diproses
- Tidak ada file yang berubah di storage

---

## TASK 4 — Eksekusi langsung

```bash
php artisan gambar:resize
```
**Assert**:
- Gambar PNG (`.png`) dikonversi menjadi `.jpg` dan path di DB diupdate
- Gambar JPG yang bukan 1200x630 di-resize
- Output tabel menampilkan jumlah berhasil/skip/gagal
- Tidak ada error

---

## TASK 5 — Verifikasi hasil

```bash
php artisan tinker --execute="
\$programs = App\Models\Program::whereNotNull('image')
    ->whereNot('image', 'like', 'http%')
    ->whereNot('image', 'like', '%placehold%')
    ->get(['id','image']);
foreach(\$programs as \$p) {
    \$path = storage_path('app/public/' . \$p->getRawOriginal('image'));
    if(file_exists(\$path)) {
        list(\$w, \$h) = getimagesize(\$path);
        \$mime = mime_content_type(\$path);
        echo 'ID ' . \$p->id . ' | ' . \$w . 'x' . \$h . ' | ' . \$mime . PHP_EOL;
    }
}
" 2>&1
```
**Assert setiap baris**:
- Dimensi = `1200x630`
- MIME = `image/jpeg`

---

## TASK 6 — Commit & Push

```bash
git add app/Console/Commands/ResizeProgramImages.php
git commit -m "feat: tambah artisan command gambar:resize untuk resize gambar program lama ke JPEG 1200x630"
git push
```
