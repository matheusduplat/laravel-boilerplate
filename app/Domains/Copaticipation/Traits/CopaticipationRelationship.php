<?php

namespace App\Domains\Copaticipation\Traits;

use App\Domains\Customer\Model\Customer;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

trait CopaticipationRelationship
{

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }
}
