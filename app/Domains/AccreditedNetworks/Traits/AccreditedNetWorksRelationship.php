<?php

namespace App\Domains\AccreditedNetworks\Traits;

use App\Domains\Address\Model\Address;
use App\Domains\Phone\Model\Phone;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;

trait AccreditedNetWorksRelationship
{
    public function address(): MorphOne
    {
        return $this->morphOne(Address::class, 'addressable');
    }

    public function phones(): MorphMany
    {
        return $this->morphMany(Phone::class, 'phoneable');
    }
}
