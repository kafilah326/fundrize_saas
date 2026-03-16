# Plan: Unique Code Total Consistency Fix — QurbanOrder & QurbanSavingsDeposit

**Status**: Ready for Execution  
**Created**: 2026-03-16  
**Scope**: Memperbaiki inkonsistensi penyimpanan `total` (yang sudah include unique_code) di tabel `qurban_orders` dan `qurban_savings_deposits`. Donasi Program dan Zakat sudah benar.

---

## Latar Belakang

Aplikasi menggunakan **kode unik (unique_code: 100-999)** yang ditambahkan ke nominal transfer bank agar mudah diidentifikasi. Formula:

```
total = amount + admin_fee (0) + unique_code
```

| Tabel | Field `total` ada? | unique_code masuk? |
|-------|-------------------|--------------------|
| `donations` | ✅ | ✅ |
| `zakat_transactions` | ✅ | ✅ |
| `qurban_orders` | ❌ BUG | ❌ BUG |
| `qurban_savings_deposits` | ❌ BUG | ❌ BUG |
| `payments` (pusat) | ✅ | ✅ |

**Dampak bug**: Ketika user membayar Qurban via transfer bank, nominal total yang mereka bayar (termasuk kode unik) tidak tersimpan di record qurban itu sendiri — hanya tersimpan di tabel `payments` pusat. Ini menyebabkan laporan/tampilan yang bergantung langsung pada tabel qurban menampilkan nominal yang tidak sesuai dengan yang dibayar user.

---

## Decision Log

| Keputusan | Pilihan | Alasan |
|-----------|---------|--------|
| `admin_fee` di qurban tables | **TIDAK ditambahkan** | Semua consumer JOIN ke `payments` untuk admin_fee; tidak ada consumer langsung yang butuhkan |
| Kolom `total` nullable | **Nullable** | Data lama belum punya total; COALESCE(total, amount) untuk backward compat |
| Backfill scope | **Hanya status `paid`** | Expired/failed: total tidak relevan karena pembayaran tidak terjadi |
| FundraiserDashboard update | **Tahap 2 (tidak di sini)** | Tunggu backfill selesai dulu |
| MaintenanceFee.php | **Tidak disentuh** | Bukan scope bug fix ini |
| Urutan Tahap | **Migration → Model → PaymentMethod → Backfill → QA** | Dependen satu sama lain |

## Scope

**IN (5 file + 2 migration baru):**
- `database/migrations/[timestamp]_add_total_to_qurban_orders_table.php` — CREATE
- `database/migrations/[timestamp]_add_total_to_qurban_savings_deposits_table.php` — CREATE
- `app/Models/QurbanOrder.php` — EDIT (`total` ke fillable + casts)
- `app/Models/QurbanSavingsDeposit.php` — EDIT (`total` ke fillable + casts)
- `app/Livewire/Front/PaymentMethod.php` — EDIT (isi `total` saat create QurbanOrder & QurbanSavingsDeposit)
- Backfill command atau artisan tinker script

**OUT (tidak disentuh):**
- `app/Livewire/Admin/FundraiserDashboard.php` — ditunda ke Tahap 2
- `app/Http/Controllers/MaintenanceFee.php` — tidak berkaitan
- `app/Services/WhatsAppNotificationService.php` — sudah benar
- `app/Http/Controllers/XenditWebhookController.php` — webhook tidak perlu diubah
- Kalkulasi komisi — sudah by design dari `amount` saja
- `saved_amount` increment — separate concern

---

## Tasks

### TASK 1 — Migration: tambah kolom `total` ke `qurban_orders`
**Action**: Buat migration baru  
**Command**: `php artisan make:migration add_total_to_qurban_orders_table`

**Isi migration:**
```php
public function up(): void
{
    Schema::table('qurban_orders', function (Blueprint $table) {
        $table->decimal('total', 15, 2)->nullable()->after('amount');
    });
}

public function down(): void
{
    Schema::table('qurban_orders', function (Blueprint $table) {
        $table->dropColumn('total');
    });
}
```

**QA**: `php artisan migrate --pretend | grep "qurban_orders"` → tampilkan `alter table add column total`

---

### TASK 2 — Migration: tambah kolom `total` ke `qurban_savings_deposits`
**Action**: Buat migration baru  
**Command**: `php artisan make:migration add_total_to_qurban_savings_deposits_table`

**Isi migration:**
```php
public function up(): void
{
    Schema::table('qurban_savings_deposits', function (Blueprint $table) {
        $table->decimal('total', 15, 2)->nullable()->after('amount');
    });
}

public function down(): void
{
    Schema::table('qurban_savings_deposits', function (Blueprint $table) {
        $table->dropColumn('total');
    });
}
```

**QA**: `php artisan migrate --pretend | grep "qurban_savings_deposits"` → tampilkan alter column

---

### TASK 3 — Jalankan migration
**Command**: `php artisan migrate --force`

**QA**: `php artisan tinker --execute="echo DB::getSchemaBuilder()->hasColumn('qurban_orders', 'total') ? 'OK' : 'FAIL';"` → `OK`  
**QA**: `php artisan tinker --execute="echo DB::getSchemaBuilder()->hasColumn('qurban_savings_deposits', 'total') ? 'OK' : 'FAIL';"` → `OK`

---

### TASK 4 — Update `QurbanOrder` Model
**File**: `app/Models/QurbanOrder.php`  
**Change**: Tambah `'total'` ke array `$fillable` dan tambah ke `$casts`.

**Target fillable** (tambahkan setelah `'amount'`):
```php
protected $fillable = [
    // ... existing fields ...
    'amount',
    'total',      // ← TAMBAH INI
    // ... rest ...
];
```

**Target casts:**
```php
protected $casts = [
    'amount' => 'decimal:2',
    'total'  => 'decimal:2',  // ← TAMBAH INI
];
```

**QA**: `php artisan tinker --execute="echo in_array('total', (new App\Models\QurbanOrder)->getFillable()) ? 'OK' : 'FAIL';"` → `OK`

---

### TASK 5 — Update `QurbanSavingsDeposit` Model
**File**: `app/Models/QurbanSavingsDeposit.php`  
**Change**: Tambah `'total'` ke `$fillable` dan `$casts`.

**Target fillable** (tambahkan setelah `'amount'`):
```php
protected $fillable = [
    // ... existing fields ...
    'amount',
    'total',      // ← TAMBAH INI
    // ... rest ...
];
```

**Target casts:**
```php
protected $casts = [
    'amount' => 'decimal:2',
    'total'  => 'decimal:2',  // ← TAMBAH INI
];
```

**QA**: `php artisan tinker --execute="echo in_array('total', (new App\Models\QurbanSavingsDeposit)->getFillable()) ? 'OK' : 'FAIL';"` → `OK`

---

### TASK 6 — Update `PaymentMethod.php` — QurbanOrder::create
**File**: `app/Livewire/Front/PaymentMethod.php`  
**Location**: Block `QurbanOrder::create(...)` (sekitar line 255)  
**Change**: Tambah `'total' => $finalTotal` ke array create.

**Pattern yang dicari:**
```php
QurbanOrder::create([
    'transaction_id' => $transactionId,
    // ... other fields ...
    'amount' => $this->amount,
    'payment_method' => $this->paymentMethod,
    'status' => 'pending',
]);
```

**Target:**
```php
QurbanOrder::create([
    'transaction_id' => $transactionId,
    // ... other fields ...
    'amount' => $this->amount,
    'total'  => $finalTotal,    // ← TAMBAH INI
    'payment_method' => $this->paymentMethod,
    'status' => 'pending',
]);
```

**QA**: `grep -n "'total'" app/Livewire/Front/PaymentMethod.php | grep -i "qurban"` → verify line ada

---

### TASK 7 — Update `PaymentMethod.php` — QurbanSavingsDeposit::create
**File**: `app/Livewire/Front/PaymentMethod.php`  
**Location**: Block `QurbanSavingsDeposit::create(...)` (sekitar line 320)  
**Change**: Tambah `'total' => $finalTotal` ke array create.

**Pattern yang dicari:**
```php
QurbanSavingsDeposit::create([
    'qurban_saving_id' => $this->qurbanSavingId,
    'transaction_id'   => $transactionId,
    'amount'           => $this->amount,
    'payment_method'   => $this->paymentMethod,
    'status'           => 'pending',
]);
```

**Target:**
```php
QurbanSavingsDeposit::create([
    'qurban_saving_id' => $this->qurbanSavingId,
    'transaction_id'   => $transactionId,
    'amount'           => $this->amount,
    'total'            => $finalTotal,    // ← TAMBAH INI
    'payment_method'   => $this->paymentMethod,
    'status'           => 'pending',
]);
```

**QA**: `grep -n "'total'" app/Livewire/Front/PaymentMethod.php` → minimal 4 occurrences (Donation, Zakat, QurbanOrder, QurbanSavingsDeposit)

---

### TASK 8 — Backfill: isi `total` untuk data lama
**Goal**: Update semua `qurban_orders` dan `qurban_savings_deposits` yang sudah `paid` dengan nilai `total` dari tabel `payments`.

**Script** (jalankan via `php artisan tinker` atau buat Artisan command `php artisan backfill:qurban-total`):

```php
// Dry run terlebih dahulu
$orphanOrders = DB::select("
    SELECT COUNT(*) as c FROM qurban_orders
    WHERE status = 'paid' AND transaction_id NOT IN (SELECT external_id FROM payments)
");
echo "Orphan orders (no payment record): " . $orphanOrders[0]->c . "\n";

$orphanDeposits = DB::select("
    SELECT COUNT(*) as c FROM qurban_savings_deposits
    WHERE status = 'paid' AND transaction_id NOT IN (SELECT external_id FROM payments)
");
echo "Orphan deposits (no payment record): " . $orphanDeposits[0]->c . "\n";

// Preview: berapa yang akan di-update
$ordersToUpdate = DB::select("
    SELECT COUNT(*) as c FROM qurban_orders qo
    JOIN payments p ON p.external_id = qo.transaction_id
    WHERE qo.status = 'paid' AND qo.total IS NULL
");
echo "QurbanOrders to backfill: " . $ordersToUpdate[0]->c . "\n";

$depositsToUpdate = DB::select("
    SELECT COUNT(*) as c FROM qurban_savings_deposits qsd
    JOIN payments p ON p.external_id = qsd.transaction_id
    WHERE qsd.status = 'paid' AND qsd.total IS NULL
");
echo "QurbanSavingsDeposits to backfill: " . $depositsToUpdate[0]->c . "\n";
```

**Setelah dry run konfirmasi, jalankan UPDATE:**

```php
// Backfill qurban_orders
$updatedOrders = DB::statement("
    UPDATE qurban_orders qo
    JOIN payments p ON p.external_id = qo.transaction_id
    SET qo.total = p.total
    WHERE qo.status = 'paid' AND qo.total IS NULL
");
echo "Updated qurban_orders: done\n";

// Backfill qurban_savings_deposits
$updatedDeposits = DB::statement("
    UPDATE qurban_savings_deposits qsd
    JOIN payments p ON p.external_id = qsd.transaction_id
    SET qsd.total = p.total
    WHERE qsd.status = 'paid' AND qsd.total IS NULL
");
echo "Updated qurban_savings_deposits: done\n";
```

**Guardrail**: Jalankan dry-run dulu, screenshot/catat jumlah record, baru jalankan UPDATE.

---

## Final Verification Wave (QA Checks)

```bash
# QA-1: Migration sudah jalan — kolom total ada di qurban_orders
php artisan tinker --execute="echo DB::getSchemaBuilder()->hasColumn('qurban_orders', 'total') ? 'OK' : 'FAIL';"
# ASSERT: OK

# QA-2: Migration sudah jalan — kolom total ada di qurban_savings_deposits
php artisan tinker --execute="echo DB::getSchemaBuilder()->hasColumn('qurban_savings_deposits', 'total') ? 'OK' : 'FAIL';"
# ASSERT: OK

# QA-3: QurbanOrder fillable punya 'total'
php artisan tinker --execute="echo in_array('total', (new App\Models\QurbanOrder)->getFillable()) ? 'OK' : 'FAIL';"
# ASSERT: OK

# QA-4: QurbanSavingsDeposit fillable punya 'total'
php artisan tinker --execute="echo in_array('total', (new App\Models\QurbanSavingsDeposit)->getFillable()) ? 'OK' : 'FAIL';"
# ASSERT: OK

# QA-5: PaymentMethod.php mengisi total di QurbanOrder dan QurbanSavingsDeposit
php -r "echo substr_count(file_get_contents('app/Livewire/Front/PaymentMethod.php'), \"'total' => \\\$finalTotal\");"
# ASSERT: >= 4 (Donation, ZakatTransaction, QurbanOrder, QurbanSavingsDeposit)

# QA-6: Setelah backfill — tidak ada paid QurbanOrder dengan total NULL
php artisan tinker --execute="echo \App\Models\QurbanOrder::where('status','paid')->whereNull('total')->count() === 0 ? 'OK' : 'FAIL';"
# ASSERT: OK

# QA-7: Setelah backfill — tidak ada paid QurbanSavingsDeposit dengan total NULL
php artisan tinker --execute="echo \App\Models\QurbanSavingsDeposit::where('status','paid')->whereNull('total')->count() === 0 ? 'OK' : 'FAIL';"
# ASSERT: OK

# QA-8: Konsistensi data — total di qurban_orders = total di payments (max diff harus 0)
php artisan tinker --execute="
\$mismatch = DB::select('
    SELECT COUNT(*) as c FROM qurban_orders qo
    JOIN payments p ON p.external_id = qo.transaction_id
    WHERE qo.total IS NOT NULL AND ABS(qo.total - p.total) > 0.01
');
echo \$mismatch[0]->c === 0 ? 'OK' : 'FAIL ('.\$mismatch[0]->c.' mismatches)';
"
# ASSERT: OK

# QA-9: Konsistensi data — total di qurban_savings_deposits = total di payments
php artisan tinker --execute="
\$mismatch = DB::select('
    SELECT COUNT(*) as c FROM qurban_savings_deposits qsd
    JOIN payments p ON p.external_id = qsd.transaction_id
    WHERE qsd.total IS NOT NULL AND ABS(qsd.total - p.total) > 0.01
');
echo \$mismatch[0]->c === 0 ? 'OK' : 'FAIL ('.\$mismatch[0]->c.' mismatches)';
"
# ASSERT: OK

# QA-10: PHP syntax clean
php -l app/Models/QurbanOrder.php && php -l app/Models/QurbanSavingsDeposit.php && php -l app/Livewire/Front/PaymentMethod.php
# ASSERT: No syntax errors

# QA-11: Regression — Donation dan ZakatTransaction masih benar (total tidak berubah)
php -r "
\$pm = file_get_contents('app/Livewire/Front/PaymentMethod.php');
echo (strpos(\$pm, 'Donation::create') !== false && strpos(\$pm, 'ZakatTransaction::create') !== false) ? 'OK' : 'FAIL';
"
# ASSERT: OK
```

---

## Execution Order

1. **TASK 1** (migration qurban_orders) — create file
2. **TASK 2** (migration qurban_savings_deposits) — create file
3. **TASK 3** (migrate) — jalankan `php artisan migrate`
4. **TASK 4** (QurbanOrder model) — edit fillable + casts
5. **TASK 5** (QurbanSavingsDeposit model) — edit fillable + casts
6. **TASK 6** (PaymentMethod — QurbanOrder::create) — edit
7. **TASK 7** (PaymentMethod — QurbanSavingsDeposit::create) — edit
8. **TASK 8** (Backfill) — dry-run, review, then execute UPDATE
9. **Final Verification Wave** (QA-1 s/d QA-11)

---

## Guardrails (dari Metis)

- JANGAN ubah `MaintenanceFee.php` — kalkulasi system fee dari `amount` tidak diubah
- JANGAN ubah cara `saved_amount` diincrement di webhook — separate concern
- JANGAN update `FundraiserDashboard.php` sekarang — tunggu semua data ter-backfill (Tahap 2)
- WAJIB dry-run backfill sebelum UPDATE aktual
- Kolom `total` harus `nullable()` — backward compatible dengan data lama expired/failed
- Semua consumer yang belum diupdate harus pakai `COALESCE(total, amount)` jika perlu nilai total
