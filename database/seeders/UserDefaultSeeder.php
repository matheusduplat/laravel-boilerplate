<?php

namespace Database\Seeders;

use App\Domains\Customer\Enums\CustomerStatus;
use App\Domains\Customer\Model\Customer;
use App\Domains\Employee\Enums\EmployeeStatus;
use App\Domains\Employee\Model\Employee;
use App\Domains\Role\Enums\RoleDefaults;
use App\Domains\Role\Model\Role;
use App\Domains\User\Model\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserDefaultSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        /**
         * Created Employee
         * 
         */


        $employee = Employee::updateOrCreate(
            [
                'name' => "Administrador",
            ],
            [
                "status" => EmployeeStatus::ACTIVE
            ]
        );
        $user =  $employee->user()->updateOrCreate(
            [
                'email' => 'admin@email.com.br',
            ],
            [
                'name' => 'Administrador',
                'password' => 'admin',
                'email_verified_at' => now(),
                'secure_login_email' => false,
                'first_access' => false,
            ]
        );
        $roles = Role::query()->where('name', RoleDefaults::ADMIN)->get()->pluck('id')->toArray();

        $user->auditAttach('roles', $roles);

        /**
         * Created Customer
         * 
         */
        $customer = Customer::updateOrCreate(
            [
                "cpf" => "123.456.789-00",
            ],
            [
                'name' => fake()->name(),
                "birth_date" => "2000-01-01",
                'status' => CustomerStatus::ACTIVE
            ]
        );
        $userCustomer =  $customer->user()->updateOrCreate(
            [
                'email' => 'customer@email.com.br',
            ],
            [
                'name' => 'Administrador',
                'password' => 'admin',
                'email_verified_at' => now(),
                'secure_login_email' => false,
                'first_access' => false,
            ]
        );
        $roles = Role::query()->where('name', RoleDefaults::CLIENT)->get()->pluck('id')->toArray();
        $userCustomer->auditAttach('roles', $roles);
    }
}
