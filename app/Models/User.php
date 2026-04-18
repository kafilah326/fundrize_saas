<?php

namespace App\Models;

use App\Traits\BelongsToTenant;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use BelongsToTenant;

    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'tenant_id',
        'name',
        'email',
        'phone',
        'avatar',
        'role',
        'otp_code',
        'otp_expires_at',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'otp_code',
        'otp_expires_at',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'otp_expires_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function donations()
    {
        return $this->hasMany(Donation::class);
    }

    public function qurbanOrders()
    {
        return $this->hasMany(QurbanOrder::class);
    }

    public function qurbanSavings()
    {
        return $this->hasMany(QurbanSaving::class);
    }

    public function fundraiser()
    {
        return $this->hasOne(Fundraiser::class);
    }

    public function getInitialsAttribute()
    {
        $name = trim($this->name);
        if (empty($name)) {
            return '??';
        }

        // Split by spaces and filter out empty strings (handling multiple spaces)
        $words = array_values(array_filter(explode(' ', $name)));
        
        if (count($words) === 1) {
            // Jika 1 kata ambil 2 huruf pertama
            return strtoupper(substr($name, 0, 2));
        }
        
        // Jika 2 kata atau lebih, ambil huruf pertama kata 1 dan kata 2
        return strtoupper(substr($words[0], 0, 1) . substr($words[1], 0, 1));
    }
}
