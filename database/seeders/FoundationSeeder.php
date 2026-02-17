<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\FoundationSetting;
use App\Models\LegalDocument;

class FoundationSeeder extends Seeder
{
    public function run(): void
    {
        FoundationSetting::create([
            'name' => 'Yayasan Peduli',
            'tagline' => 'Berbagi Kebaikan',
            'logo' => 'https://storage.googleapis.com/uxpilot-auth.appspot.com/1f472856-4b2a-4318-971c-4b3002302315.png',
            'about' => 'Didirikan sejak 2015, kami telah melayani ribuan penerima manfaat di seluruh Indonesia.',
            'vision' => 'Menjadi yayasan terpercaya yang mampu mengentaskan kemiskinan dan memajukan pendidikan di Indonesia melalui pengelolaan dana sosial yang transparan dan profesional.',
            'mission' => json_encode([
                'Menyelenggarakan program pendidikan untuk anak kurang mampu',
                'Memberikan bantuan kesehatan dan kemanusiaan',
                'Memberdayakan ekonomi masyarakat dhuafa'
            ]),
            'focus_areas' => json_encode(['Pendidikan', 'Kesehatan', 'Dakwah', 'Kemanusiaan']),
            'address' => 'Jl. Kebaikan No. 123, Jakarta Selatan 12560',
            'phone' => '+62 21 1234 5678',
            'email' => 'info@berbagiberkah.org',
            'social_media' => json_encode([
                'facebook' => '#',
                'instagram' => '#',
                'whatsapp' => '#',
                'youtube' => '#'
            ]),
        ]);

        $documents = [
            [
                'title' => 'Akta Pendirian',
                'document_number' => 'AHU-0012345.AH.01.04.Tahun 2020',
                'status' => 'Terverifikasi',
                'sort_order' => 1
            ],
            [
                'title' => 'SK Kemenkumham',
                'document_number' => 'AHU-0067890.AH.01.04.Tahun 2020',
                'status' => 'Terverifikasi',
                'sort_order' => 2
            ],
            [
                'title' => 'NPWP Yayasan',
                'document_number' => '12.345.678.9-012.000',
                'status' => 'Terverifikasi',
                'sort_order' => 3
            ],
            [
                'title' => 'Izin Pengumpulan Uang',
                'document_number' => 'No. 456/IPU/2023 - Kemensos RI',
                'status' => 'Aktif s.d 2025',
                'sort_order' => 4
            ],
            [
                'title' => 'Rekomendasi BAZNAS',
                'document_number' => 'No. B-789/BAZNAS/XII/2023',
                'status' => 'Terverifikasi',
                'sort_order' => 5
            ],
        ];

        foreach ($documents as $doc) {
            LegalDocument::create($doc);
        }
    }
}
