<?php

namespace App\Domains\DigitalWallet\Traits;

use App\Domains\Customer\Model\Customer;

trait DigitalWalletRelationship
{
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
}
