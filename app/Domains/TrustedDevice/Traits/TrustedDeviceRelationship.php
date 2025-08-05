<?php

namespace App\Domains\TrustedDevice\Traits;

use App\Domains\User\Model\User;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

trait TrustedDeviceRelationship
{
    public function user(): BelongsTo
    {
        return $this->belongTo(User::class);
    }
}
