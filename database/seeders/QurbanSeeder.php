<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\QurbanAnimal;
use App\Models\QurbanOrder;
use App\Models\QurbanSaving;
use App\Models\QurbanSavingsDeposit;
use App\Models\Payment;
use App\Models\User;

class QurbanSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Seed Animals - Qurban Langsung
        $langsungAnimals = [
            ['name' => 'Kambing Jantan Premium', 'category' => 'kambing', 'weight' => '28-30 kg', 'price' => 2800000],
            ['name' => 'Kambing Betina Premium', 'category' => 'kambing', 'weight' => '23-25 kg', 'price' => 2200000],
            ['name' => 'Sapi Jantan Premium', 'category' => 'sapi', 'weight' => '300-350 kg', 'price' => 22000000],
            ['name' => 'Sapi Betina Premium', 'category' => 'sapi', 'weight' => '250-300 kg', 'price' => 18500000],
            ['name' => 'Sapi 1/7 (Patungan)', 'category' => 'sapi', 'weight' => 'Per Bagian', 'price' => 3200000],
            ['name' => 'Domba Jantan Premium', 'category' => 'domba', 'weight' => '28-30 kg', 'price' => 2600000],
            ['name' => 'Kerbau Jantan Premium', 'category' => 'kerbau', 'weight' => '350-400 kg', 'price' => 24000000],
        ];

        foreach ($langsungAnimals as $animal) {
            QurbanAnimal::create(array_merge($animal, [
                'type' => 'langsung',
                'stock' => 50,
                'image' => 'https://via.placeholder.com/80',
                'is_active' => true,
            ]));
        }

        // 2. Seed Animals - Qurban Tabungan (Target)
        $tabunganAnimals = [
            ['name' => 'Kambing', 'category' => 'kambing', 'weight' => '25-30 kg', 'price' => 2500000, 'description' => 'Target tabungan kambing qurban'],
            ['name' => 'Domba', 'category' => 'domba', 'weight' => '25-30 kg', 'price' => 3000000, 'description' => 'Target tabungan domba qurban'],
            ['name' => 'Sapi 1/7', 'category' => 'sapi', 'weight' => 'Per Bagian', 'price' => 3500000, 'description' => 'Patungan 1/7 bagian sapi'],
            ['name' => 'Sapi Utuh', 'category' => 'sapi', 'weight' => '300-350 kg', 'price' => 24000000, 'description' => 'Target tabungan sapi utuh'],
        ];

        foreach ($tabunganAnimals as $animal) {
            QurbanAnimal::create(array_merge($animal, [
                'type' => 'tabungan',
                'stock' => 0,
                'image' => 'https://via.placeholder.com/80',
                'is_active' => true,
            ]));
        }

        // 3. Seed Orders (History)
        $user = User::where('email', 'siti.nurhaliza@email.com')->first();
        if (!$user) return;

        $kambing = QurbanAnimal::where('category', 'kambing')->where('type', 'langsung')->first();
        $sapi17 = QurbanAnimal::where('name', 'like', '%1/7%')->where('type', 'langsung')->first();

        // Order 1: Sapi 1/7 - Xendit/QRIS - sudah success
        $order1 = QurbanOrder::create([
            'transaction_id' => 'QRB20260520143045',
            'user_id' => $user->id,
            'qurban_animal_id' => $sapi17->id,
            'hijri_year' => '1447 H',
            'donor_name' => 'Ahmad Fauzi Rahman',
            'whatsapp' => '081234567890',
            'email' => 'ahmad.fauzi@email.com',
            'qurban_name' => 'Ahmad Fauzi Rahman & Keluarga',
            'address' => 'Jl. Merdeka No. 123',
            'city' => 'Solo',
            'postal_code' => '57138',
            'slaughter_method' => 'wakalah',
            'delivery_method' => 'dikirim',
            'amount' => 2500000,
            'payment_method' => 'QRIS',
            'status' => 'success',
            'created_at' => '2026-05-20 14:30:00',
        ]);

        Payment::create([
            'external_id' => 'QRB20260520143045',
            'transaction_type' => 'qurban_langsung',
            'user_id' => $user->id,
            'qurban_order_id' => $order1->id,
            'customer_name' => 'Ahmad Fauzi Rahman',
            'customer_email' => 'ahmad.fauzi@email.com',
            'customer_phone' => '081234567890',
            'payment_type' => 'xendit',
            'amount' => 2500000,
            'admin_fee' => 0,
            'total' => 2500000,
            'payment_method' => 'QRIS',
            'status' => 'paid',
            'paid_at' => '2026-05-20 14:35:00',
            'expired_at' => '2026-05-21 14:30:00',
            'created_at' => '2026-05-20 14:30:00',
        ]);

        // Order 2: Kambing - BCA (bank_transfer) - sudah success
        $order2 = QurbanOrder::create([
            'transaction_id' => 'QRB20250520143045',
            'user_id' => $user->id,
            'qurban_animal_id' => $kambing->id,
            'hijri_year' => '1446 H',
            'donor_name' => 'Ahmad Fauzi Rahman',
            'whatsapp' => '081234567890',
            'qurban_name' => 'Alm. Bapak Rahman',
            'slaughter_method' => 'wakalah',
            'delivery_method' => 'disalurkan',
            'amount' => 1800000,
            'payment_method' => 'bca',
            'status' => 'success',
            'created_at' => '2025-06-10 10:00:00',
        ]);

        Payment::create([
            'external_id' => 'QRB20250520143045',
            'transaction_type' => 'qurban_langsung',
            'user_id' => $user->id,
            'qurban_order_id' => $order2->id,
            'customer_name' => 'Ahmad Fauzi Rahman',
            'customer_phone' => '081234567890',
            'payment_type' => 'bank_transfer',
            'amount' => 1800000,
            'admin_fee' => 0,
            'total' => 1800345,
            'unique_code' => 345,
            'payment_method' => 'bca',
            'status' => 'paid',
            'paid_at' => '2025-06-10 12:00:00',
            'expired_at' => '2025-06-11 10:00:00',
            'created_at' => '2025-06-10 10:00:00',
        ]);

        // Order 3: Kambing - bank_transfer BRI - PENDING (bisa di-konfirmasi dari admin)
        $order3 = QurbanOrder::create([
            'transaction_id' => 'QRB20260215090000',
            'user_id' => $user->id,
            'qurban_animal_id' => $kambing->id,
            'hijri_year' => '1447 H',
            'donor_name' => 'Budi Santoso',
            'whatsapp' => '081298765432',
            'email' => 'budi.santoso@email.com',
            'qurban_name' => 'Budi Santoso & Keluarga',
            'address' => 'Jl. Sudirman No. 45',
            'city' => 'Jakarta',
            'postal_code' => '10220',
            'slaughter_method' => 'wakalah',
            'delivery_method' => 'dikirim',
            'amount' => 2800000,
            'payment_method' => 'bri',
            'status' => 'pending',
            'created_at' => '2026-02-15 09:00:00',
        ]);

        Payment::create([
            'external_id' => 'QRB20260215090000',
            'transaction_type' => 'qurban_langsung',
            'user_id' => $user->id,
            'qurban_order_id' => $order3->id,
            'customer_name' => 'Budi Santoso',
            'customer_email' => 'budi.santoso@email.com',
            'customer_phone' => '081298765432',
            'payment_type' => 'bank_transfer',
            'amount' => 2800000,
            'admin_fee' => 0,
            'total' => 2800567,
            'unique_code' => 567,
            'payment_method' => 'bri',
            'status' => 'pending',
            'expired_at' => '2026-02-16 09:00:00',
            'created_at' => '2026-02-15 09:00:00',
        ]);

        // 3. Seed Savings
        $saving = QurbanSaving::create([
            'user_id' => $user->id,
            'target_animal_type' => 'sapi-1-7',
            'target_amount' => 3500000,
            'saved_amount' => 1150000,
            'target_hijri_year' => '1448 H',
            'donor_name' => 'Ahmad Wijaya',
            'whatsapp' => '0812-3456-7890',
            'qurban_name' => 'Ahmad Wijaya & Keluarga',
            'reminder_enabled' => true,
            'reminder_frequency' => 'bulanan',
            'status' => 'active',
            'created_at' => '2026-01-15 08:30:00',
        ]);

        // Deposits with Payment records
        $deposits = [
            ['amount' => 350000, 'method' => 'mandiri', 'payment_type' => 'bank_transfer', 'status' => 'paid', 'date' => '2026-01-15 08:30:21'],
            ['amount' => 300000, 'method' => 'xendit',  'payment_type' => 'xendit',        'status' => 'paid', 'date' => '2026-02-10 11:20:34'],
            ['amount' => 500000, 'method' => 'bca',     'payment_type' => 'bank_transfer', 'status' => 'paid', 'date' => '2026-03-28 16:45:12'],
            ['amount' => 250000, 'method' => 'bri',     'payment_type' => 'bank_transfer', 'status' => 'pending', 'date' => '2026-04-15 09:15:23'],
            ['amount' => 100000, 'method' => 'mandiri', 'payment_type' => 'bank_transfer', 'status' => 'pending', 'date' => '2026-05-22 10:15:30'],
        ];

        foreach ($deposits as $dep) {
            $trxId = 'QRB' . str_replace(['-', ' ', ':'], '', $dep['date']);

            QurbanSavingsDeposit::create([
                'qurban_saving_id' => $saving->id,
                'transaction_id' => $trxId,
                'amount' => $dep['amount'],
                'payment_method' => $dep['method'],
                'status' => $dep['status'],
                'created_at' => $dep['date'],
            ]);

            $uniqueCode = $dep['payment_type'] === 'bank_transfer' ? rand(100, 999) : null;

            Payment::create([
                'external_id' => $trxId,
                'transaction_type' => 'qurban_tabungan',
                'user_id' => $user->id,
                'qurban_saving_id' => $saving->id,
                'unique_code' => $uniqueCode,
                'customer_name' => 'Ahmad Wijaya',
                'customer_phone' => '0812-3456-7890',
                'payment_type' => $dep['payment_type'],
                'amount' => $dep['amount'],
                'admin_fee' => 0,
                'total' => $dep['amount'] + ($uniqueCode ?? 0),
                'payment_method' => $dep['method'],
                'status' => $dep['status'],
                'paid_at' => $dep['status'] === 'paid' ? $dep['date'] : null,
                'expired_at' => now()->addHours(24),
                'created_at' => $dep['date'],
            ]);
        }
    }
}
