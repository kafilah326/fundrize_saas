<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\QurbanTabunganSetting;

class QurbanTabunganSettingSeeder extends Seeder
{
    public function run(): void
    {
        QurbanTabunganSetting::firstOrCreate([], [
            'title' => 'Tabungan Qurban',
            'subtitle' => 'Nabung sedikit demi sedikit untuk ibadah qurban',
            'description' => '<p>Tabungan Qurban memudahkan Anda untuk menyiapkan dana ibadah qurban dengan cara menabung secara bertahap. Anda bisa menabung sesuai kemampuan dan jadwal sendiri.</p><p>Setelah dana mencukupi untuk membeli hewan qurban, tabungan akan otomatis dikonversi menjadi program qurban pada waktu yang tepat sesuai kalender hijriah.</p>',
            'benefits' => [
                'Nabung kapan saja',
                'Sesuai syariah',
                'Otomatis jadi qurban saat cukup',
                'Ada pengingat rutin'
            ],
            'akad_title' => 'Informasi Akad',
            'akad_description' => 'Menggunakan akad Wadi\'ah Amanah dan Wakalah sesuai syariah',
            'terms' => [
                [
                    'title' => 'Akad Tabungan',
                    'description' => 'Program ini menggunakan akad Wadi\'ah Amanah dan Wakalah yang sesuai dengan prinsip syariah Islam.'
                ],
                [
                    'title' => 'Aturan Penarikan Dana',
                    'description' => 'Dana tabungan dapat ditarik kapan saja sebelum konversi ke program qurban. Setelah konversi, dana tidak dapat ditarik.'
                ],
                [
                    'title' => 'Ketentuan Wafat',
                    'description' => 'Jika penabung meninggal dunia, tabungan akan diserahkan kepada ahli waris sesuai ketentuan syariah.'
                ],
                [
                    'title' => 'Pengalihan Dana',
                    'description' => 'Dana dapat dialihkan ke program lain dengan persetujuan yayasan dan sesuai ketentuan yang berlaku.'
                ],
                [
                    'title' => 'Perubahan Harga',
                    'description' => 'Harga hewan qurban dapat berubah sesuai kondisi pasar. Penabung akan diberitahu jika ada perubahan target dana.'
                ],
            ]
        ]);
    }
}
