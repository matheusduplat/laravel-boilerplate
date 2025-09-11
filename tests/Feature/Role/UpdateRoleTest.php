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
        'name' => fake()->jobTitle(),
        'permissions' => Permission::get()->random(3)->pluck('id')->toArray(),
    ];
});


describe('Update Role', function () {

    $url = "api/role/update";
    it('Atualizando função com sucesso', function () use ($url) {
        Sanctum::actingAs(
            User::query()->first(),
            ['*']
        );
        $role = Role::factory()->create();

        $response = $this->postJson("{$url}/{$role->id}", $this->data);
        $response->assertStatus(200)->assertJson([
            'message' => __('Role updated successfully.'),
        ]);
        $this->assertDatabaseHas('roles', [
            'name' => $this->data['name'],
        ]);
        $this->assertDatabaseHas('role_has_permissions', [
            'role_id' => $role->id,
            'permission_id' => $this->data['permissions'][0],
        ]);
        $this->assertDatabaseHas('role_has_permissions', [
            'role_id' => $role->id,
            'permission_id' => $this->data['permissions'][1],
        ]);
        $this->assertDatabaseHas('role_has_permissions', [
            'role_id' => $role->id,
            'permission_id' => $this->data['permissions'][2],
        ]);
    });

    it('Não enviando dados nenhum para atualizar uma função', function () use ($url) {
        Sanctum::actingAs(
            User::query()->first(),
            ['*']
        );
        $role = Role::factory()->create();

        $response = $this->postJson("{$url}/{$role->id}", []);
        $response->assertStatus(422)->assertJsonStructure([
            'message',
            'errors'
        ]);
    });


    it('Tentando atualizar a permissão Administrador', function () use ($url) {
        Sanctum::actingAs(
            User::query()->first(),
            ['*']
        );

        $role = Role::where('name', 'Administrador')->first();

        $response = $this->postJson("{$url}/{$role->id}", $this->data);
        $response->assertStatus(403)->assertJson([
            'message' => __('This action is unauthorized.'),
        ]);
    });

    it('Usuário não autenticado', function () use ($url) {
        $role = Role::factory()->create();

        $response = $this->postJson("{$url}/{$role->id}", $this->data);
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
        $role = Role::factory()->create();


        $response = $this->postJson("{$url}/{$role->id}", $this->data);
        $response->assertStatus(403)->assertJson([
            'message' => __('This action is unauthorized.'),
        ]);
    });
});
