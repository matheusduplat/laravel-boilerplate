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

        // Permission::updateOrCreate(
        //     [
        //         'name' => 'admin.customer.create'
        //     ],
        //     [
        //         'description' => 'Acesso a cadastrar Cliente',
        //         'action' => 'create',
        //         'subject' => 'adminCustomer'
        //     ]
        // );
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
        // Permission::updateOrCreate(
        //     [
        //         'name' => 'admin.customer.delete'
        //     ],
        //     [
        //         'description' => 'Acesso a deletar Cliente',
        //         'action' => 'delete',
        //         'subject' => 'adminCustomer'
        //     ]
        // );
        // Permission::updateOrCreate(
        //     [
        //         'name' => 'admin.customer.restore'
        //     ],
        //     [
        //         'description' => 'Acesso a restaurar Cliente',
        //         'action' => 'restore',
        //         'subject' => 'adminCustomer'
        //     ]
        // );
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

        //bill
        Permission::updateOrCreate(
            [
                'name' => 'admin.bill'
            ],
            [
                'description' => 'Acesso a tudo de Boletos',
                'action' => 'manage',
                'subject' => 'adminBill'
            ]
        );

        // Permission::updateOrCreate(
        //     [
        //         'name' => 'admin.bill.create'
        //     ],
        //     [
        //         'description' => 'Acesso a cadastrar Boletos',
        //         'action' => 'create',
        //         'subject' => 'adminBill'
        //     ]
        // );
        // Permission::updateOrCreate(
        //     [
        //         'name' => 'admin.bill.update'
        //     ],
        //     [
        //         'description' => 'Acesso a editar Boletos',
        //         'action' => 'update',
        //         'subject' => 'adminBill'
        //     ]
        // );
        // Permission::updateOrCreate(
        //     [
        //         'name' => 'admin.bill.delete'
        //     ],
        //     [
        //         'description' => 'Acesso a deletar Boletos',
        //         'action' => 'delete',
        //         'subject' => 'adminBill'
        //     ]
        // );
        // Permission::updateOrCreate(
        //     [
        //         'name' => 'admin.bill.restore'
        //     ],
        //     [
        //         'description' => 'Acesso a restaurar Boletos',
        //         'action' => 'restore',
        //         'subject' => 'adminBill'
        //     ]
        // );
        Permission::updateOrCreate(
            [
                'name' => 'admin.bill.read'
            ],
            [
                'description' => 'Acesso a visualizar Boletos',
                'action' => 'read',
                'subject' => 'adminBill'
            ]
        );
        Permission::updateOrCreate(
            [
                'name' => 'admin.bill.download'
            ],
            [
                'description' => 'Acesso a baixar Boletos',
                'action' => 'download',
                'subject' => 'adminBill'
            ]
        );

        //income report
        Permission::updateOrCreate(
            [
                'name' => 'admin.incomeReport'
            ],
            [
                'description' => 'Acesso a tudo de imposto de renda',
                'action' => 'manage',
                'subject' => 'adminIncomeReport'
            ]
        );

        // Permission::updateOrCreate(
        //     [
        //         'name' => 'admin.incomeReport.create'
        //     ],
        //     [
        //         'description' => 'Acesso a cadastrar imposto de renda',
        //         'action' => 'create',
        //         'subject' => 'adminIncomeReport'
        //     ]
        // );
        // Permission::updateOrCreate(
        //     [
        //         'name' => 'admin.incomeReport.update'
        //     ],
        //     [
        //         'description' => 'Acesso a editar imposto de renda',
        //         'action' => 'update',
        //         'subject' => 'adminIncomeReport'
        //     ]
        // );
        // Permission::updateOrCreate(
        //     [
        //         'name' => 'admin.incomeReport.delete'
        //     ],
        //     [
        //         'description' => 'Acesso a deletar imposto de renda',
        //         'action' => 'delete',
        //         'subject' => 'adminIncomeReport'
        //     ]
        // );
        // Permission::updateOrCreate(
        //     [
        //         'name' => 'admin.incomeReport.restore'
        //     ],
        //     [
        //         'description' => 'Acesso a restaurar imposto de renda',
        //         'action' => 'restore',
        //         'subject' => 'adminIncomeReport'
        //     ]
        // );
        Permission::updateOrCreate(
            [
                'name' => 'admin.incomeReport.read'
            ],
            [
                'description' => 'Acesso a visualizar imposto de renda',
                'action' => 'read',
                'subject' => 'adminIncomeReport'
            ]
        );
        Permission::updateOrCreate(
            [
                'name' => 'admin.incomeReport.download'
            ],
            [
                'description' => 'Acesso a baixar imposto de renda',
                'action' => 'download',
                'subject' => 'adminIncomeReport'
            ]
        );

        //copaticipation
        Permission::updateOrCreate(
            [
                'name' => 'admin.copaticipation'
            ],
            [
                'description' => 'Acesso a tudo de co-participação',
                'action' => 'manage',
                'subject' => 'adminCopaticipation'
            ]
        );

        // Permission::updateOrCreate(
        //     [
        //         'name' => 'admin.copaticipation.create'
        //     ],
        //     [
        //         'description' => 'Acesso a cadastrar co-participação',
        //         'action' => 'create',
        //         'subject' => 'adminCopaticipation'
        //     ]
        // );
        // Permission::updateOrCreate(
        //     [
        //         'name' => 'admin.copaticipation.update'
        //     ],
        //     [
        //         'description' => 'Acesso a editar co-participação',
        //         'action' => 'update',
        //         'subject' => 'adminCopaticipation'
        //     ]
        // );
        // Permission::updateOrCreate(
        //     [
        //         'name' => 'admin.copaticipation.delete'
        //     ],
        //     [
        //         'description' => 'Acesso a deletar co-participação',
        //         'action' => 'delete',
        //         'subject' => 'adminCopaticipation'
        //     ]
        // );
        // Permission::updateOrCreate(
        //     [
        //         'name' => 'admin.copaticipation.restore'
        //     ],
        //     [
        //         'description' => 'Acesso a restaurar co-participação',
        //         'action' => 'restore',
        //         'subject' => 'adminCopaticipation'
        //     ]
        // );
        Permission::updateOrCreate(
            [
                'name' => 'admin.copaticipation.read'
            ],
            [
                'description' => 'Acesso a visualizar co-participação',
                'action' => 'read',
                'subject' => 'adminCopaticipation'
            ]
        );
        Permission::updateOrCreate(
            [
                'name' => 'admin.copaticipation.download'
            ],
            [
                'description' => 'Acesso a baixar co-participação',
                'action' => 'download',
                'subject' => 'adminCopaticipation'
            ]
        );

        //digital wallet
        Permission::updateOrCreate(
            [
                'name' => 'admin.digitalWallet'
            ],
            [
                'description' => 'Acesso a tudo de carteirinha digital',
                'action' => 'manage',
                'subject' => 'adminDigitalWallet'
            ]
        );

        // Permission::updateOrCreate(
        //     [
        //         'name' => 'admin.digitalWallet.create'
        //     ],
        //     [
        //         'description' => 'Acesso a cadastrar carteirinha digital',
        //         'action' => 'create',
        //         'subject' => 'adminDigitalWallet'
        //     ]
        // );
        // Permission::updateOrCreate(
        //     [
        //         'name' => 'admin.digitalWallet.update'
        //     ],
        //     [
        //         'description' => 'Acesso a editar carteirinha digital',
        //         'action' => 'update',
        //         'subject' => 'adminDigitalWallet'
        //     ]
        // );
        // Permission::updateOrCreate(
        //     [
        //         'name' => 'admin.digitalWallet.delete'
        //     ],
        //     [
        //         'description' => 'Acesso a deletar carteirinha digital',
        //         'action' => 'delete',
        //         'subject' => 'adminDigitalWallet'
        //     ]
        // );
        // Permission::updateOrCreate(
        //     [
        //         'name' => 'admin.digitalWallet.restore'
        //     ],
        //     [
        //         'description' => 'Acesso a restaurar carteirinha digital',
        //         'action' => 'restore',
        //         'subject' => 'adminDigitalWallet'
        //     ]
        // );
        Permission::updateOrCreate(
            [
                'name' => 'admin.digitalWallet.read'
            ],
            [
                'description' => 'Acesso a visualizar carteirinha digital',
                'action' => 'read',
                'subject' => 'adminDigitalWallet'
            ]
        );
        //accredited Networks
        Permission::updateOrCreate(
            [
                'name' => 'admin.accreditedNetworks'
            ],
            [
                'description' => 'Acesso a tudo de rede credenciada',
                'action' => 'manage',
                'subject' => 'adminAccreditedNetworks'
            ]
        );

        // Permission::updateOrCreate(
        //     [
        //         'name' => 'admin.accreditedNetworks.create'
        //     ],
        //     [
        //         'description' => 'Acesso a cadastrar rede credenciada',
        //         'action' => 'create',
        //         'subject' => 'adminAccreditedNetworks'
        //     ]
        // );
        // Permission::updateOrCreate(
        //     [
        //         'name' => 'admin.accreditedNetworks.update'
        //     ],
        //     [
        //         'description' => 'Acesso a editar rede credenciada',
        //         'action' => 'update',
        //         'subject' => 'adminAccreditedNetworks'
        //     ]
        // );
        // Permission::updateOrCreate(
        //     [
        //         'name' => 'admin.accreditedNetworks.delete'
        //     ],
        //     [
        //         'description' => 'Acesso a deletar rede credenciada',
        //         'action' => 'delete',
        //         'subject' => 'adminAccreditedNetworks'
        //     ]
        // );
        // Permission::updateOrCreate(
        //     [
        //         'name' => 'admin.accreditedNetworks.restore'
        //     ],
        //     [
        //         'description' => 'Acesso a restaurar rede credenciada',
        //         'action' => 'restore',
        //         'subject' => 'adminAccreditedNetworks'
        //     ]
        // );
        Permission::updateOrCreate(
            [
                'name' => 'admin.accreditedNetworks.read'
            ],
            [
                'description' => 'Acesso a visualizar rede credenciada',
                'action' => 'read',
                'subject' => 'adminAccreditedNetworks'
            ]
        );

        //RequestManagement
        Permission::updateOrCreate(
            [
                'name' => 'admin.RequestManagement'
            ],
            [
                'description' => 'Acesso a tudo de gerenciamento de solicitações',
                'action' => 'manage',
                'subject' => 'adminRequestManagement'
            ]
        );

        Permission::updateOrCreate(
            [
                'name' => 'admin.RequestManagement.create'
            ],
            [
                'description' => 'Acesso a cadastrar gerenciamento de solicitações',
                'action' => 'create',
                'subject' => 'adminRequestManagement'
            ]
        );
        Permission::updateOrCreate(
            [
                'name' => 'admin.RequestManagement.update'
            ],
            [
                'description' => 'Acesso a editar gerenciamento de solicitações',
                'action' => 'update',
                'subject' => 'adminRequestManagement'
            ]
        );
        Permission::updateOrCreate(
            [
                'name' => 'admin.RequestManagement.delete'
            ],
            [
                'description' => 'Acesso a deletar gerenciamento de solicitações',
                'action' => 'delete',
                'subject' => 'adminRequestManagement'
            ]
        );
        Permission::updateOrCreate(
            [
                'name' => 'admin.RequestManagement.restore'
            ],
            [
                'description' => 'Acesso a restaurar gerenciamento de solicitações',
                'action' => 'restore',
                'subject' => 'adminRequestManagement'
            ]
        );
        Permission::updateOrCreate(
            [
                'name' => 'admin.RequestManagement.read'
            ],
            [
                'description' => 'Acesso a visualizar gerenciamento de solicitações',
                'action' => 'read',
                'subject' => 'adminRequestManagement'
            ]
        );

        //contact
        Permission::updateOrCreate(
            [
                'name' => 'admin.contact'
            ],
            [
                'description' => 'Acesso a tudo de canal de contato',
                'action' => 'manage',
                'subject' => 'adminContact'
            ]
        );

        Permission::updateOrCreate(
            [
                'name' => 'admin.contact.create'
            ],
            [
                'description' => 'Acesso a cadastrar canal de contato',
                'action' => 'create',
                'subject' => 'adminContact'
            ]
        );
        Permission::updateOrCreate(
            [
                'name' => 'admin.contact.update'
            ],
            [
                'description' => 'Acesso a editar canal de contato',
                'action' => 'update',
                'subject' => 'adminContact'
            ]
        );
        Permission::updateOrCreate(
            [
                'name' => 'admin.contact.delete'
            ],
            [
                'description' => 'Acesso a deletar canal de contato',
                'action' => 'delete',
                'subject' => 'adminContact'
            ]
        );
        Permission::updateOrCreate(
            [
                'name' => 'admin.contact.restore'
            ],
            [
                'description' => 'Acesso a restaurar canal de contato',
                'action' => 'restore',
                'subject' => 'adminContact'
            ]
        );
        Permission::updateOrCreate(
            [
                'name' => 'admin.contact.read'
            ],
            [
                'description' => 'Acesso a visualizar canal de contato',
                'action' => 'read',
                'subject' => 'adminContact'
            ]
        );
    }
}
