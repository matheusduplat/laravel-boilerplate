<?php

namespace App\Trait;

use Illuminate\Database\Eloquent\SoftDeletes;

trait GlobalSoftDelete
{
    use SoftDeletes;
    public function resolveRouteBinding($value, $field = null)
    {
        return $this->withTrashed()->where($field ?? $this->getRouteKeyName(), $value)->first();
    }
}
