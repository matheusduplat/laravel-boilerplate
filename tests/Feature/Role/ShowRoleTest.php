<?php

use App\Domains\Employee\Model\Employee;
use App\Domains\Role\Model\Role;
use App\Domains\User\Model\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;

uses(RefreshDatabase::class);
beforeEach(function () {
    $this->seed();
});


describe('Show Role', function () {

    it('Retorna somente um função', function () {
        Sanctum::actingAs(
            User::query()->first(),
            ['*']
        );
        $role = Role::inRandomOrder()->first();
        $response = $this->getJson('api/role/show/' . $role->id);
        $response->assertStatus(200);
        $response->assertJsonFragment([
            'id' => $role->id,
            'name' => $role->name,
        ]);
    });
    it('Usuário não autenticado', function () {
        $role = Role::inRandomOrder()->first();
        $response = $this->getJson('api/role/show/' . $role->id);
        $response->assertStatus(403)->assertJson([
            'message' => 'Unauthenticated.',
        ]);
    });

    it('Usuário sem permissão', function () {
        $user =  User::factory()->for(Employee::factory(), 'userable')->create();

        Sanctum::actingAs(
            $user,
            ['*']
        );

        $role = Role::inRandomOrder()->first();
        $response = $this->getJson('api/role/show/' . $role->id);
        $response->assertStatus(403)->assertJson([
            'message' => __('This action is unauthorized.'),
        ]);
    });
});
