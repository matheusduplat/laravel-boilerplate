<?php

namespace App\Domains\Copaticipation\Policy;

use App\Domains\Copaticipation\Model\Copaticipation;
use App\Domains\Customer\Model\Customer;
use App\Domains\Role\Enums\RoleDefaults;
use App\Domains\User\Model\User;

class CopaticipationPolicy
{
    public function indexAndWithPagination(User $user)
    {
        return $user->hasAnyPermission(['admin.copaticipation.create', 'admin.copaticipation.update', 'admin.copaticipation.delete', 'admin.copaticipation.read']);
    }

    public function show(User|Customer $user)
    {
        return $user->hasAnyRole([RoleDefaults::CLIENT]) || $user->hasAnyPermission(['admin.copaticipation.create', 'admin.copaticipation.update', 'admin.copaticipation.delete', 'admin.copaticipation.read']);
    }
    public function store(User $user)
    {
        return $user->hasAnyPermission(['admin.copaticipation.create']);
    }
    public function update(User $user)
    {
        return $user->hasAnyPermission(['admin.copaticipation.update']);
    }
    public function destroy(User $user)
    {
        return $user->hasAnyPermission(['admin.copaticipation.delete']);
    }
    public function restore(User $user)
    {
        return $user->hasAnyPermission(['admin.copaticipation.delete']);
    }
    public function toCustomer(Customer $user)
    {
        return $user->hasAnyRole([RoleDefaults::CLIENT]);
    }
    public function download(User|Customer $user, Copaticipation $copaticipation)
    {
        if ($user instanceof Customer && $user->id !== $copaticipation->customer_id) {
            abort(403, __("You cannot download another customer's copaticipation."));
        }

        return $user->hasAnyRole([RoleDefaults::CLIENT]) || $user->hasAnyPermission(['admin.copaticipation.download']);
    }
}
