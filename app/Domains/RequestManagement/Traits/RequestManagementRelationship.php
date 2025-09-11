<?php

namespace App\Domains\RequestManagement\Traits;

use App\Domains\Customer\Model\Customer;
use App\Domains\Employee\Model\Employee;
use App\Domains\RequestManagementResponse\Model\RequestManagementResponse;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

trait RequestManagementRelationship
{
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }
    public function responses(): HasMany
    {
        return $this->hasMany(RequestManagementResponse::class);
    }
    public function responsible(): BelongsToMany
    {
        return $this->belongsToMany(Employee::class, 'request_management_responsible', 'request_management_id', 'employee_id')->withTimestamps();
    }
}
