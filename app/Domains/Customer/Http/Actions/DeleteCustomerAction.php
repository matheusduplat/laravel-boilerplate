<?php

namespace App\Domains\Customer\Http\Actions;

use App\Domains\Customer\Enums\CustomerStatus;
use App\Domains\Customer\Model\Customer;
use Illuminate\Support\Facades\Auth;

class DeleteCustomerAction
{
    public function execute(Customer $customer)
    {
        $data['deleted_by'] = Auth::user()->name ?? null;
        $data['status'] = CustomerStatus::INACTIVE;
        $customer->update($data);
        $customer->delete();
        return $customer;
    }
}
