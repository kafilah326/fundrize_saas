<?php

return [
    'base_domain' => env('TENANCY_BASE_DOMAIN', 'fundrize.com'),
    'superadmin_domain' => env('TENANCY_SUPERADMIN_DOMAIN', 'superadmin.fundrize.id'),
    'app_domain' => env('TENANCY_APP_DOMAIN', 'app.fundrize.com'),
    'default_plan' => 'trial',
    'trial_days' => 14,
];
