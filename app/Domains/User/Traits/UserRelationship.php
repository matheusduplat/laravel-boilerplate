<?php

namespace App\Domains\User\Traits;


use App\Domains\TrustedDevice\Model\TrustedDevice;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;

trait UserRelationship
{
    public function trustedDevices(): HasMany
    {
        return $this->hasMany(TrustedDevice::class);
    }
    public function userable(): MorphTo
    {
        return $this->morphTo('userable');
    }
}
