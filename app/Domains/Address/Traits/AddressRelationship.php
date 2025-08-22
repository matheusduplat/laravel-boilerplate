<?php

namespace App\Domains\Address\Traits;

use App\Domains\State\Model\State;
use Illuminate\Database\Eloquent\Relations\MorphTo;

trait AddressRelationship
{
    /*************  ✨ Windsurf Command ⭐  *************/
    /**
     * Addressable model that owns the address
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    /*******  bd1ed68f-1e08-4dc4-8bf8-9d883458e895  *******/
    public function addressable(): MorphTo
    {
        return $this->morphTo('addressable');
    }
    public function stateable()
    {
        return $this->belongsTo(State::class, 'state', 'code');
    }
}
