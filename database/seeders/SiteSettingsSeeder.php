<?php

namespace Database\Seeders;

use App\Models\AppSetting;
use Illuminate\Database\Seeder;

class SiteSettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $settings = [
            // Hero Section
            [
                'key' => 'landing_hero_title',
                'value' => 'Digitalkan Yayasan Anda Dalam Sekejap.',
                'group' => 'landing_hero',
                'type' => 'text',
                'label' => 'Hero Title',
                'description' => 'Judul utama di halaman landing (Gunakan <br> untuk baris baru)',
            ],
            [
                'key' => 'landing_hero_subtitle',
                'value' => 'Platform all-in-one untuk manajemen donasi, zakat, qurban, dan pemberdayaan fundraiser. Kelola semua operasional lembaga sosial Anda dengan sistem yang transparan dan modern.',
                'group' => 'landing_hero',
                'type' => 'textarea',
                'label' => 'Hero Subtitle',
                'description' => 'Deskripsi singkat di bawah judul utama',
            ],
            [
                'key' => 'landing_hero_cta_text',
                'value' => 'Lihat Fitur',
                'group' => 'landing_hero',
                'type' => 'text',
                'label' => 'Hero CTA Button Text',
                'description' => 'Teks pada tombol utama',
            ],
            [
                'key' => 'landing_hero_badge',
                'value' => 'Terbaru: Fitur Fundraiser & Laporan Otomatis',
                'group' => 'landing_hero',
                'type' => 'text',
                'label' => 'Hero Badge Text',
                'description' => 'Teks kecil di atas judul utama',
            ],

            // Features Section
            [
                'key' => 'landing_features_title',
                'value' => 'Kenapa Harus Menggunakan Fundrize?',
                'group' => 'landing_features',
                'type' => 'text',
                'label' => 'Features Section Title',
                'description' => 'Judul bagian fitur unggulan',
            ],
            [
                'key' => 'landing_features_subtitle',
                'value' => 'Kami menyediakan semua kebutuhan digitalisasi yang diperlukan oleh lembaga filantropi modern.',
                'group' => 'landing_features',
                'type' => 'textarea',
                'label' => 'Features Section Subtitle',
                'description' => 'Deskripsi bagian fitur unggulan',
            ],

            // FAQ Section (Stored as JSON)
            [
                'key' => 'landing_faqs',
                'value' => json_encode([
                    ['q' => 'Berapa lama proses aktivasinya?', 'a' => 'Setelah pembayaran terkonfirmasi, sistem akan otomatis melakukan pembuatan subdomain dan dashboard yayasan Anda dalam waktu kurang dari 5 menit.'],
                    ['q' => 'Apakah saya bisa menggunakan domain sendiri?', 'a' => 'Ya, untuk paket "Pro" ke atas, Anda dapat menghubungkan domain kustom sendiri (misal: donasi.nama-yayasan.org) melalui menu pengaturan admin.'],
                    ['q' => 'Apakah ada biaya tambahan per transaksi?', 'a' => 'Kami hanya menarik "System Fee" kecil sesuai paket yang dipilih (misal 2% untuk paket Pro) untuk biaya operasional platform.'],
                ]),
                'group' => 'landing_faq',
                'type' => 'json',
                'label' => 'Landing Page FAQs',
                'description' => 'Daftar pertanyaan dan jawaban di halaman landing',
            ],

            // Final CTA
            [
                'key' => 'landing_cta_title',
                'value' => 'Siap Mendigitalkan Yayasan Anda Sekarang?',
                'group' => 'landing_cta',
                'type' => 'text',
                'label' => 'Final CTA Title',
                'description' => 'Judul ajakan terakhir di bagian bawah',
            ],
            [
                'key' => 'landing_cta_subtitle',
                'value' => 'Mulai langkah kebaikan Anda secara digital hari ini dengan Fundrize.',
                'group' => 'landing_cta',
                'type' => 'textarea',
                'label' => 'Final CTA Subtitle',
                'description' => 'Deskripsi ajakan terakhir di bagian bawah',
            ],
        ];

        foreach ($settings as $setting) {
            AppSetting::updateOrCreate(
                ['key' => $setting['key']],
                [
                    'tenant_id' => null, // Global scope
                    'value' => $setting['value'],
                    'group' => $setting['group'],
                    'type' => $setting['type'],
                    'label' => $setting['label'],
                    'description' => $setting['description'],
                ]
            );
        }
    }
}
