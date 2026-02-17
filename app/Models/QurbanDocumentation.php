<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QurbanDocumentation extends Model
{
    protected $fillable = [
        'documentable_type',
        'documentable_id',
        'file_path',
        'file_type',
        'caption',
        'sort_order',
    ];

    public function documentable()
    {
        return $this->morphTo();
    }
}
