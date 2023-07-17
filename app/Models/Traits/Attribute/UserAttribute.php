<?php

namespace App\Models\Traits\Attribute;

use Illuminate\Support\Facades\Hash;

/**
 * Trait UserAttribute.
 */
trait UserAttribute
{
    public function setPasswordAttribute($value)
    {
      $this->attributes['password'] = Hash::make($value);
    }
}
