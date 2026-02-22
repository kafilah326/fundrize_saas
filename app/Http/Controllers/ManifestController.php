<?php

namespace App\Http\Controllers;

use App\Models\FoundationSetting;

class ManifestController extends Controller
{
    public function index()
    {
        $setting = FoundationSetting::first();

        // Default values if setting not found
        $name = $setting->name ?? 'Yayasan Peduli';
        $shortName = isset($setting->name) ? substr($setting->name, 0, 12) : 'YPeduli';

        // Icon logic: Use faivcon or logo if available
        $iconUrl = null;
        if ($setting && $setting->favicon) {
            $iconUrl = $setting->favicon; // Model accessor handles full URL
        } elseif ($setting && $setting->logo) {
            $iconUrl = $setting->logo;
        } else {
            $iconUrl = asset('icons/icon-512.png');
        }

        // Determine mime type
        $mimeType = 'image/png';
        if (str_ends_with(strtolower($iconUrl), '.webp')) {
            $mimeType = 'image/webp';
        } elseif (str_ends_with(strtolower($iconUrl), '.jpg') || str_ends_with(strtolower($iconUrl), '.jpeg')) {
            $mimeType = 'image/jpeg';
        }

        return response()->json([
            'name' => $name,
            'short_name' => $shortName,
            'start_url' => '/',
            'display' => 'standalone',
            'background_color' => '#F8F9FA',
            'theme_color' => '#FF6B35',
            'orientation' => 'portrait',
            'icons' => [
                [
                    'src' => $iconUrl,
                    'sizes' => '192x192',
                    'type' => $mimeType,
                ],
                [
                    'src' => $iconUrl,
                    'sizes' => '512x512',
                    'type' => $mimeType,
                    'purpose' => 'any maskable',
                ],
            ],
        ]);
    }
}
