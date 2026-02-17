<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Program;
use App\Models\Category;
use App\Models\AkadType;
use App\Models\ProgramUpdate;
use App\Models\ProgramDistribution;
use Illuminate\Support\Str;

class ProgramSeeder extends Seeder
{
    public function run(): void
    {
        $programs = [
            [
                'title' => 'Bangun Sekolah untuk Anak Yatim',
                'image' => 'https://storage.googleapis.com/uxpilot-auth.appspot.com/2c7d9e03-5b8f-4d1a-9c3e-8f2a1b4d5e6f.jpg',
                'description' => 'Mari bersama membangun masa depan anak yatim dengan menyediakan fasilitas pendidikan yang layak. Sekolah ini akan menjadi tempat belajar bagi 200 anak yatim dhuafa secara gratis.',
                'target_amount' => 90000000,
                'collected_amount' => 65250000,
                'donor_count' => 1247,
                'end_date' => now()->addDays(15),
                'is_featured' => true,
                'is_urgent' => true,
                'categories' => ['Pendidikan', 'Sosial'],
                'akads' => ['Sedekah', 'Wakaf'],
            ],
            [
                'title' => 'Klinik Gratis untuk Masyarakat Dhuafa',
                'image' => 'https://storage.googleapis.com/uxpilot-auth.appspot.com/a1b2c3d4-e5f6-4a5b-8c7d-9e0f1a2b3c4d.jpg',
                'description' => 'Klinik gratis ini akan melayani masyarakat dhuafa yang membutuhkan layanan kesehatan dasar tanpa dipungut biaya.',
                'target_amount' => 70000000,
                'collected_amount' => 45250000,
                'donor_count' => 850,
                'end_date' => now()->addDays(25),
                'is_featured' => true,
                'is_urgent' => false,
                'categories' => ['Kesehatan', 'Sosial'],
                'akads' => ['Sedekah', 'Zakat'],
            ],
            [
                'title' => 'Sumur Air Bersih untuk Desa Terpencil',
                'image' => 'https://storage.googleapis.com/uxpilot-auth.appspot.com/f6e5d4c3-b2a1-4098-7654-3210fedcba98.jpg',
                'description' => 'Bantu warga desa terpencil mendapatkan akses air bersih yang layak untuk kebutuhan sehari-hari dan ibadah.',
                'target_amount' => 50000000,
                'collected_amount' => 38900000,
                'donor_count' => 620,
                'end_date' => now()->addDays(10),
                'is_featured' => true,
                'is_urgent' => true,
                'categories' => ['Sosial', 'Kemanusiaan'],
                'akads' => ['Sedekah', 'Wakaf'],
            ],
            [
                'title' => 'Santunan Bulanan Anak Yatim',
                'image' => 'https://storage.googleapis.com/uxpilot-auth.appspot.com/12345678-90ab-cdef-1234-567890abcdef.jpg',
                'description' => 'Program santunan rutin bulanan untuk biaya hidup dan pendidikan anak-anak yatim binaan yayasan.',
                'target_amount' => 60000000,
                'collected_amount' => 28500000,
                'donor_count' => 415,
                'end_date' => now()->addDays(30),
                'is_featured' => false,
                'is_urgent' => false,
                'categories' => ['Sosial', 'Pendidikan'],
                'akads' => ['Sedekah', 'Zakat'],
            ],
            [
                'title' => 'Beasiswa Tahfidz Al-Quran',
                'image' => 'https://storage.googleapis.com/uxpilot-auth.appspot.com/fedcba98-7654-3210-fedc-ba9876543210.jpg',
                'description' => 'Mencetak generasi qurani dengan memberikan beasiswa penuh bagi santri penghafal Al-Quran.',
                'target_amount' => 60000000,
                'collected_amount' => 32800000,
                'donor_count' => 530,
                'end_date' => now()->addDays(20),
                'is_featured' => true,
                'is_urgent' => false,
                'categories' => ['Pendidikan', 'Dakwah'],
                'akads' => ['Sedekah'],
            ],
        ];

        foreach ($programs as $data) {
            $categories = $data['categories'];
            $akads = $data['akads'];
            unset($data['categories'], $data['akads']);

            $data['slug'] = Str::slug($data['title']);
            $program = Program::create($data);

            // Attach Categories
            $catIds = Category::whereIn('name', $categories)->pluck('id');
            $program->categories()->attach($catIds);

            // Attach Akad Types
            $akadIds = AkadType::whereIn('name', $akads)->pluck('id');
            $program->akads()->attach($akadIds);

            // Create Updates
            ProgramUpdate::create([
                'program_id' => $program->id,
                'title' => 'Progres Pembangunan Mencapai 80%',
                'description' => 'Alhamdulillah, berkat doa dan dukungan para donatur, pembangunan pondasi dan struktur utama telah selesai.',
                'published_at' => now()->subDays(3),
            ]);

            // Create Distributions
            ProgramDistribution::create([
                'program_id' => $program->id,
                'amount_distributed' => $program->collected_amount * 0.7,
                'description' => 'Penyaluran tahap pertama untuk pembelian material dan upah tukang.',
                'documentation_date' => now()->subDays(5),
            ]);
        }
    }
}
