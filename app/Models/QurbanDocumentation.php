<?php

namespace App\Models;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;

class QurbanDocumentation extends Model
{
    use BelongsToTenant;

    protected $fillable = [
        'tenant_id',
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
