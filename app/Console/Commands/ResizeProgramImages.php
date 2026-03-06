<?php

namespace App\Console\Commands;

use App\Models\Program;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class ResizeProgramImages extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'gambar:resize 
                            {--dry-run : Tampilkan daftar gambar tanpa memprosesnya}
                            {--force : Proses ulang semua gambar meski sudah berukuran 1200x630}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Resize dan konversi semua gambar program lama ke format JPEG 1200x630 untuk OG Facebook/WhatsApp';

    /**
     * Execute the console command.
     */
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
            $this->line("  [PROSES] ID {$program->id} | {$program->title}");
            $this->line("           Lama: {$rawPath}");

            if ($dryRun) {
                $processed++;
                continue;
            }

            try {
                // Proses gambar
                $image = $manager->read($storagePath);
                $processed_img = $image->cover(1200, 630)->toJpeg(85);

                // Tentukan path baru (ganti ekstensi ke .jpg)
                $pathInfo = pathinfo($rawPath);
                $newRelativePath = 'programs/' . $pathInfo['filename'] . '.jpg';
                $newStoragePath  = storage_path('app/public/' . $newRelativePath);

                // Simpan file baru (timpa yang ada jika sama namanya, atau buat baru jika beda ekstensi)
                // Kita gunakan Storage facade agar permission benar
                Storage::disk('public')->put($newRelativePath, $processed_img->toString());

                // Update DB jika path berubah (ekstensi berubah misal .png → .jpg)
                if ($rawPath !== $newRelativePath) {
                    Program::where('id', $program->id)->update(['image' => $newRelativePath]);
                    $this->line("           Baru: {$newRelativePath}");
                    
                    // Hapus file lama jika ektensi berbeda (jangan sampai menumpuk)
                    if (file_exists($storagePath)) {
                        unlink($storagePath);
                    }
                }

                $this->info("           ✓ Berhasil diproses");
                $processed++;

            } catch (\Throwable $e) {
                $this->error("           ✗ Gagal: " . $e->getMessage());
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
            $this->warn('Mode --dry-run aktif. Tidak ada file yang diubah di storage maupun database.');
        }

        return $failed > 0 ? Command::FAILURE : Command::SUCCESS;
    }
}
