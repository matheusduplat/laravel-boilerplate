<?php

namespace App\Domains\Customer\Http\Actions;

use App\Domains\Customer\Model\Customer;
use Illuminate\Support\Facades\Auth;

class UpdateCustomerAction
{
    public function execute(array $data, Customer $customer)
    {
        $data['updated_by'] = Auth::user()->name ?? null;
        $customer->update($data);
        return $customer;
    }
}
