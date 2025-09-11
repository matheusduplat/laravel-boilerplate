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

    $employee = Employee::factory()
        ->has(
            Phone::factory()->count(2), // 1 phone por employee
            'phones'
        )
        ->has(
            User::factory(), // 1 user por employee
            'user'
        )->create();
});


describe('Show Employee', function () {

    it('Retorna somente um funcionário', function () {
        Sanctum::actingAs(
            User::query()->first(),
            ['*']
        );
        $employee = Employee::with(['phones', 'user'])->inRandomOrder()->first();
        $response = $this->getJson('api/employee/show/' . $employee->id);
        $response->assertStatus(200);
        $response->assertJsonFragment([
            'id' => $employee->id,
            'name' => $employee->name,
        ]);
    });
    it('Usuário não autenticado', function () {
        $response = $this->getJson('api/employee');
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

        $response = $this->getJson('api/employee');
        $response->assertStatus(403)->assertJson([
            'message' => __('This action is unauthorized.'),
        ]);
    });
});
