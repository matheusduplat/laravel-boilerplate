<?php

namespace App\Domains\Bill\Policy;

use App\Domains\Bill\Model\Bill;
use App\Domains\Customer\Model\Customer;
use App\Domains\Role\Enums\RoleDefaults;
use App\Domains\User\Model\User;

class BillPolicy
{
    public function indexAndWithPagination(User $user)
    {
        return $user->hasAnyPermission(['admin.bill.create', 'admin.bill.update', 'admin.bill.delete', 'admin.bill.read']);
    }

    public function show(User|Customer $user)
    {
        return $user->hasAnyRole([RoleDefaults::CLIENT]) || $user->hasAnyPermission(['admin.bill.create', 'admin.bill.update', 'admin.bill.delete', 'admin.bill.read']);
    }
    public function store(User $user)
    {
        return $user->hasAnyPermission(['admin.bill.create']);
    }
    public function update(User $user)
    {
        return $user->hasAnyPermission(['admin.bill.update']);
    }
    public function destroy(User $user)
    {
        return $user->hasAnyPermission(['admin.bill.delete']);
    }
    public function restore(User $user)
    {
        return $user->hasAnyPermission(['admin.bill.delete']);
    }
    public function toCustomer(Customer $user)
    {
        return $user->hasAnyRole([RoleDefaults::CLIENT]);
    }
    public function download(User|Customer $user, Bill $bill)
    {
        if ($user instanceof Customer && $user->id !== $bill->customer_id) {
            abort(403, __("You cannot download another customer's bill."));
        }

        return $user->hasAnyRole([RoleDefaults::CLIENT]) || $user->hasAnyPermission(['admin.bill.download']);
    }
}
