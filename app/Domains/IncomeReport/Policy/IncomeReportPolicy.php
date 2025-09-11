<?php

namespace App\Domains\IncomeReport\Policy;

use App\Domains\Customer\Model\Customer;
use App\Domains\IncomeReport\Model\IncomeReport;
use App\Domains\Role\Enums\RoleDefaults;
use App\Domains\User\Model\User;

class IncomeReportPolicy
{
    public function indexAndWithPagination(User $user)
    {
        return $user->hasAnyPermission(['admin.incomeReport.create', 'admin.incomeReport.update', 'admin.incomeReport.delete', 'admin.incomeReport.read']);
    }

    public function show(User|Customer $user)
    {
        return $user->hasAnyRole([RoleDefaults::CLIENT]) || $user->hasAnyPermission(['admin.incomeReport.create', 'admin.incomeReport.update', 'admin.incomeReport.delete', 'admin.incomeReport.read']);
    }
    public function store(User $user)
    {
        return $user->hasAnyPermission(['admin.incomeReport.create']);
    }
    public function update(User $user)
    {
        return $user->hasAnyPermission(['admin.incomeReport.update']);
    }
    public function destroy(User $user)
    {
        return $user->hasAnyPermission(['admin.incomeReport.delete']);
    }
    public function restore(User $user)
    {
        return $user->hasAnyPermission(['admin.incomeReport.delete']);
    }
    public function toCustomer(Customer $user)
    {
        return $user->hasAnyRole([RoleDefaults::CLIENT]);
    }
    public function download(User|Customer $user, IncomeReport $incomeReport)
    {
        if ($user instanceof Customer && $user->id !== $incomeReport->customer_id) {
            abort(403, __("You cannot download another customer's incomeReport."));
        }

        return $user->hasAnyRole([RoleDefaults::CLIENT]) || $user->hasAnyPermission(['admin.incomeReport.download']);
    }
}
