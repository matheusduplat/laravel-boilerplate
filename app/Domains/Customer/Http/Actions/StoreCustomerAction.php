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
            'first_access' => true,
            'status' => CustomerStatus::ACTIVE,
            'password' => $data['password'] ?? $this->generatePasswordByCpf($data['cpf']),
        ]);

        if (isset($data['email'])) {
            $customer->sendEmailVerificationNotification();
        }

        $roles = Role::firstWhere('name', RoleDefaults::CLIENT)?->id;

        $customer->auditAttach('roles', $roles);

        return $customer;
    }

    protected function generatePasswordByCpf(string $cpf): string
    {
        $cpf = str_replace(['.', '-', '_'], '', $cpf);
        return substr($cpf, 0, 6);
    }
}
