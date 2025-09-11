<?php

namespace App\Domains\Bill\Traits;

use App\Domains\Customer\Model\Customer;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

trait BillRelationship
{

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }
}
