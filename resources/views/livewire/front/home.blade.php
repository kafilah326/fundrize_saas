<div>
    @php
        $templateSlug = \App\Models\AppSetting::get('home_template', 'default');
        if (empty($templateSlug)) { $templateSlug = 'default'; }
        $viewName = 'livewire.front.home-' . $templateSlug;
        if (!\Illuminate\Support\Facades\View::exists($viewName)) {
            $viewName = 'livewire.front.home-default';
        }
    @endphp

    @include($viewName)
</div>
