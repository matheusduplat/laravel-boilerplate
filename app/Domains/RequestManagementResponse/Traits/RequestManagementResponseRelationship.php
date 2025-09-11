<?php

namespace App\Domains\RequestManagementResponse\Traits;

use App\Domains\RequestManagement\Model\RequestManagement;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

trait RequestManagementResponseRelationship
{
    public function requestManagement(): BelongsTo
    {
        return $this->belongsTo(RequestManagement::class);
    }
    public function authorable(): MorphTo
    {
        return $this->morphTo('authorable');
    }
}
