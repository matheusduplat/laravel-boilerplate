<?php

namespace App\Domains\Customer\Traits;

use App\Domains\Address\Model\Address;
use App\Domains\Customer\Model\Customer;
use App\Domains\Phone\Model\Phone;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;

trait CustomerRelationship
{


    public function phones(): MorphMany
    {
        return $this->morphMany(Phone::class, 'phoneable');
    }
    public function address(): MorphOne
    {
        return $this->morphOne(Address::class, 'addressable');
    }
}
