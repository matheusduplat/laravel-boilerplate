<?php

namespace App\Domains\TrustedDevice\Traits;

use App\Domains\TrustedDevice\Model\TrustedDevice;
use Illuminate\Database\Eloquent\Relations\HasMany;

trait TrustedDeviceRelationship
{
    public function trustedDevices(): HasMany
    {
        return $this->hasMany(TrustedDevice::class);
    }
}
