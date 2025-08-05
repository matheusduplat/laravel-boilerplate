<?php

namespace App\Domains\User\Traits;

use App\Domains\TrustedDevice\Model\TrustedDevice;

trait UserRelationship
{
    public function trustedDevices()
    {
        return $this->hasMany(TrustedDevice::class);
    }
}
