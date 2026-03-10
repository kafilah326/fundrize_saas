# Plan: Zakat Distribution (Laporan Penyaluran Zakat)

**Goal**: 
1. Tambah section **Perolehan Zakat** dan **Laporan Penyaluran Zakat** di `/zakat` (frontend publik), setelah section niat & tentang hisab
2. Tambah tab **Laporan Penyaluran** di `/admin/zakat` dengan CRUD penyaluran zakat

**Tech Stack**: Laravel 12 + Livewire 4 + Tailwind CSS 4 + Alpine.js + Quill Editor

---

## Scope: IN
- Migration `zakat_distributions` table
- Model `ZakatDistribution`
- Admin tab "Laporan Penyaluran" dengan CRUD (title, amount, description via Quill, distribution_date)
- Frontend stat cards (Total Terkumpul, Bulan Ini, Total Transaksi)
- Frontend list penyaluran (accordion, limit 12)

## Scope: OUT
- Balance/overdraft validation
- File/image upload per distribusi
- Export CSV
- Soft deletes
- Auth gate pada list publik
- Changes ke `ProgramDistribution` / `ProgramManage`

---

## Key Decisions
- **Muzakki stat**: `Payment::count()` labeled "Total Transaksi Zakat" (bukan "Total Muzakki" karena anonymous)
- **Stats formula**: `sum(DB::raw('amount + COALESCE(unique_code, 0)'))` for consistency with admin
- **XSS**: Accept parity dengan ProgramDistribution (`{!! description !!}`) — admin-only input
- **Frontend pagination**: `limit(12)->get()` — no paginator in mobile UI
- **Date validation**: `required|date` only — no `after_or_equal:today` (admin butuh backdate)
- **title field**: KEEP — jadi identifier entri di list

---

## Final Verification Wave
- [x] `php artisan migrate --pretend` → no errors, `zakat_distributions` table appears
- [x] `php -l` on all modified PHP files → no syntax errors
- [x] `php artisan view:cache` → no blade compile errors
- [x] `curl -s http://localhost:8000/zakat | grep -i 'Terkumpul'` → section heading appears
- [x] `curl -s http://localhost:8000/admin/zakat | grep -i 'Laporan'` → tab button present

---

## TASK-1: Migration — Create zakat_distributions Table

**File**: `database/migrations/{timestamp}_create_zakat_distributions_table.php`

### Checklist
- [x] Create migration file via `php artisan make:migration create_zakat_distributions_table`
- [x] Migration `up()`: fields: `id`, `title` (string), `amount` (decimal 15,2), `description` (text), `distribution_date` (date), `timestamps()`
- [x] Migration `down()`: `dropIfExists('zakat_distributions')`
- [x] Run `php artisan migrate` — confirm table created
- [x] Verify `php artisan migrate:status` shows new migration as "Ran"

### QA
- [x] `php artisan migrate --pretend` → shows CREATE TABLE statement with correct columns

---

## TASK-2: Model — ZakatDistribution

**File**: `app/Models/ZakatDistribution.php`

### Checklist
- [x] Create model with: `namespace App\Models;`
- [x] `use HasFactory;`
- [x] `$fillable = ['title', 'amount', 'description', 'distribution_date']`
- [x] `$casts = ['amount' => 'decimal:2', 'distribution_date' => 'date']`
- [x] No relations needed (standalone table)

### QA
- [x] `php -l app/Models/ZakatDistribution.php` → no syntax errors
- [x] `php artisan tinker --execute="App\Models\ZakatDistribution::count();"` → returns 0 (not error)

---

## TASK-3: Admin Backend — ZakatList.php CRUD Methods

**File**: `app/Livewire/Admin/ZakatList.php`

### Checklist
- [x] Add `use App\Models\ZakatDistribution;` import
- [x] Add properties:
  ```php
  public bool $showDistributionModal = false;
  public bool $confirmingDistributionDeletion = false;
  public ?int $distributionId = null;
  public string $distributionTitle = '';
  public string $distributionAmount = '';
  public string $distributionDescription = '';
  public string $distributionDate = '';
  ```
- [x] Add method `createDistribution()`: reset form, `$showDistributionModal = true`
- [x] Add method `editDistribution(int $id)`: load record, populate props, `$showDistributionModal = true`
- [x] Add method `storeDistribution()`: validate → if `$distributionId` → update, else create; reset form; flash success; close modal
- [x] Add method `confirmDeleteDistribution(int $id)`: `$distributionId = $id; $confirmingDistributionDeletion = true;`
- [x] Add method `deleteDistribution()`: delete record, reset, flash success
- [x] Add method `resetDistributionForm()`: reset all distribution props, close modal and confirm flags
- [x] Modify `render()`: add `'distributions' => ZakatDistribution::latest()->paginate($this->perPage)` to data passed to view (load always — low volume data)

### QA
- [x] `php -l app/Livewire/Admin/ZakatList.php` → no syntax errors
- [x] `php artisan tinker --execute="(new App\Livewire\Admin\ZakatList)->mount();"` → no errors

---

## TASK-4: Admin Blade — Tab & CRUD UI

**File**: `resources/views/livewire/admin/zakat-list.blade.php`

### Checklist
- [x] Add tab button "Laporan Penyaluran" in the tab switcher section (after "Pengaturan" button), using same CSS class pattern as existing tabs:
- [x] Add `@elseif($activeTab === 'laporan')` block after the settings `@elseif` block, before `@endif`
- [x] Tab content: white card wrapper, header with title "Laporan Penyaluran Zakat" + "Tambah" button (`wire:click="createDistribution"`)
- [x] Table: columns = Judul, Tanggal, Jumlah, Aksi (Edit + Hapus)
- [x] Empty state: if no distributions, show centered text
- [x] Pagination: `{{ $distributions->links() }}` 
- [x] Distribution Modal (`wire:model="showDistributionModal"`):
  - [x] Title: "Tambah Penyaluran" / "Edit Penyaluran"
  - [x] Input: `distributionTitle` (text), `distributionDate` (date), `distributionAmount` (number with Rp prefix)
  - [x] Rich text: Quill editor following ProgramManage pattern
  - [x] Buttons: Cancel (close modal) + Save (`wire:click="storeDistribution"`)
  - [x] Loading indicator on Save button
- [x] Delete confirmation modal (`wire:model="confirmingDistributionDeletion"`):
  - [x] Text: "Hapus penyaluran ini?"
  - [x] Buttons: Batal + Hapus (red, `wire:click="deleteDistribution"`)

### QA
- [x] `php artisan view:cache` → no compile errors
- [x] No unclosed HTML tags (count `<div` vs `</div>` in new section)
- [x] Tab appears at `/admin/zakat?activeTab=laporan` without 500 error

---

## TASK-5: Frontend Backend — ZakatIndex.php Stats

**File**: `app/Livewire/Front/ZakatIndex.php`

### Checklist
- [x] Add `use App\Models\ZakatDistribution;` import
- [x] Add `use Illuminate\Support\Facades\DB;` import (if not already present)
- [x] Add public properties: `$totalCollected`, `$totalThisMonth`, `$totalTransactions`, `$zakatDistributions`
- [x] In `mount()`, calculate statistics using the specified query pattern
- [x] Load latest 12 zakat distributions in `mount()`

### QA
- [x] `php -l app/Livewire/Front/ZakatIndex.php` → no syntax errors
- [x] `php artisan tinker --execute="(new App\Livewire\Front\ZakatIndex)->mount();"` → no errors
- [x] Stats return numeric 0 (not null) when no zakat payments exist yet

---

## TASK-6: Frontend Blade — Perolehan & Penyaluran Sections

**File**: `resources/views/livewire/front/zakat-index.blade.php`

### Checklist
- [x] Insert Section A (stat cards) after collapsible section closing tag, before `</main>`
- [x] Insert Section B (distribution list) immediately after Section A
- [x] Verify `pb-32` on `<main>` is still sufficient — if sections overflow bottom CTA, change to `pb-40`
- [x] `@if($zakatDistributions && $zakatDistributions->isNotEmpty())` guard for Section B
- [x] Alpine.js `x-collapse` used for smooth accordion (check if Alpine Collapse plugin is loaded in front layout — if not, use `x-show` only)
- [x] `number_format($totalCollected, 0, ',', '.')` with 'Rp ' prefix (space after Rp)

### QA
- [x] `php artisan view:cache` → no compile errors
- [x] `curl -s http://localhost:8000/zakat | grep -i 'Terkumpul'` → stat heading present
- [x] With 0 distributions: Section B does NOT render (guard works)
- [x] Seed 1 ZakatDistribution → Section B renders 1 accordion item
- [x] Mobile layout: 2-col grid does not break (col-span-2 for first card)

---
- [ ] `php artisan migrate --pretend` → no errors, `zakat_distributions` table appears
- [ ] `php -l` on all modified PHP files → no syntax errors
- [ ] `php artisan view:cache` → no blade compile errors
- [ ] `curl -s http://localhost:8000/zakat | grep -i 'Terkumpul'` → section heading appears
- [ ] `curl -s http://localhost:8000/admin/zakat | grep -i 'Laporan'` → tab button present

---

## TASK-1: Migration — Create zakat_distributions Table

**File**: `database/migrations/{timestamp}_create_zakat_distributions_table.php`

### Checklist
- [x] Create migration file via `php artisan make:migration create_zakat_distributions_table`
- [x] Migration `up()`: fields: `id`, `title` (string), `amount` (decimal 15,2), `description` (text), `distribution_date` (date), `timestamps()`
- [x] Migration `down()`: `dropIfExists('zakat_distributions')`
- [x] Run `php artisan migrate` — confirm table created
- [x] Verify `php artisan migrate:status` shows new migration as "Ran"
- [x] Migration `up()`: fields: `id`, `title` (string), `amount` (decimal 15,2), `description` (text), `distribution_date` (date), `timestamps()`
- [x] Migration `down()`: `dropIfExists('zakat_distributions')`
- [x] Run `php artisan migrate` — confirm table created
- [x] Verify `php artisan migrate:status` shows new migration as "Ran"
- [ ] Migration `down()`: `dropIfExists('zakat_distributions')`
- [ ] Run `php artisan migrate` — confirm table created
- [ ] Verify `php artisan migrate:status` shows new migration as "Ran"

### QA
- [x] `php artisan migrate --pretend` → shows CREATE TABLE statement with correct columns

---

## TASK-2: Model — ZakatDistribution

**File**: `app/Models/ZakatDistribution.php`

### Checklist
- [x] Create model with: `namespace App\Models;`
- [x] `use HasFactory;`
- [x] `$fillable = ['title', 'amount', 'description', 'distribution_date']`
- [x] `$casts = ['amount' => 'decimal:2', 'distribution_date' => 'date']`
- [x] No relations needed (standalone table)
- [x] `php -l app/Models/ZakatDistribution.php` → no syntax errors
- [x] `php artisan tinker --execute="App\Models\ZakatDistribution::count();"` → returns 0 (not error)
- [ ] `use HasFactory;`
- [ ] `$fillable = ['title', 'amount', 'description', 'distribution_date']`
- [ ] `$casts = ['amount' => 'decimal:2', 'distribution_date' => 'date']`
- [ ] No relations needed (standalone table)

### QA
- [ ] `php -l app/Models/ZakatDistribution.php` → no syntax errors
- [ ] `php artisan tinker --execute="App\Models\ZakatDistribution::count();"` → returns 0 (not error)

---

## TASK-3: Admin Backend — ZakatList.php CRUD Methods

**File**: `app/Livewire/Admin/ZakatList.php`

### Checklist
- [x] Add `use App\Models\ZakatDistribution;` import
- [x] Add properties:
  ```php
  public bool $showDistributionModal = false;
  public bool $confirmingDistributionDeletion = false;
  public ?int $distributionId = null;
  public string $distributionTitle = '';
  public string $distributionAmount = '';
  public string $distributionDescription = '';
  public string $distributionDate = '';
  ```
- [x] Add method `createDistribution()`: reset form, `$showDistributionModal = true`
- [x] Add method `editDistribution(int $id)`: load record, populate props, `$showDistributionModal = true`
- [x] Add method `storeDistribution()`: validate → if `$distributionId` → update, else create; reset form; flash success; close modal
- [x] Add method `confirmDeleteDistribution(int $id)`: `$distributionId = $id; $confirmingDistributionDeletion = true;`
- [x] Add method `deleteDistribution()`: delete record, reset, flash success
- [x] Add method `resetDistributionForm()`: reset all distribution props, close modal and confirm flags
- [x] Modify `render()`: add `'distributions' => ZakatDistribution::latest()->paginate($this->perPage)` to data passed to view (load always — low volume data)
- [x] Add properties:
  ```php
  public bool $showDistributionModal = false;
  public bool $confirmingDistributionDeletion = false;
  public ?int $distributionId = null;
  public string $distributionTitle = '';
  public string $distributionAmount = '';
  public string $distributionDescription = '';
  public string $distributionDate = '';
  ```
- [x] Add method `createDistribution()`: reset form, `$showDistributionModal = true`
- [x] Add method `editDistribution(int $id)`: load record, populate props, `$showDistributionModal = true`
- [x] Add method `storeDistribution()`: validate → if `$distributionId` → update, else create; reset form; flash success; close modal
- [x] Add method `confirmDeleteDistribution(int $id)`: `$distributionId = $id; $confirmingDistributionDeletion = true;`
- [x] Add method `deleteDistribution()`: delete record, reset, flash success
- [x] Add method `resetDistributionForm()`: reset all distribution props, close modal and confirm flags
- [x] Modify `render()`: add `'distributions' => ZakatDistribution::latest()->paginate($this->perPage)` to data passed to view (load always — low volume data)
- [x] `php artisan tinker --execute="(new App\Livewire\Admin\ZakatList)->mount();"` → no errors
  ```php
  public bool $showDistributionModal = false;
  public bool $confirmingDistributionDeletion = false;
  public ?int $distributionId = null;
  public string $distributionTitle = '';
  public string $distributionAmount = '';
  public string $distributionDescription = '';
  public string $distributionDate = '';
  ```
- [ ] Add method `createDistribution()`: reset form, `$showDistributionModal = true`
- [ ] Add method `editDistribution(int $id)`: load record, populate props, `$showDistributionModal = true`
- [ ] Add method `storeDistribution()`: validate → if `$distributionId` → update, else create; reset form; flash success; close modal
  - Validation: `distributionTitle: required|string|max:255`, `distributionAmount: required|numeric|min:1`, `distributionDescription: required`, `distributionDate: required|date`
- [ ] Add method `confirmDeleteDistribution(int $id)`: `$distributionId = $id; $confirmingDistributionDeletion = true;`
- [ ] Add method `deleteDistribution()`: delete record, reset, flash success
- [ ] Add method `resetDistributionForm()`: reset all distribution props, close modal and confirm flags
- [ ] Modify `render()`: add `'distributions' => ZakatDistribution::latest()->paginate($this->perPage)` to data passed to view (load always — low volume data)

### QA
- [x] `php -l app/Livewire/Admin/ZakatList.php` → no syntax errors
- [x] `php artisan tinker --execute="(new App\Livewire\Admin\ZakatList)->mount();"` → no errors
- [ ] `php artisan tinker --execute="(new App\Livewire\Admin\ZakatList)->mount();"` → no errors

---

## TASK-4: Admin Blade — Tab & CRUD UI

**File**: `resources/views/livewire/admin/zakat-list.blade.php`

### Checklist
- [x] Add tab button "Laporan Penyaluran" in the tab switcher section (after "Pengaturan" button), using same CSS class pattern as existing tabs:
- [x] Add `@elseif($activeTab === 'laporan')` block after the settings `@elseif` block, before `@endif`
- [x] Tab content: white card wrapper, header with title "Laporan Penyaluran Zakat" + "Tambah" button (`wire:click="createDistribution"`)
- [x] Table: columns = Judul, Tanggal, Jumlah, Aksi (Edit + Hapus)
- [x] Empty state: if no distributions, show centered text
- [x] Pagination: `{{ $distributions->links() }}` 
- [x] Distribution Modal (`wire:model="showDistributionModal"`):
  - [x] Title: "Tambah Penyaluran" / "Edit Penyaluran"
  - [x] Input: `distributionTitle` (text), `distributionDate` (date), `distributionAmount` (number with Rp prefix)
  - [x] Rich text: Quill editor following ProgramManage pattern
  - [x] Buttons: Cancel (close modal) + Save (`wire:click="storeDistribution"`)
  - [x] Loading indicator on Save button
- [x] Delete confirmation modal (`wire:model="confirmingDistributionDeletion"`):
  - [x] Text: "Hapus penyaluran ini?"
  - [x] Buttons: Batal + Hapus (red, `wire:click="deleteDistribution"`)
- [x] `php artisan view:cache` → no compile errors
- [x] No unclosed HTML tags (count `<div` vs `</div>` in new section)
- [x] Tab appears at `/admin/zakat?activeTab=laporan` without 500 error
  ```html
  <button wire:click="setTab('laporan')" class="...same classes..." :class="...">
      Laporan Penyaluran
  </button>
  ```
- [ ] Add `@elseif($activeTab === 'laporan')` block after the settings `@elseif` block, before `@endif`
- [ ] Tab content: white card wrapper, header with title "Laporan Penyaluran Zakat" + "Tambah" button (`wire:click="createDistribution"`)
- [ ] Table: columns = Judul, Tanggal, Jumlah, Aksi (Edit + Hapus)
- [ ] Empty state: if no distributions, show centered text
- [ ] Pagination: `{{ $distributions->links() }}` 
- [ ] Distribution Modal (`wire:model="showDistributionModal"`):
  - Title: "Tambah Penyaluran" / "Edit Penyaluran"
  - Input: `distributionTitle` (text), `distributionDate` (date), `distributionAmount` (number with Rp prefix)
  - Rich text: Quill editor following ProgramManage pattern:
    ```html
    <div wire:ignore>
        <div x-data="quillEditor($wire.entangle('distributionDescription').live)">
            <div x-ref="quillEditor" class="min-h-[150px]"></div>
        </div>
    </div>
    ```
  - Buttons: Cancel (close modal) + Save (`wire:click="storeDistribution"`)
  - Loading indicator on Save button
- [ ] Delete confirmation modal (`wire:model="confirmingDistributionDeletion"`):
  - Text: "Hapus penyaluran ini?"
  - Buttons: Batal + Hapus (red, `wire:click="deleteDistribution"`)

### QA
- [ ] `php artisan view:cache` → no compile errors
- [ ] No unclosed HTML tags (count `<div` vs `</div>` in new section)
- [ ] Tab appears at `/admin/zakat?activeTab=laporan` without 500 error

---

## TASK-5: Frontend Backend — ZakatIndex.php Stats

**File**: `app/Livewire/Front/ZakatIndex.php`

### Checklist
- [x] Add `use App\Models\ZakatDistribution;` import
- [x] Add `use Illuminate\Support\Facades\DB;` import (if not already present)
- [x] Add public properties: `$totalCollected`, `$totalThisMonth`, `$totalTransactions`, `$zakatDistributions`
- [x] In `mount()`, calculate statistics using the specified query pattern
- [x] Load latest 12 zakat distributions in `mount()`
- [x] `php -l app/Livewire/Front/ZakatIndex.php` → no syntax errors
- [x] `php artisan tinker --execute="(new App\Livewire\Front\ZakatIndex)->mount();"` → no errors
- [x] Stats return numeric 0 (not null) when no zakat payments exist yet
- [ ] Add `use Illuminate\Support\Facades\DB;` import (if not already present)
- [ ] Add public properties:
  ```php
  public $totalCollected = 0;
  public $totalThisMonth = 0;
  public $totalTransactions = 0;
  public $zakatDistributions;
  ```
- [ ] In `mount()`, after existing code, add:
  ```php
  $baseQuery = \App\Models\Payment::where('transaction_type', 'zakat')->where('status', 'paid');
  $this->totalCollected   = (clone $baseQuery)->sum(DB::raw('amount + COALESCE(unique_code, 0)'));
  $this->totalThisMonth   = (clone $baseQuery)->whereMonth('paid_at', now()->month)->whereYear('paid_at', now()->year)->sum(DB::raw('amount + COALESCE(unique_code, 0)'));
  $this->totalTransactions = (clone $baseQuery)->count();
  $this->zakatDistributions = ZakatDistribution::latest()->limit(12)->get();
  ```

### QA
- [ ] `php -l app/Livewire/Front/ZakatIndex.php` → no syntax errors
- [ ] `php artisan tinker --execute="(new App\Livewire\Front\ZakatIndex)->mount();"` → no errors
- [ ] Stats return numeric 0 (not null) when no zakat payments exist yet

---

## TASK-6: Frontend Blade — Perolehan & Penyaluran Sections

**File**: `resources/views/livewire/front/zakat-index.blade.php`

### Insertion Point
Insert between the closing `</section>` of the collapsible niat/nisab block and `</main>`.

### Section A — Perolehan Zakat (3 Stat Cards)
```html
{{-- Perolehan Zakat --}}
<section class="px-4 mt-6">
    <h2 class="text-base font-bold text-gray-800 mb-3">Perolehan Zakat</h2>
    <div class="grid grid-cols-2 gap-3">
        {{-- Total Terkumpul --}}
        <div class="col-span-2 bg-gradient-to-br from-green-500 to-green-600 rounded-xl p-4 text-white shadow-lg shadow-green-200">
            <p class="text-xs opacity-80">Total Terkumpul</p>
            <p class="text-xl font-bold mt-1">Rp {{ number_format($totalCollected, 0, ',', '.') }}</p>
        </div>
        {{-- Bulan Ini --}}
        <div class="bg-gradient-to-br from-teal-500 to-teal-600 rounded-xl p-4 text-white shadow-lg shadow-teal-200">
            <p class="text-xs opacity-80">Bulan Ini</p>
            <p class="text-lg font-bold mt-1">Rp {{ number_format($totalThisMonth, 0, ',', '.') }}</p>
        </div>
        {{-- Total Transaksi --}}
        <div class="bg-gradient-to-br from-emerald-500 to-emerald-600 rounded-xl p-4 text-white shadow-lg shadow-emerald-200">
            <p class="text-xs opacity-80">Total Transaksi</p>
            <p class="text-lg font-bold mt-1">{{ number_format($totalTransactions, 0, ',', '.') }}</p>
        </div>
    </div>
</section>
```

### Section B — Laporan Penyaluran Zakat (Accordion List)
```html
{{-- Laporan Penyaluran Zakat --}}
@if($zakatDistributions && $zakatDistributions->isNotEmpty())
<section class="px-4 mt-6 mb-4">
    <h2 class="text-base font-bold text-gray-800 mb-3">Laporan Penyaluran Zakat</h2>
    <div class="space-y-3">
        @foreach($zakatDistributions as $dist)
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden"
             x-data="{ open: false }">
            <button @click="open = !open"
                    class="w-full flex items-center justify-between px-4 py-3 text-left">
                <div>
                    <p class="text-sm font-semibold text-gray-800">{{ $dist->title }}</p>
                    <p class="text-xs text-gray-500 mt-0.5">
                        {{ $dist->distribution_date->translatedFormat('d F Y') }}
                        &bull; Rp {{ number_format($dist->amount, 0, ',', '.') }}
                    </p>
                </div>
                <svg class="w-4 h-4 text-gray-400 transition-transform" :class="open ? 'rotate-180' : ''"
                     fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                </svg>
            </button>
            <div x-show="open" x-collapse class="px-4 pb-4">
                {{-- Admin-only input, accepted XSS parity with ProgramDistribution --}}
                <div class="text-sm text-gray-600 prose prose-sm max-w-none">
                    {!! $dist->description !!}
                </div>
            </div>
        </div>
        @endforeach
    </div>
</section>
@endif
```

### Checklist
- [x] Insert Section A (stat cards) after collapsible section closing tag, before `</main>`
- [x] Insert Section B (distribution list) immediately after Section A
- [x] Verify `pb-32` on `<main>` is still sufficient — if sections overflow bottom CTA, change to `pb-40`
- [x] `@if($zakatDistributions && $zakatDistributions->isNotEmpty())` guard for Section B
- [x] Alpine.js `x-collapse` used for smooth accordion (check if Alpine Collapse plugin is loaded in front layout — if not, use `x-show` only)
- [x] `number_format($totalCollected, 0, ',', '.')` with 'Rp ' prefix (space after Rp)
- [x] `php artisan view:cache` → no compile errors
- [x] `curl -s http://localhost:8000/zakat | grep -i 'Terkumpul'` → stat heading present
- [x] With 0 distributions: Section B does NOT render (guard works)
- [x] Seed 1 ZakatDistribution → Section B renders 1 accordion item
- [x] Mobile layout: 2-col grid does not break (col-span-2 for first card)
- [ ] Insert Section B (distribution list) immediately after Section A
- [ ] Verify `pb-32` on `<main>` is still sufficient — if sections overflow bottom CTA, change to `pb-40`
- [ ] `@if($zakatDistributions && $zakatDistributions->isNotEmpty())` guard for Section B
- [ ] Alpine.js `x-collapse` used for smooth accordion (check if Alpine Collapse plugin is loaded in front layout — if not, use `x-show` only)
- [ ] `number_format($totalCollected, 0, ',', '.')` with 'Rp ' prefix (space after Rp)

### QA
- [ ] `php artisan view:cache` → no compile errors
- [ ] `curl -s http://localhost:8000/zakat | grep -i 'Terkumpul'` → stat heading present
- [ ] With 0 distributions: Section B does NOT render (guard works)
- [ ] Seed 1 ZakatDistribution → Section B renders 1 accordion item
- [ ] Mobile layout: 2-col grid does not break (col-span-2 for first card)

---

## TASK-7: End-to-End Visual QA

### Checklist
- [ ] Open `/zakat` in browser → stat cards show "Rp 0" when no data (not null/error)
- [ ] Admin: open `/admin/zakat?activeTab=laporan` → tab renders table (empty state shown)
- [ ] Admin: click "Tambah" → modal opens with Quill editor functional
- [ ] Admin: fill form → save → record appears in table, flash success shown
- [ ] Admin: edit record → modal pre-filled → update → table updated
- [ ] Admin: delete → confirmation modal → confirm → record removed
- [ ] Frontend: refresh `/zakat` → new distribution appears in accordion
- [ ] Frontend: accordion click → description expands/collapses smoothly
- [ ] Mobile viewport (375px): stat cards 2-col grid renders correctly
- [ ] No JS console errors on both pages
- [ ] `curl -s http://localhost:8000/zakat | grep 'og:image'` → still returns banner URL (existing og:image not broken)
