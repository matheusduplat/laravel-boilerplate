<?php

namespace App\Domains\Phone\Traits;

use Illuminate\Database\Eloquent\Relations\MorphTo;

trait PhoneRelationship
{

    public function phoneable(): MorphTo
    {
        return $this->morphTo('phoneable');
    }
}
