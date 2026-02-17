<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\AkadType;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Pendidikan', 'icon' => 'fa-graduation-cap'],
            ['name' => 'Kesehatan', 'icon' => 'fa-heart-pulse'],
            ['name' => 'Sosial', 'icon' => 'fa-users'],
            ['name' => 'Kemanusiaan', 'icon' => 'fa-hand-holding-heart'],
            ['name' => 'Masjid', 'icon' => 'fa-mosque'],
            ['name' => 'Dakwah', 'icon' => 'fa-microphone'],
            ['name' => 'Bencana', 'icon' => 'fa-house-crack'],
            ['name' => 'Lainnya', 'icon' => 'fa-ellipsis'],
        ];

        foreach ($categories as $cat) {
            Category::create([
                'name' => $cat['name'],
                'slug' => Str::slug($cat['name']),
                'icon' => $cat['icon'],
            ]);
        }

        $akads = [
            ['name' => 'Sedekah', 'icon' => 'fa-hand-holding-dollar'],
            ['name' => 'Wakaf', 'icon' => 'fa-building-columns'],
            ['name' => 'Zakat', 'icon' => 'fa-scale-balanced'],
            ['name' => 'Qurban', 'icon' => 'fa-cow'],
            ['name' => 'Fidyah', 'icon' => 'fa-bowl-rice'],
        ];

        foreach ($akads as $akad) {
            AkadType::create([
                'name' => $akad['name'],
                'slug' => Str::slug($akad['name']),
                'icon' => $akad['icon'],
            ]);
        }
    }
}
