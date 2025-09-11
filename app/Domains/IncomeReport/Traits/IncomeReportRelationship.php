<?php

namespace App\Domains\IncomeReport\Traits;

use App\Domains\Customer\Model\Customer;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

trait IncomeReportRelationship
{

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }
}
