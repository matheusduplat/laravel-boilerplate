<?php

namespace Database\Seeders;

use App\Domains\Permission\Model\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //employee
        Permission::updateOrCreate(
            [
                'name' => 'admin.employee'
            ],
            [
                'description' => 'Acesso a tudo de Funcionários',
                'action' => 'manage',
                'subject' => 'adminEmployee'
            ]
        );

        Permission::updateOrCreate(
            [
                'name' => 'admin.employee.create'
            ],
            [
                'description' => 'Acesso a cadastrar Funcionários',
                'action' => 'create',
                'subject' => 'adminEmployee'
            ]
        );
        Permission::updateOrCreate(
            [
                'name' => 'admin.employee.update'
            ],
            [
                'description' => 'Acesso a editar Funcionários',
                'action' => 'update',
                'subject' => 'adminEmployee'
            ]
        );
        Permission::updateOrCreate(
            [
                'name' => 'admin.employee.delete'
            ],
            [
                'description' => 'Acesso a deletar Funcionários',
                'action' => 'delete',
                'subject' => 'adminEmployee'
            ]
        );
        Permission::updateOrCreate(
            [
                'name' => 'admin.employee.restore'
            ],
            [
                'description' => 'Acesso a restaurar/ativar Funcionários',
                'action' => 'restore',
                'subject' => 'adminEmployee'
            ]
        );
        Permission::updateOrCreate(
            [
                'name' => 'admin.employee.read'
            ],
            [
                'description' => 'Acesso a visualizar Funcionários',
                'action' => 'read',
                'subject' => 'adminEmployee'
            ]
        );
        // profile
        Permission::updateOrCreate(
            [
                'name' => 'admin.profile.read'
            ],
            [
                'description' => 'Acesso ao perfil',
                'action' => 'read',
                'subject' => 'profile'
            ]
        );
        //roles
        Permission::updateOrCreate(
            [
                'name' => 'admin.role'
            ],
            [
                'description' => 'Acesso a tudo de Função',
                'action' => 'manage',
                'subject' => 'adminRole'
            ]
        );

        Permission::updateOrCreate(
            [
                'name' => 'admin.role.create'
            ],
            [
                'description' => 'Acesso a cadastrar Função',
                'action' => 'create',
                'subject' => 'adminRole'
            ]
        );
        Permission::updateOrCreate(
            [
                'name' => 'admin.role.update'
            ],
            [
                'description' => 'Acesso a editar Função',
                'action' => 'update',
                'subject' => 'adminRole'
            ]
        );
        Permission::updateOrCreate(
            [
                'name' => 'admin.role.delete'
            ],
            [
                'description' => 'Acesso a deletar Função',
                'action' => 'delete',
                'subject' => 'adminRole'
            ]
        );
        Permission::updateOrCreate(
            [
                'name' => 'admin.role.restore'
            ],
            [
                'description' => 'Acesso a restaurar Função',
                'action' => 'restore',
                'subject' => 'adminRole'
            ]
        );
        Permission::updateOrCreate(
            [
                'name' => 'admin.role.read'
            ],
            [
                'description' => 'Acesso a visualizar Função',
                'action' => 'read',
                'subject' => 'adminRole'
            ]
        );
        //customer
        Permission::updateOrCreate(
            [
                'name' => 'admin.customer'
            ],
            [
                'description' => 'Acesso a tudo de Cliente',
                'action' => 'manage',
                'subject' => 'adminCustomer'
            ]
        );

        Permission::updateOrCreate(
            [
                'name' => 'admin.customer.create'
            ],
            [
                'description' => 'Acesso a cadastrar Cliente',
                'action' => 'create',
                'subject' => 'adminCustomer'
            ]
        );
        Permission::updateOrCreate(
            [
                'name' => 'admin.customer.update'
            ],
            [
                'description' => 'Acesso a editar Cliente',
                'action' => 'update',
                'subject' => 'adminCustomer'
            ]
        );
        Permission::updateOrCreate(
            [
                'name' => 'admin.customer.delete'
            ],
            [
                'description' => 'Acesso a deletar Cliente',
                'action' => 'delete',
                'subject' => 'adminCustomer'
            ]
        );
        Permission::updateOrCreate(
            [
                'name' => 'admin.customer.restore'
            ],
            [
                'description' => 'Acesso a restaurar Cliente',
                'action' => 'restore',
                'subject' => 'adminCustomer'
            ]
        );
        Permission::updateOrCreate(
            [
                'name' => 'admin.customer.read'
            ],
            [
                'description' => 'Acesso a visualizar Cliente',
                'action' => 'read',
                'subject' => 'adminCustomer'
            ]
        );
    }
}
