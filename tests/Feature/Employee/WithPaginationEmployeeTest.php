<?php

use App\Domains\Employee\Model\Employee;
use App\Domains\Phone\Model\Phone;
use App\Domains\Role\Model\Role;
use App\Domains\User\Model\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;

uses(RefreshDatabase::class);
beforeEach(function () {
    $this->seed();
    User::factory()
        ->count(5) // cria 5 usuários
        ->has(
            Employee::factory()
                ->has(
                    Phone::factory()->count(2), // 1 phone por employee
                    'phones'
                ),
            'employee' // nome da relação no User (hasOne)
        )
        ->administrador()
        ->create();
});


describe('With Pagination Employee', function () {

    it('Retorna a lista de funcionário com paginação', function () {
        Sanctum::actingAs(
            User::query()->first(),
            ['*']
        );
        $response = $this->getJson('api/employee/with-pagination?with_trashed=1');
        $response->assertStatus(200);
        $response->assertJsonCount(6, 'items');
        $response->assertJsonStructure([
            'items',
            'pagination_more',
            'perPage',
            'total',
            'lastPage',
            'currentPage',
        ]);
    });
    it('Usuário não autenticado', function () {
        $response = $this->getJson('api/employee/with-pagination');
        $response->assertStatus(403)->assertJson([
            'message' => 'Unauthenticated.',
        ]);
    });

    it('Usuário sem permissão', function () {
        $user =  User::Create([
            'name' => fake()->name(),
            'email' => fake()->email(),
            'password' => 'password',
            'email_verified_at' => now(),
        ]);

        Sanctum::actingAs(
            $user,
            ['*']
        );

        $response = $this->getJson('api/employee/with-pagination');
        $response->assertStatus(403)->assertJson([
            'message' => __('This action is unauthorized.'),
        ]);
    });
});
