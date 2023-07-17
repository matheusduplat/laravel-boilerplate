<?php

namespace App\Models\Traits\Relationship;

use App\Models\Lead;
use App\Models\Phone;

/**
 * Class UserRelationship.
 */
trait UserRelationship
{
    public function phones()
    {
        return $this->morphMany(Phone::class, 'model');
    }
    public function userable()
    {
        return $this->morphTo('model');
    }
    public function lead()
    {
        return $this->morphedByMany(Lead::class, 'sending_alerts');
    }
}
