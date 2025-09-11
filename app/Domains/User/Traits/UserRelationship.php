<?php

namespace App\Domains\User\Traits;

use App\Domains\Customer\Model\Customer;
use App\Domains\Employee\Model\Employee;
use App\Domains\Role\Model\Role;
use App\Domains\TrustedDevice\Model\TrustedDevice;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphOne;

trait UserRelationship
{
    public function trustedDevices(): HasMany
    {
        return $this->hasMany(TrustedDevice::class);
    }
    public function employee(): HasOne
    {
        return $this->hasOne(Employee::class);
    }
    public function customer(): HasOne
    {
        return $this->hasOne(Customer::class);
    }
}
