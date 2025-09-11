<?php

namespace App\Domains\Contact\Traits;

use App\Domains\ContactResponse\Model\ContactResponse;
use App\Domains\Customer\Model\Customer;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

trait ContactRelationship
{
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }
    public function response(): HasMany
    {
        return $this->hasMany(ContactResponse::class);
    }
}
