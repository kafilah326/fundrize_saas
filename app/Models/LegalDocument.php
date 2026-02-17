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
}
