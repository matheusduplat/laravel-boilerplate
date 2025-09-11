<?php

namespace App\Domains\DigitalWallet\Policy;

use App\Domains\Customer\Model\Customer;
use App\Domains\Role\Enums\RoleDefaults;
use App\Domains\User\Model\User;

class DigitalWalletPolicy
{
    public function indexAndWithPagination(User $user)
    {
        return $user->hasAnyPermission(['admin.digitalWallet.create', 'admin.digitalWallet.update', 'admin.digitalWallet.delete', 'admin.digitalWallet.read']);
    }

    public function show(User|Customer $user)
    {
        return $user->hasAnyRole([RoleDefaults::CLIENT]) || $user->hasAnyPermission(['admin.digitalWallet.create', 'admin.digitalWallet.update', 'admin.digitalWallet.delete', 'admin.digitalWallet.read']);
    }
    public function store(User $user)
    {
        return $user->hasAnyPermission(['admin.digitalWallet.create']);
    }
    public function update(User $user)
    {
        return $user->hasAnyPermission(['admin.digitalWallet.update']);
    }
    public function destroy(User $user)
    {
        return $user->hasAnyPermission(['admin.digitalWallet.delete']);
    }
    public function restore(User $user)
    {
        return $user->hasAnyPermission(['admin.digitalWallet.delete']);
    }
    public function toCustomer(Customer $user)
    {
        return $user->hasAnyRole([RoleDefaults::CLIENT]);
    }
}
