<?php

use App\Domains\Employee\Model\Employee;
use App\Domains\Permission\Model\Permission;
use App\Domains\User\Model\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;

uses(RefreshDatabase::class);
beforeEach(function () {
    $this->seed();
    $this->data = [
        'name' => fake()->jobTitle(),
        'permissions' => Permission::all()->random(3)->pluck('id')->toArray(),
    ];
});


describe('Create Role', function () {

    $url = 'api/role';

    it('Criando função com sucesso', function () use ($url) {
        Sanctum::actingAs(
            User::query()->first(),
            ['*']
        );
        $response = $this->postJson("{$url}/store", $this->data);
        $response->assertStatus(200)->assertJson([
            'message' => __('Role created successfully.'),
        ]);
        $this->assertDatabaseHas('roles', [
            'name' => $this->data['name'],
        ]);
        $this->assertDatabaseHas('role_has_permissions', [
            'role_id' => 3,
            'permission_id' => $this->data['permissions'][0],
        ]);
        $this->assertDatabaseHas('role_has_permissions', [
            'role_id' => 3,
            'permission_id' => $this->data['permissions'][1],
        ]);
        $this->assertDatabaseHas('role_has_permissions', [
            'role_id' => 3,
            'permission_id' => $this->data['permissions'][2],
        ]);
    });

    it('Não enviando dados nenhum para criar um novo função', function () use ($url) {
        Sanctum::actingAs(
            User::query()->first(),
            ['*']
        );
        $response = $this->postJson("{$url}/store", []);
        $response->assertStatus(422)->assertJsonStructure([
            'message',
            'errors'
        ]);
    });


    it('Usuário não autenticado', function () use ($url) {
        $response = $this->postJson("{$url}/store", $this->data);
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

        $response = $this->postJson("{$url}/store", $this->data);
        $response->assertStatus(403)->assertJson([
            'message' => __('This action is unauthorized.'),
        ]);
    });
});
