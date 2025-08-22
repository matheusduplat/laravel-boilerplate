<?php

namespace App\Domains\Customer\Http\Actions;

use App\Domains\Customer\Enums\CustomerStatus;
use App\Domains\Customer\Model\Customer;


class RestoreCustomerAction
{
    public function execute(Customer $customer)
    {
        $data['deleted_by'] = null;
        $data['status'] = CustomerStatus::ACTIVE;
        $customer->update($data);
        $customer->restore();
        return $customer;
    }
}
