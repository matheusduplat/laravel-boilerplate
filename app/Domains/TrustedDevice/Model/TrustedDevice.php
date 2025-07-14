<?php

namespace App\Domains\TrustedDevice\Model;

use App\Domains\TrustedDevice\Traits\TrustedDeviceRelationship;
use Illuminate\Database\Eloquent\Model;

class TrustedDevice extends Model
{
    use TrustedDeviceRelationship;
    protected $table = 'trusted_devices';
    protected $fillable = [
        'user_id',
        'device_token_hash',
        'device_name',
        'user_agent',
        'ip_address',
        'expires_at',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
    ];
}
