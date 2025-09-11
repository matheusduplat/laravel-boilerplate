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

    $employee = Employee::factory()->has(Phone::factory()->count(1), 'phones')->create();
    User::factory()->for($employee, 'userable')->create();
});


describe('Delete Employee', function () {

    it('Deletado funcionário com sucesso', function () {
        Sanctum::actingAs(
            User::query()->first(),
            ['*']
        );

        $employee = Employee::query()->with(['user'])->orderBy('id', 'desc')->first();


        $response = $this->deleteJson('api/employee/destroy/' . $employee->id);
        $response->assertStatus(201)->assertJson([
            'message' => __('Employee deleted successfully.'),
        ]);
        $this->assertSoftDeleted('users', [
            'id' => $employee->user->id
        ]);
        $this->assertSoftDeleted('employees', [
            'id' => $employee->id
        ]);
        $this->assertSoftDeleted('phones', [
            'phoneable_id' => $employee->id,
            'phoneable_type' => 'Employee'
        ]);
    });

    it('Usuário não autenticado', function () {

        $employee = Employee::query()->first();


        $response = $this->deleteJson('api/employee/destroy/' . $employee->id, []);
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

        $employee = Employee::query()->first();

        $response = $this->deleteJson('api/employee/destroy/' . $employee->id);
        $response->assertStatus(403)->assertJson([
            'message' => __('This action is unauthorized.'),
        ]);
    });

    it('Id do funcionário inválido', function () {

        Sanctum::actingAs(
            User::query()->first(),
            ['*']
        );


        $response = $this->deleteJson('api/employee/destroy/66');
        $response->assertStatus(404)->assertJson([
            'message' => 'O registro de Employee com ID(s) 66 não foi encontrado.',
        ]);
    });
});
