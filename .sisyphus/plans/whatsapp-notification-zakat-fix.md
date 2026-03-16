# Plan: WhatsApp Notification — Zakat Fix & Admin Template Improvements

**Status**: Ready for Execution  
**Created**: 2026-03-16  
**Scope**: Fix pesan WA zakat (salah type/label/variables), tambah type 'zakat' ke UI admin `/admin/whatsapp-template`, enable semua event (payment_success, payment_expired), tambah seeder default template zakat.

---

## Decision Log

| Decision | Choice | Rationale |
|----------|--------|-----------|
| Tipe template zakat | Satu type `'zakat'` | Cukup satu type, handle fitrah & maal via `{{detail_zakat}}` |
| Event unlock | Enable untuk SEMUA type | payment_success & payment_expired di-enable global |
| Seeder | Tambahkan | Default template zakat agar tidak hanya fallback hardcoded |
| Migration | TIDAK perlu | Kolom `type` & `event` sudah `string`, bukan enum |
| Data source zakat | `checkout_data` | Sudah diisi oleh ZakatIndex.php; tidak perlu query tambahan |

## Scope

**IN:**
- `app/Services/WhatsAppNotificationService.php` — fix 4 method + fallback
- `app/Livewire/Admin/WhatsappTemplate.php` — fix validasi + getSampleData + getAvailableParameters
- `resources/views/livewire/admin/whatsapp-template.blade.php` — fix UI (cards, filter, modal, badge, enable events)
- `app/Models/WhatsappTemplate.php` — tambah label 'zakat' di accessor (sudah ada ucfirst fallback, tapi eksplisit lebih baik)
- `database/seeders/WhatsappTemplateSeeder.php` — buat/update seeder dengan default template zakat (3 event)

**OUT:**
- `app/Http/Controllers/PakasirWebhookController.php` — tidak disentuh (sudah handle zakat benar)
- `app/Livewire/Front/` — tidak disentuh
- Migration baru — tidak perlu
- Test file — tinker acceptance criteria sudah cukup
- `app/Models/ZakatTransaction.php` — tidak disentuh

---

## File Map

```
app/Services/WhatsAppNotificationService.php   ← EDIT (4 method fixes + fallback)
app/Livewire/Admin/WhatsappTemplate.php        ← EDIT (validation + sample + params)
app/Models/WhatsappTemplate.php                ← EDIT (tambah explicit 'zakat' label)
resources/views/livewire/admin/
  whatsapp-template.blade.php                  ← EDIT (cards, filter, modal, badges, events)
database/seeders/WhatsappTemplateSeeder.php    ← CREATE/EDIT (default zakat templates)
```

---

## Tasks

### TASK 1 — Fix `WhatsAppNotificationService::getTemplateType()`
**File**: `app/Services/WhatsAppNotificationService.php`  
**Method**: `getTemplateType(Payment $payment): string`  
**Change**: Tambah case `'zakat' => 'zakat'` di match expression, **sebelum** `default`.

**Current (broken):**
```php
match ($payment->transaction_type) {
    'program'         => 'donasi',
    'qurban_langsung' => 'qurban',
    'qurban_tabungan' => 'tabungan_qurban',
    default           => 'donasi',   // ← zakat salah mapping ke sini
};
```

**Target:**
```php
match ($payment->transaction_type) {
    'program'         => 'donasi',
    'qurban_langsung' => 'qurban',
    'qurban_tabungan' => 'tabungan_qurban',
    'zakat'           => 'zakat',
    default           => 'donasi',
};
```

**QA**: `php artisan tinker --execute="..."` — assert output `'zakat'` (lihat script QA di section bawah).

---

### TASK 2 — Fix `WhatsAppNotificationService::getTransactionLabel()`
**File**: `app/Services/WhatsAppNotificationService.php`  
**Method**: `getTransactionLabel(Payment $payment): string`  
**Change**: Tambah case `'zakat' => 'Zakat'` di match expression.

**Current (broken):**
```php
match ($payment->transaction_type) {
    'program'         => 'Donasi Program',
    'qurban_langsung' => 'Qurban Langsung',
    'qurban_tabungan' => 'Tabungan Qurban',
    default           => 'Donasi',   // ← zakat tampil sebagai 'Donasi'
};
```

**Target:**
```php
match ($payment->transaction_type) {
    'program'         => 'Donasi Program',
    'qurban_langsung' => 'Qurban Langsung',
    'qurban_tabungan' => 'Tabungan Qurban',
    'zakat'           => 'Zakat',
    default           => 'Donasi',
};
```

**QA**: tinker assert output `'Zakat'` (NOT `'Donasi'`).

---

### TASK 3 — Fix `WhatsAppNotificationService::buildVariables()` — tambah block zakat
**File**: `app/Services/WhatsAppNotificationService.php`  
**Method**: `buildVariables(Payment $payment, string $event): array`  
**Change**: Tambah `elseif` block untuk `'zakat'` setelah block `'qurban_tabungan'`.

**Data source**: `$checkout = $payment->checkout_data` sudah berisi:
- Selalu: `zakat_type` ('fitrah'|'maal'), `name`, `phone`
- Fitrah: `jumlah_jiwa` (int)
- Maal: `total_harta`, `nisab_at_time`, `calculated_zakat`

**Block yang ditambahkan** (ikuti pola existing):
```php
} elseif ($payment->transaction_type === 'zakat') {
    $zakatType = $checkout['zakat_type'] ?? 'maal';
    $jenisZakat = $zakatType === 'fitrah' ? 'Zakat Fitrah' : 'Zakat Mal';

    if ($zakatType === 'fitrah') {
        $jumlahJiwa = $checkout['jumlah_jiwa'] ?? 1;
        $detailZakat = $jumlahJiwa . ' Jiwa';
    } else {
        $totalHarta = $checkout['total_harta'] ?? 0;
        $detailZakat = 'Harta Rp ' . number_format($totalHarta, 0, ',', '.');
    }

    $variables['{{jenis_zakat}}']  = $jenisZakat;
    $variables['{{detail_zakat}}'] = $detailZakat;
    $variables['{{jumlah_jiwa}}']  = $zakatType === 'fitrah' ? ($checkout['jumlah_jiwa'] ?? '-') : '-';
    $variables['{{total_harta}}']  = $zakatType === 'maal'
        ? 'Rp ' . number_format($checkout['total_harta'] ?? 0, 0, ',', '.')
        : '-';
}
```

**QA**: tinker — untuk fitrah assert output array_keys mengandung `'{{jenis_zakat}}'` dan `'{{jumlah_jiwa}}'`.

---

### TASK 4 — Fix `WhatsAppNotificationService::getTransactionDetail()`
**File**: `app/Services/WhatsAppNotificationService.php`  
**Method**: `getTransactionDetail(Payment $payment): string`  
**Change**: Tambah case/branch untuk `'zakat'` yang return string ringkasan.

**Pattern** (ikuti pola existing):
```php
} elseif ($payment->transaction_type === 'zakat') {
    $checkout  = $payment->checkout_data;
    $zakatType = $checkout['zakat_type'] ?? 'maal';
    if ($zakatType === 'fitrah') {
        $jiwa = $checkout['jumlah_jiwa'] ?? 1;
        return "Zakat Fitrah — {$jiwa} Jiwa";
    }
    return 'Zakat Mal';
}
```

**QA**: tinker — assert return bukan `''` (string kosong) untuk payment type 'zakat'.

---

### TASK 5 — Fix fallback messages di `WhatsAppNotificationService`
**File**: `app/Services/WhatsAppNotificationService.php`  
**Scope**: Method `fallbackPaymentExpiredMessage()` (dan jika ada fallback lain yang hardcode "donasi/qurban").

**Change**: Ganti teks hardcoded yang menyebut "donasi/qurban" dengan variabel `$typeLabel` yang sudah di-set dari `getTransactionLabel()`:
- Pastikan `$typeLabel` dipakai (bukan string literal 'donasi') di semua fallback message methods.
- Fallback expired harus netral: gunakan `$typeLabel` agar zakat tampil sebagai "Zakat" bukan "Donasi/Qurban".

**QA**: Tinker — instantiate Payment dengan transaction_type='zakat', panggil fallback method via Reflection, assert output tidak mengandung kata "Donasi" atau "Qurban".

---

### TASK 6 — Fix `WhatsappTemplate` Livewire — validation rules
**File**: `app/Livewire/Admin/WhatsappTemplate.php`  
**Change**: Update dua validation rules:

```php
// BEFORE:
'type'  => 'required|in:donasi,qurban,tabungan_qurban',
'event' => 'required|in:payment_created',

// AFTER:
'type'  => 'required|in:donasi,qurban,tabungan_qurban,zakat',
'event' => 'required|in:payment_created,payment_success,payment_expired',
```

**QA**: tinker — `$c->getRules()['type']` contains `'zakat'`; `$c->getRules()['event']` contains `'payment_success'` dan `'payment_expired'`.

---

### TASK 7 — Fix `WhatsappTemplate` Livewire — `getSampleData()`
**File**: `app/Livewire/Admin/WhatsappTemplate.php`  
**Method**: `getSampleData(): array` (atau method yang menghasilkan data preview)  
**Change**: Tambah branch `'zakat'` setelah branch `'tabungan_qurban'`.

**Sample data yang ditambahkan** (ikuti pola existing):
```php
} elseif ($type === 'zakat') {
    return array_merge($base, [
        '{{jenis_zakat}}'  => 'Zakat Fitrah',
        '{{detail_zakat}}' => '3 Jiwa',
        '{{jumlah_jiwa}}'  => '3',
        '{{total_harta}}'  => '-',
        '{{tipe_transaksi}}' => 'Zakat',
    ]);
}
```

Variabel `$base` mengikuti pola existing (berisi {{nama}}, {{no_transaksi}}, {{jumlah}}, {{total}}, dll.).

**QA**: Admin buka preview modal, pilih type 'zakat' — placeholder terganti dengan sample data (verifikasi visual di browser, atau tinker reflection).

---

### TASK 8 — Fix `WhatsappTemplate` Livewire — `getAvailableParametersProperty()`
**File**: `app/Livewire/Admin/WhatsappTemplate.php`  
**Property/Method**: computed property `$availableParameters` atau `getAvailableParametersProperty()`  
**Change**: Tambah branch `'zakat'` setelah branch `'tabungan_qurban'`.

**Parameters yang ditambahkan** (ikuti pola existing, return array of param entries):
```php
} elseif ($this->type === 'zakat') {
    $typeParams = [
        ['key' => '{{jenis_zakat}}',   'label' => 'Jenis Zakat (Fitrah/Mal)'],
        ['key' => '{{detail_zakat}}',  'label' => 'Detail Zakat (ringkasan)'],
        ['key' => '{{jumlah_jiwa}}',   'label' => 'Jumlah Jiwa (Fitrah)'],
        ['key' => '{{total_harta}}',   'label' => 'Total Harta (Mal)'],
        ['key' => '{{tipe_transaksi}}','label' => 'Tipe Transaksi'],
    ];
}
```

**QA**: UI — saat type 'zakat' dipilih di modal, tombol insert parameter menampilkan 5 parameter zakat.

---

### TASK 9 — Fix `WhatsappTemplate` Model — tambah explicit 'zakat' label
**File**: `app/Models/WhatsappTemplate.php`  
**Method**: `getTypeLabelAttribute(): string`  
**Change**: Tambah case `'zakat' => 'Zakat'` secara eksplisit (meskipun `default => ucfirst(...)` sudah handle, explicit lebih aman):

```php
// BEFORE (simplified):
match ($this->type) {
    'donasi'          => 'Donasi Program',
    'qurban'          => 'Qurban',
    'tabungan_qurban' => 'Tabungan Qurban',
    default           => ucfirst(str_replace('_', ' ', $this->type)),
};

// AFTER:
match ($this->type) {
    'donasi'          => 'Donasi Program',
    'qurban'          => 'Qurban',
    'tabungan_qurban' => 'Tabungan Qurban',
    'zakat'           => 'Zakat',
    default           => ucfirst(str_replace('_', ' ', $this->type)),
};
```

**QA**: tinker — `(new WhatsappTemplate(['type' => 'zakat']))->type_label` === `'Zakat'`.

---

### TASK 10 — Fix blade view — info cards (`$types` array)
**File**: `resources/views/livewire/admin/whatsapp-template.blade.php`  
**Section**: PHP array `$types` di bagian info cards (line ~8-12)  
**Change**: Tambah entry `'zakat'` ke array:

```php
// TAMBAHKAN setelah 'tabungan_qurban' entry:
'zakat' => [
    'label' => 'Zakat',
    'icon'  => 'fa-star-and-crescent',
    'color' => 'amber',
],
```

Pastikan card amber/yellow Tailwind class tersedia (misal: `bg-amber-50`, `text-amber-600`, `border-amber-200`). Ikuti pola card yang sudah ada untuk blue/green/purple.

**QA**: Halaman `/admin/whatsapp-template` menampilkan 4 info cards (Donasi, Qurban, Tabungan Qurban, Zakat).

---

### TASK 11 — Fix blade view — filter dropdown
**File**: `resources/views/livewire/admin/whatsapp-template.blade.php`  
**Section**: Filter type `<select>` (line ~60-63)  
**Change**: Tambah `<option value="zakat">Zakat</option>` sebagai opsi filter.

**QA**: Dropdown filter type memiliki 4 pilihan (termasuk Zakat).

---

### TASK 12 — Fix blade view — modal create/edit type select
**File**: `resources/views/livewire/admin/whatsapp-template.blade.php`  
**Section**: Modal form type `<select>` (line ~260-262)  
**Change**: Tambah `<option value="zakat">Zakat</option>`.

**QA**: Modal create template memiliki option type 'Zakat'.

---

### TASK 13 — Fix blade view — enable event `payment_success` & `payment_expired`
**File**: `resources/views/livewire/admin/whatsapp-template.blade.php`  
**Section**: Modal form event `<select>` (line ~272-277)  
**Change**:
- Hapus attribute `disabled` dari `<option value="payment_success">`
- Hapus attribute `disabled` dari `<option value="payment_expired">`
- Hapus teks `(Segera Hadir)` dari kedua option label

**Before:**
```html
<option value="payment_success" disabled>Pembayaran Berhasil (Segera Hadir)</option>
<option value="payment_expired" disabled>Pembayaran Expired (Segera Hadir)</option>
```

**After:**
```html
<option value="payment_success">Pembayaran Berhasil</option>
<option value="payment_expired">Pembayaran Expired</option>
```

**QA**: Tinker — `grep 'payment_success" disabled' whatsapp-template.blade.php` → count 0.

---

### TASK 14 — Fix blade view — badge warna untuk type 'zakat'
**File**: `resources/views/livewire/admin/whatsapp-template.blade.php`  
**Section**: Badge/pill color logic di tabel template list (line ~139-143)  
**Change**: Tambah kondisi untuk 'zakat' → amber/yellow. Ikuti pola `@if`/`@elseif` yang sudah ada.

**Pattern target:**
```blade
@if ($template->type === 'donasi') ... class blue ...
@elseif ($template->type === 'qurban') ... class green ...
@elseif ($template->type === 'tabungan_qurban') ... class purple ...
@elseif ($template->type === 'zakat') ... class amber ...
@else ... class gray ...
@endif
```

**QA**: Template dengan type 'zakat' tampil dengan badge amber/yellow di tabel.

---

### TASK 15 — Buat seeder default template zakat
**File**: `database/seeders/WhatsappTemplateSeeder.php`  
**Action**: Buat file baru jika belum ada, atau tambahkan ke seeder yang ada.

**Template yang di-seed** (3 event × 1 type 'zakat' = 3 records):

**1. payment_created:**
```
name: 'Default Zakat - Pembayaran Dibuat'
type: 'zakat'
event: 'payment_created'
content: |
  Assalamu'alaikum {{nama}} 🌙

  Terima kasih, *{{jenis_zakat}}* Anda telah kami terima.

  📋 *Detail Transaksi:*
  • No. Transaksi: {{no_transaksi}}
  • Jenis: {{jenis_zakat}} ({{detail_zakat}})
  • Jumlah: {{jumlah}}
  • Total Bayar: {{total}}

  ⏳ Segera selesaikan pembayaran sebelum: {{batas_waktu}}
  🔗 Link Pembayaran: {{link_pembayaran}}

  Semoga zakat Anda membawa keberkahan.
  _{{yayasan}}_
is_active: true
```

**2. payment_success:**
```
name: 'Default Zakat - Pembayaran Berhasil'
type: 'zakat'
event: 'payment_success'
content: |
  Assalamu'alaikum {{nama}} ✅

  *Pembayaran {{jenis_zakat}} Anda telah berhasil!*

  📋 *Detail:*
  • No. Transaksi: {{no_transaksi}}
  • Jenis: {{jenis_zakat}} ({{detail_zakat}})
  • Total Dibayar: {{total}}

  Jazakallahu Khairan atas zakat Anda 🤲
  Semoga menjadi amal yang diterima oleh Allah SWT.

  _{{yayasan}}_
is_active: true
```

**3. payment_expired:**
```
name: 'Default Zakat - Pembayaran Expired'
type: 'zakat'
event: 'payment_expired'
content: |
  Assalamu'alaikum {{nama}} ⚠️

  Transaksi *{{jenis_zakat}}* Anda telah *kedaluwarsa*.

  📋 *Detail:*
  • No. Transaksi: {{no_transaksi}}
  • Jenis: {{jenis_zakat}} ({{detail_zakat}})
  • Jumlah: {{total}}

  Silakan lakukan transaksi zakat baru melalui platform kami.
  Kami siap membantu Anda menunaikan zakat. 🙏

  _{{yayasan}}_
is_active: true
```

**Implementation pattern** (gunakan `updateOrCreate` bukan `create` agar idempotent):
```php
WhatsappTemplate::updateOrCreate(
    ['name' => 'Default Zakat - Pembayaran Dibuat'],
    ['type' => 'zakat', 'event' => 'payment_created', 'content' => '...', 'is_active' => true]
);
```

**Daftarkan seeder** di `DatabaseSeeder.php` jika belum terdaftar:
```php
$this->call(WhatsappTemplateSeeder::class);
```

**QA**: `php artisan db:seed --class=WhatsappTemplateSeeder` → exit 0. `php artisan tinker --execute="echo App\Models\WhatsappTemplate::where('type','zakat')->count();"` → output `3`.

---

## Final Verification Wave

Jalankan seluruh QA checks berikut secara berurutan setelah semua task selesai:

```bash
# QA-1: getTemplateType zakat → 'zakat' (bukan 'donasi')
php artisan tinker --execute="
use App\Services\WhatsAppNotificationService;
use App\Models\Payment;
\$p = new Payment(['transaction_type' => 'zakat']);
\$svc = app(WhatsAppNotificationService::class);
\$ref = new ReflectionMethod(\$svc, 'getTemplateType');
\$ref->setAccessible(true);
echo \$ref->invoke(\$svc, \$p);
"
# ASSERT: output === 'zakat'

# QA-2: getTransactionLabel zakat → 'Zakat' (bukan 'Donasi')
php artisan tinker --execute="
use App\Services\WhatsAppNotificationService;
use App\Models\Payment;
\$p = new Payment(['transaction_type' => 'zakat']);
\$svc = app(WhatsAppNotificationService::class);
\$ref = new ReflectionMethod(\$svc, 'getTransactionLabel');
\$ref->setAccessible(true);
echo \$ref->invoke(\$svc, \$p);
"
# ASSERT: output === 'Zakat'

# QA-3: buildVariables — zakat fitrah ada {{jenis_zakat}} & {{jumlah_jiwa}}
php artisan tinker --execute="
use App\Services\WhatsAppNotificationService;
use App\Models\Payment;
\$p = new Payment([
    'transaction_type' => 'zakat',
    'customer_name'    => 'Test User',
    'external_id'      => 'ZKT-TEST-001',
    'amount'           => 30000,
    'total'            => 30000,
    'payment_type'     => 'bank_transfer',
    'checkout_data'    => ['zakat_type' => 'fitrah', 'jumlah_jiwa' => 3],
]);
\$svc = app(WhatsAppNotificationService::class);
\$ref = new ReflectionMethod(\$svc, 'buildVariables');
\$ref->setAccessible(true);
\$vars = \$ref->invoke(\$svc, \$p, 'payment_created');
echo implode(',', array_keys(\$vars));
"
# ASSERT: output contains '{{jenis_zakat}}' AND '{{jumlah_jiwa}}'

# QA-4: buildVariables — zakat maal ada {{total_harta}}
php artisan tinker --execute="
use App\Services\WhatsAppNotificationService;
use App\Models\Payment;
\$p = new Payment([
    'transaction_type' => 'zakat',
    'customer_name'    => 'Test User',
    'external_id'      => 'ZKT-TEST-002',
    'amount'           => 500000,
    'total'            => 500000,
    'payment_type'     => 'bank_transfer',
    'checkout_data'    => ['zakat_type' => 'maal', 'total_harta' => 20000000],
]);
\$svc = app(WhatsAppNotificationService::class);
\$ref = new ReflectionMethod(\$svc, 'buildVariables');
\$ref->setAccessible(true);
\$vars = \$ref->invoke(\$svc, \$p, 'payment_created');
echo \$vars['{{jenis_zakat}}'] . '|' . \$vars['{{jumlah_jiwa}}'];
"
# ASSERT: output === 'Zakat Mal|-'

# QA-5: Livewire validation menerima 'zakat' type
php artisan tinker --execute="
\$c = new App\Livewire\Admin\WhatsappTemplate();
echo \$c->getRules()['type'];
"
# ASSERT: output contains 'zakat'

# QA-6: Livewire validation menerima semua 3 event
php artisan tinker --execute="
\$c = new App\Livewire\Admin\WhatsappTemplate();
echo \$c->getRules()['event'];
"
# ASSERT: output contains 'payment_success' AND 'payment_expired'

# QA-7: Model type_label untuk 'zakat' → 'Zakat'
php artisan tinker --execute="
echo (new App\Models\WhatsappTemplate(['type' => 'zakat']))->type_label;
"
# ASSERT: output === 'Zakat'

# QA-8: Blade punya option zakat di filter & modal (min 2 occurrences)
php -r "echo substr_count(file_get_contents('resources/views/livewire/admin/whatsapp-template.blade.php'), 'value=\"zakat\"');"
# ASSERT: output >= 2

# QA-9: Blade — payment_success tidak disabled
php -r "echo substr_count(file_get_contents('resources/views/livewire/admin/whatsapp-template.blade.php'), 'payment_success\" disabled');"
# ASSERT: output === 0

# QA-10: Seeder berhasil dan ada 3 template zakat di DB
php artisan db:seed --class=WhatsappTemplateSeeder
php artisan tinker --execute="echo App\Models\WhatsappTemplate::where('type','zakat')->count();"
# ASSERT: output === 3

# QA-11: Regression — type mapping existing lainnya tidak rusak
php artisan tinker --execute="
use App\Services\WhatsAppNotificationService;
use App\Models\Payment;
\$svc = app(WhatsAppNotificationService::class);
\$ref = new ReflectionMethod(\$svc, 'getTemplateType');
\$ref->setAccessible(true);
foreach (['program'=>'donasi','qurban_langsung'=>'qurban','qurban_tabungan'=>'tabungan_qurban','zakat'=>'zakat'] as \$in => \$ex) {
    \$p = new Payment(['transaction_type' => \$in]);
    \$r = \$ref->invoke(\$svc, \$p);
    echo \$in.':'.(\$r===\$ex?'OK':'FAIL('.\$r.')').PHP_EOL;
}
"
# ASSERT: semua 4 baris output berakhir dengan ':OK'
```

---

## Execution Order

1. **TASK 1** → TASK 2 → TASK 3 → TASK 4 → TASK 5 (service fix, sequential, satu file)
2. **TASK 6** → TASK 7 → TASK 8 (Livewire component fix, satu file)
3. **TASK 9** (Model fix, minor)
4. **TASK 10** → TASK 11 → TASK 12 → TASK 13 → TASK 14 (blade view fix, satu file)
5. **TASK 15** (Seeder, satu file baru)
6. **Final Verification Wave** (QA-1 s/d QA-11)

---

## Guardrails (dari Metis)

- JANGAN tambah eager loading di `buildVariables()` — gunakan `checkout_data` yang sudah ada
- JANGAN refactor logic parameter combinations (event×type show/hide) — bukan scope ini
- JANGAN sentuh `PakasirWebhookController` — sudah handle zakat dengan benar
- JANGAN buat migration baru — type/event adalah kolom string biasa
- JANGAN split ke 'zakat_fitrah'/'zakat_maal' — sudah keputusan satu type 'zakat'
- Gunakan `updateOrCreate` di seeder agar idempotent (aman dijalankan berulang)
