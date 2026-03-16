# Plan: Home Template Switcher

**Feature**: Allow admins to switch between multiple homepage layout templates via a dedicated admin menu.
**Date**: 2026-03-16
**Status**: Ready for execution

---

## Context & Architecture

### What Exists
- **Home route**: `Route::get('/', Home::class)->name('home')`
- **Home component**: `app/Livewire/Front/Home.php`
  - `mount()` loads: `$foundation`, `$banners`, `$featuredPrograms`, `$otherPrograms`, `$categories`, `$akads`
  - `render()` returns: `view('livewire.front.home')->layout('layouts.front', [title, metaDescription, metaKeywords, metaImage])`
- **Current blade**: `resources/views/livewire/front/home.blade.php` (monolithic, static)
- **AppSetting model**: key-value table, `get($key, $default)` / `set($key, $value)`, cache auto-managed
- **Admin**: Tab-based `app/Livewire/Admin/Settings.php`, existing sidebar navigation

### Architecture Decisions
- Templates = **separate blade files** per template (`home-{slug}.blade.php`)
- Adding new template in future = drop new blade file + add one `<option>` to admin dropdown
- Template selection = stored in `app_settings` table as key `home_template` (value: slug string)
- Admin UI = **dedicated new sidebar menu** (new Livewire component + route), NOT inside existing Settings tabs
- All templates share **identical data contract** — no changes to `mount()`
- `View::exists()` guard in `render()` as fallback safety

### Template Data Contract (ALL templates must use these variables)
```
$foundation     → FoundationSetting model (name, tagline, logo, about, etc.)
$banners        → Collection of active Banner models for 'home' placement
$featuredPrograms → Collection of up to 5 featured active Program models
$otherPrograms  → Collection of up to 5 latest active Program models
$categories     → Collection of all active Category models
$akads          → Collection of all active AkadType models
```

### File Naming Convention (STRICT)
- Pattern: `resources/views/livewire/front/home-{slug}.blade.php`
- `{slug}` must be **lowercase alphanumeric + hyphens only** (no spaces, no special chars)
- First template slug: `default`
- All templates MUST use identical root `<div>` wrapper element (required for Livewire 3 Alpine morphing)

---

## Scope

**IN:**
- Rename existing `home.blade.php` → `home-default.blade.php`
- Add data contract comment block at top of `home-default.blade.php`
- Modify `Home::render()` with dynamic view resolution + `View::exists()` fallback
- Seed `home_template` key in `app_settings` table
- New Livewire admin component: `app/Livewire/Admin/HomepageTemplate.php`
- New blade view: `resources/views/livewire/admin/homepage-template.blade.php`
- New admin route for template manager
- Add sidebar link in admin navigation
- Run `php artisan view:clear` after blade rename

**OUT:**
- Template preview feature
- Dynamic template list from database
- New database columns or tables
- Changes to `mount()` data loading
- Any additional homepage features
- Cache::flush or artisan cache:clear

---

## Implementation Tasks

<!-- TASKS START -->

## Task 1: Rename home.blade.php → home-default.blade.php

**File**: `resources/views/livewire/front/home.blade.php`
**Action**: Rename to `resources/views/livewire/front/home-default.blade.php`

**Steps:**
1. Rename the file (git mv to preserve history)
2. Add data contract comment block at the very top of the renamed file (before any HTML):

```blade
{{--
  HOME TEMPLATE: default
  ========================
  DATA CONTRACT — All home templates receive these Livewire public properties:
    $foundation      → FoundationSetting model (name, tagline, logo, favicon, about, vision, mission, address, phone, email, social_media, focus_areas)
    $banners         → Collection<Banner> — active banners for 'home' placement, ordered by priority asc
    $featuredPrograms→ Collection<Program> — up to 5 active + featured programs (latest first)
    $otherPrograms   → Collection<Program> — up to 5 latest active programs
    $categories      → Collection<Category> — all active categories
    $akads           → Collection<AkadType> — all active akad types

  NAMING CONVENTION: home-{slug}.blade.php
  ROOT ELEMENT: Must keep identical root <div> wrapper as other templates (Livewire/Alpine morphing requirement)

  To add a new template:
    1. Create resources/views/livewire/front/home-{newslug}.blade.php
    2. Add <option value="{{ newslug }}">Label</option> to admin/homepage-template.blade.php dropdown
    3. Run php artisan view:clear
--}}
```

3. Run: `php artisan view:clear`

**QA:**
- [x] Files created/modified: `resources/views/livewire/front/home-default.blade.php`
- [x] Old path `resources/views/livewire/front/home.blade.php` does NOT exist
- [x] Comment block is present at top of file
- [x] `php artisan view:clear` exits with code 0

---

## Task 2: Modify Home::render() — Dynamic View Resolution

**File**: `app/Livewire/Front/Home.php`
**Action**: Replace the static `view()` call with dynamic resolution + fallback guard

**Current code** (in `render()`):
```php
return view('livewire.front.home')->layout('layouts.front', [
    'title'           => $this->foundation->name,
    'metaDescription' => \Illuminate\Support\Str::limit(strip_tags($this->foundation->about ?? ''), 160),
    'metaKeywords'    => 'donasi, yayasan, sedekah, zakat, infaq, qurban, galang dana, crowdfunding, ' . $this->foundation->name,
    'metaImage'       => $logo ?: null,
]);
```

**Replace with:**
```php
$templateSlug = AppSetting::get('home_template', 'default');
$viewName = 'livewire.front.home-' . $templateSlug;

// Fallback to default if template view file does not exist
if (!\Illuminate\Support\Facades\View::exists($viewName)) {
    $viewName = 'livewire.front.home-default';
}

return view($viewName)->layout('layouts.front', [
    'title'           => $this->foundation->name,
    'metaDescription' => \Illuminate\Support\Str::limit(strip_tags($this->foundation->about ?? ''), 160),
    'metaKeywords'    => 'donasi, yayasan, sedekah, zakat, infaq, qurban, galang dana, crowdfunding, ' . $this->foundation->name,
    'metaImage'       => $logo ?: null,
]);
```

**Required import** — add at top of file if not present:
```php
use App\Models\AppSetting;
use Illuminate\Support\Facades\View;
```

**QA:**
- [x] Homepage loads (HTTP 200) with `home_template = 'default'` in DB
- [x] Homepage loads (HTTP 200) when `home_template` is set to a non-existent slug (fallback to default)
- [x] Homepage loads (HTTP 200) when `home_template` is not seeded (AppSetting::get returns default)
- [x] The `.layout('layouts.front', [...])` chain is present and intact
- [x] Page `<title>` is correct (not empty)

---

## Task 3: Seed home_template AppSetting Record

**File**: `database/seeders/AppSettingSeeder.php`
**Action**: Add `home_template` record using `firstOrCreate` (idempotent)

Add inside the seeder's `run()` method, following the existing pattern in the file:

```php
\App\Models\AppSetting::firstOrCreate(
    ['key' => 'home_template'],
    [
        'value'       => 'default',
        'group'       => 'appearance',
        'type'        => 'text',
        'label'       => 'Template Halaman Utama',
        'description' => 'Pilih template tampilan halaman utama yang aktif. Slug harus sesuai dengan nama file home-{slug}.blade.php',
    ]
);
```

**Then run**: `php artisan db:seed --class=AppSettingSeeder`

**QA:**
- [x] Record exists in `app_settings` table with `key = 'home_template'`
- [x] `value = 'default'`, `group = 'appearance'`, `type = 'text'`
- [x] Running seeder a second time does NOT duplicate the record (idempotent via `firstOrCreate`)
- [x] `AppSetting::get('home_template')` returns `'default'` in tinker

---

## Task 4: Create Admin Livewire Component — HomepageTemplate

**File**: `app/Livewire/Admin/HomepageTemplate.php` (NEW)
**Action**: Create new Livewire component for template management

```php
<?php

namespace App\Livewire\Admin;

use App\Models\AppSetting;
use Livewire\Component;

class HomepageTemplate extends Component
{
    public string $selectedTemplate = 'default';

    /**
     * Hardcoded list of available templates.
     * To add a new template: add entry here AND create the corresponding blade file.
     * Format: 'slug' => 'Display Label'
     */
    public array $availableTemplates = [
        'default' => 'Default (Standard)',
    ];

    public function mount(): void
    {
        $this->selectedTemplate = AppSetting::get('home_template', 'default');
    }

    public function save(): void
    {
        $this->validate([
            'selectedTemplate' => ['required', 'string', 'in:' . implode(',', array_keys($this->availableTemplates))],
        ]);

        AppSetting::set('home_template', $this->selectedTemplate);

        session()->flash('success', 'Template halaman utama berhasil disimpan.');
    }

    public function render(): \Illuminate\View\View
    {
        return view('livewire.admin.homepage-template')
            ->layout('layouts.admin', ['title' => 'Template Halaman Utama']);
    }
}
```

**QA:**
- [x] Component class exists at correct namespace
- [x] `mount()` loads current value from AppSetting
- [x] `save()` validates against `$availableTemplates` keys (server-side allowlist)
- [x] `save()` calls `AppSetting::set()` (not direct DB write) — ensures cache auto-cleared
- [x] Flash success message appears after save
- [x] After save, `AppSetting::get('home_template')` returns the newly saved value

---

## Task 5: Create Admin Blade View — homepage-template.blade.php

**File**: `resources/views/livewire/admin/homepage-template.blade.php` (NEW)
**Action**: Create the blade view for the template manager page

The view must:
1. Show a page header "Template Halaman Utama"
2. Show a `<select>` dropdown bound to `wire:model="selectedTemplate"` with hardcoded `<option>` values matching `$availableTemplates`
3. Show a Save button with `wire:click="save"`
4. Show flash success message if `session('success')` is set
5. Show current active template name as a badge/indicator
6. Follow the existing admin blade style (use same card/panel structure as other admin pages — reference `resources/views/livewire/admin/settings.blade.php` for styling patterns)

**Structure:**
```blade
<div>
    {{-- Flash message --}}
    @if (session()->has('success'))
        <div class="...success alert...">{{ session('success') }}</div>
    @endif

    {{-- Page header --}}
    <h1>Template Halaman Utama</h1>

    {{-- Template selector card --}}
    <div class="...card...">
        <label>Pilih Template</label>
        <select wire:model="selectedTemplate">
            @foreach($availableTemplates as $slug => $label)
                <option value="{{ $slug }}">{{ $label }}</option>
            @endforeach
        </select>

        {{-- Current active indicator --}}
        <p>Template aktif saat ini: <strong>{{ $availableTemplates[$selectedTemplate] ?? $selectedTemplate }}</strong></p>

        <button wire:click="save">Simpan Template</button>
    </div>

    {{-- Convention note for developers --}}
    {{-- Add a visible info box explaining naming convention for future templates --}}
    <div class="...info box...">
        <strong>Cara menambah template baru:</strong>
        <ol>
            <li>Buat file: <code>resources/views/livewire/front/home-{slug}.blade.php</code></li>
            <li>Tambah entri di <code>$availableTemplates</code> pada <code>HomepageTemplate.php</code></li>
            <li>Jalankan <code>php artisan view:clear</code></li>
        </ol>
    </div>
</div>
```

**Style reference**: Copy card/panel/button classes exactly from `resources/views/livewire/admin/settings.blade.php` — do NOT invent new classes.

**QA:**
- [x] Page renders without error
- [x] Dropdown shows all entries from `$availableTemplates`
- [x] Selecting an option and saving shows flash success
- [x] Current active template is shown correctly
- [x] Developer instruction box is visible
- [x] Layout matches existing admin pages visually

---

## Task 6: Register Admin Route

**File**: `routes/web.php`
**Action**: Add route for the new HomepageTemplate admin page

Find the admin route group (where other admin routes are registered) and add:

```php
use App\Livewire\Admin\HomepageTemplate;

// Inside the admin middleware/prefix group:
Route::get('/admin/homepage-template', HomepageTemplate::class)->name('admin.homepage-template');
```

**Location hint**: Find the block with other `Route::get('/admin/...')` routes. Add the new route adjacent to settings-related routes.

**QA:**
- [x] `php artisan route:list | grep homepage-template` shows the route
- [x] Route is named `admin.homepage-template`
- [x] Route is inside the admin middleware group (requires auth + admin role like other admin routes)
- [x] Navigating to `/admin/homepage-template` loads the page with HTTP 200

---

## Task 7: Add Sidebar Navigation Link

**Action**: Add "Template Halaman Utama" link to the admin sidebar

**How to find the sidebar file:**
1. Search for the file containing existing admin sidebar links: `grep -r "admin/settings" resources/views/` to locate the sidebar blade file
2. Find the section for appearance/settings links
3. Add new `<a>` or sidebar item component adjacent to existing settings link

**Link to add:**
```blade
<a href="{{ route('admin.homepage-template') }}"
   class="{{ request()->routeIs('admin.homepage-template') ? 'active-class-from-existing-pattern' : 'inactive-class' }}">
    {{-- Use same icon style as adjacent items --}}
    <svg ...> {{-- Layout/template icon --}} </svg>
    Template Halaman Utama
</a>
```

**Implementation rule**: Copy the exact HTML structure (classes, icon format, active state pattern) from an adjacent sidebar item — do NOT invent new markup patterns. The active state class MUST follow the same conditional pattern as other sidebar items.

**QA:**
- [x] Link appears in admin sidebar
- [x] Link is active/highlighted when on the homepage-template page
- [x] Link is not active on other admin pages
- [x] Clicking the link navigates to `/admin/homepage-template`

---

## Final Verification Wave

Run all checks end-to-end after completing all tasks:

```bash
- [x] View cache cleared cleanly
php artisan view:clear

- [x] Seeder is idempotent
php artisan db:seed --class=AppSettingSeeder
php artisan db:seed --class=AppSettingSeeder  # run twice — no duplicate records

- [x] AppSetting value correct
php artisan tinker --execute="echo \App\Models\AppSetting::get('home_template');"
# Expected output: default

- [x] Route registered
php artisan route:list | grep homepage-template
# Expected: admin.homepage-template route shown

- [x] Homepage loads (with web server running)
# Navigate to / — assert 200, page renders with template content

- [x] Admin page loads
# Navigate to /admin/homepage-template (logged in as admin) — assert 200

- [x] Fallback safety test
php artisan tinker --execute="\App\Models\AppSetting::set('home_template', 'nonexistent');"
# Navigate to / — assert 200 (NOT 500), page renders default template
# Reset: php artisan tinker --execute="\App\Models\AppSetting::set('home_template', 'default');"

- [x] Save flow test
# Navigate to /admin/homepage-template
# Change dropdown selection, click Simpan Template
# Assert: green success flash message appears
# Assert: AppSetting::get('home_template') returns new value
```

---

## Adding Future Templates (Reference)

When adding template #2, #3, etc.:

1. Create `resources/views/livewire/front/home-{slug}.blade.php`
   - Copy `home-default.blade.php` as starting point
   - Update comment block: change slug name
   - Keep identical root `<div>` wrapper
   - Use same data contract variables

2. Add to `$availableTemplates` in `app/Livewire/Admin/HomepageTemplate.php`:
   ```php
   public array $availableTemplates = [
       'default' => 'Default (Standard)',
       'newslug' => 'Nama Template Baru',
   ];
   ```

3. Run `php artisan view:clear`

**No other files need to change.** The `Home::render()` logic, routing, sidebar — all stay as-is.
- [x] Commit and push branch

- [x] Fix View not found error caused by implicit page component routing
- [x] Create wrapper view file `home.blade.php`
- [x] Add v2 template (`home-v2.blade.php`)
- [x] Register v2 in `HomepageTemplate` admin settings