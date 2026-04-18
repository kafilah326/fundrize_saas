<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\AkadType;
use App\Models\Program;
use App\Models\Tenant;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProgramSeeder extends Seeder
{
    public function run(): void
    {
        $tenants = Tenant::all();
        $categories = Category::all();
        $akads = AkadType::all();

        if ($tenants->isEmpty()) {
            $this->call(TenantSeeder::class);
            $tenants = Tenant::all();
        }

        foreach ($tenants as $tenant) {
            // Programs for each tenant
            $programs = [
                [
                    'title' => 'Bantuan Pangan Luar Biasa untuk Dhuafa',
                    'description' => 'Membantu menyediakan bahan pokok bagi keluarga pra-sejahtera di sekitar ' . $tenant->name . '.',
                    'target_amount' => 50000000,
                    'collected_amount' => 12500000,
                    'is_urgent' => true,
                    'category' => 'Kemanusiaan',
                    'akad' => 'Sedekah',
                ],
                [
                    'title' => 'Beasiswa Pendidikan Anak Yatim',
                    'description' => 'Program berkelanjutan untuk memastikan anak-anak yatim binaan ' . $tenant->name . ' tetap bisa sekolah.',
                    'target_amount' => 100000000,
                    'collected_amount' => 45000000,
                    'is_urgent' => false,
                    'category' => 'Pendidikan',
                    'akad' => 'Sedekah',
                ],
                [
                    'title' => 'Sedekah Air Bersih Pelosok',
                    'description' => 'Pembangunan sumur bor dan instalasi air bersih di daerah kekeringan.',
                    'target_amount' => 75000000,
                    'collected_amount' => 60000000,
                    'is_urgent' => false,
                    'category' => 'Sosial',
                    'akad' => 'Wakaf',
                ],
            ];

            foreach ($programs as $p) {
                $program = Program::create([
                    'tenant_id' => $tenant->id,
                    'title' => $p['title'],
                    'slug' => Str::slug($p['title']) . '-' . $tenant->id,
                    'description' => $p['description'],
                    'target_amount' => $p['target_amount'],
                    'collected_amount' => $p['collected_amount'],
                    'donor_count' => rand(10, 100),
                    'is_active' => true,
                    'is_urgent' => $p['is_urgent'],
                    'end_date' => now()->addMonths(3),
                    'image' => 'https://placehold.co/800x600/f97316/ffffff?text=' . urlencode($p['title']),
                ]);

                // Attach Category
                $cat = $categories->where('name', $p['category'])->first();
                if ($cat) $program->categories()->attach($cat->id);

                // Attach Akad
                $akad = $akads->where('name', $p['akad'])->first();
                if ($akad) $program->akads()->attach($akad->id);
            }
        }
    }
}
