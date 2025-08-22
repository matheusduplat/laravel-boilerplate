<?php

namespace App\Domains\TrustedDevice\Traits;

use App\Domains\User\Model\User;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

trait TrustedDeviceRelationship
{
    public function owner(): MorphTo
    {
        return $this->morphTo('owner');
    }
}
