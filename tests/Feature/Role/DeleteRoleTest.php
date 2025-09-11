<?php

use App\Domains\Employee\Model\Employee;
use App\Domains\Permission\Model\Permission;
use App\Domains\Role\Model\Role;
use App\Domains\User\Model\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;

uses(RefreshDatabase::class);
beforeEach(function () {
    $this->seed();
    $this->data = [
        'role_id' => Role::factory()->hasAttached(Permission::get()->random(3), [], 'permissions')->create()->id
    ];
});


describe('Delete Role', function () {

    $url = "api/role/destroy";
    it('Deletando função com sucesso', function () use ($url) {
        Sanctum::actingAs(
            User::query()->first(),
            ['*']
        );

        $response = $this->deleteJson("{$url}/{$this->data['role_id']}");
        $response->assertStatus(200)->assertJson([
            'message' => __('Role deleted successfully.'),
        ]);
        $this->assertSoftDeleted('roles', [
            'id' => $this->data['role_id'],
        ]);
        $this->assertDatabaseHas('role_has_permissions', [
            'role_id' => $this->data['role_id'],

        ]);
        $this->assertDatabaseHas('role_has_permissions', [
            'role_id' => $this->data['role_id'],

        ]);
        $this->assertDatabaseHas('role_has_permissions', [
            'role_id' => $this->data['role_id'],

        ]);
    });

    it('Tentando deletar a permissão Administrador', function () use ($url) {
        Sanctum::actingAs(
            User::query()->first(),
            ['*']
        );

        $role = Role::where('name', 'Administrador')->first();

        $response = $this->deleteJson("{$url}/{$role->id}");
        $response->assertStatus(403)->assertJson([
            'message' => __('This action is unauthorized.'),
        ]);
    });

    it('Usuário não autenticado', function () use ($url) {
        $role = Role::factory()->create();

        $response = $this->deleteJson("{$url}/{$this->data['role_id']}");
        $response->assertStatus(403)->assertJson([
            'message' => 'Unauthenticated.',
        ]);
    });

    it('Usuário sem permissão', function () use ($url) {
        $user =  User::factory()->for(Employee::factory(), 'userable')->create();

        Sanctum::actingAs(
            $user,
            ['*']
        );


        $response = $this->deleteJson("{$url}/{$this->data['role_id']}");
        $response->assertStatus(403)->assertJson([
            'message' => __('This action is unauthorized.'),
        ]);
    });
});
