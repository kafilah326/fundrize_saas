<?php

namespace Database\Seeders;

use App\Models\WhatsappTemplate;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class WhatsappTemplateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        WhatsappTemplate::updateOrCreate(
            ['name' => 'Default Zakat - Pembayaran Dibuat'],
            [
                'type' => 'zakat',
                'event' => 'payment_created',
                'content' => "Assalamu'alaikum {{nama}} 🌙\n\nTerima kasih, *{{jenis_zakat}}* Anda telah kami terima.\n\n📋 *Detail Transaksi:*\n• No. Transaksi: {{no_transaksi}}\n• Jenis: {{jenis_zakat}} ({{detail_zakat}})\n• Jumlah: {{jumlah}}\n• Total Bayar: {{total}}\n\n⏳ Segera selesaikan pembayaran sebelum: {{batas_waktu}}\n🔗 Link Pembayaran: {{link_pembayaran}}\n\nSemoga zakat Anda membawa keberkahan.\n_{{yayasan}}_",
                'is_active' => true
            ]
        );

        WhatsappTemplate::updateOrCreate(
            ['name' => 'Default Zakat - Pembayaran Berhasil'],
            [
                'type' => 'zakat',
                'event' => 'payment_success',
                'content' => "Assalamu'alaikum {{nama}} ✅\n\n*Pembayaran {{jenis_zakat}} Anda telah berhasil!*\n\n📋 *Detail:*\n• No. Transaksi: {{no_transaksi}}\n• Jenis: {{jenis_zakat}} ({{detail_zakat}})\n• Total Dibayar: {{total}}\n\nJazakallahu Khairan atas zakat Anda 🤲\nSemoga menjadi amal yang diterima oleh Allah SWT.\n\n_{{yayasan}}_",
                'is_active' => true
            ]
        );

        WhatsappTemplate::updateOrCreate(
            ['name' => 'Default Zakat - Pembayaran Expired'],
            [
                'type' => 'zakat',
                'event' => 'payment_expired',
                'content' => "Assalamu'alaikum {{nama}} ⚠️\n\nTransaksi *{{jenis_zakat}}* Anda telah *kedaluwarsa*.\n\n📋 *Detail:*\n• No. Transaksi: {{no_transaksi}}\n• Jenis: {{jenis_zakat}} ({{detail_zakat}})\n• Jumlah: {{total}}\n\nSilakan lakukan transaksi zakat baru melalui platform kami.\nKami siap membantu Anda menunaikan zakat. 🙏\n\n_{{yayasan}}_",
                'is_active' => true
            ]
        );
    }
}
