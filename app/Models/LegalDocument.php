<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LegalDocument extends Model
{
    protected $fillable = [
        'title',
        'document_number',
        'issuing_authority',
        'status',
        'expiry_date',
        'file_url',
        'sort_order',
    ];

    protected $casts = [
        'expiry_date' => 'date',
    ];

    public function getFileUrlAttribute($value)
    {
        if (!$value) {
            return null;
        }

        if (str_starts_with($value, 'http')) {
            return $value;
        }

        return \Illuminate\Support\Facades\Storage::disk('public')->url($value);
    }
}
