<?php

namespace App\Domains\TrustedDevice\Model;

use App\Domains\TrustedDevice\Traits\TrustedDeviceRelationship;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use \OwenIt\Auditing\Auditable as AuditableTrait;

class TrustedDevice extends Model implements Auditable
{
    use TrustedDeviceRelationship, AuditableTrait;
    protected $table = 'trusted_devices';
    protected $fillable = [
        'user_id',
        'device_token_hash',
        'device',
        'platform',
        'browser',
        'user_agent',
        'ip_address',
        'expires_at',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
    ];
}
