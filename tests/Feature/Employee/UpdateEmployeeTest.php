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
    $role = Role::query()->where('name', 'Administrador')->first();

    $this->data = [
        'name' => fake()->name(),
        'email' => fake()->email(),
        'role' => $role->id,
        'phones' => Phone::factory()->count(1)->make()->toArray(),
    ];

    $user = User::factory()->create();

    $employee = Employee::factory()->has(Phone::factory()->count(1), 'phones')->create([
        'user_id' => $user->id
    ]);
});


describe('Update Employee', function () {

    it('Atualizando um funcionário com sucesso', function () {
        Sanctum::actingAs(
            User::query()->first(),
            ['*']
        );

        $employee = Employee::query()->first();


        $response = $this->postJson('api/employee/update/' . $employee->id, $this->data);
        $response->assertStatus(201)->assertJson([
            'message' => __('Employee updated successfully.'),
        ]);
        $this->assertDatabaseHas('users', [
            'email' => $this->data['email'],
            'id' => $employee->user_id
        ]);
        $this->assertDatabaseHas('employees', [
            'name' => $this->data['name'],
            'user_id' => $employee->user_id
        ]);
        $this->assertDatabaseHas('phones', [
            'number' => $this->data['phones'][0]['number'],
            'type' => $this->data['phones'][0]['type'],
            'phoneable_id' => $employee->id,
            'phoneable_type' => 'Employee'
        ]);
    });

    it('Não enviando dados nenhum para criar um novo funcionário', function () {

        Sanctum::actingAs(
            User::query()->first(),
            ['*']
        );

        $employee = Employee::query()->first();

        $response = $this->postJson('api/employee/update/' . $employee->id, []);
        $response->assertStatus(422)->assertJsonStructure([
            'message',
            'errors'
        ]);
    });

    it('Usuário não autenticado', function () {

        $employee = Employee::query()->first();


        $response = $this->postJson('api/employee/update/' . $employee->id, []);
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

        $employee = Employee::query()->first();

        $response = $this->postJson('api/employee/update/' . $employee->id, $this->data);
        $response->assertStatus(403)->assertJson([
            'message' => __('This action is unauthorized.'),
        ]);
    });

    it('Id do funcionário inválido', function () {

        Sanctum::actingAs(
            User::query()->first(),
            ['*']
        );


        $response = $this->postJson('api/employee/update/66', $this->data);
        $response->assertStatus(404)->assertJson([
            'message' => __('exceptions.model_not_found', ['model' => 'Employee', 'ids' => "66"]),
        ]);
    });
});
