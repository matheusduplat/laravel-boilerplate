<?php

namespace App\Domains\Customer\Http\Actions;

use App\Domains\Customer\Enums\CustomerStatus;
use App\Domains\Customer\Model\Customer;
use App\Domains\Role\Enums\RoleDefaults;
use App\Domains\Role\Model\Role;
use Illuminate\Support\Facades\Auth;

class StoreCustomerAction
{
    public function execute(array $data)
    {
        $customer = Customer::create([
            ...$data,
            'created_by' => Auth::user()->name ?? null,
            'status' => CustomerStatus::ACTIVE,
        ]);

        return $customer;
    }
}
