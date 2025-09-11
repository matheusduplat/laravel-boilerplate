<?php


use App\Domains\Employee\Model\Employee;
use App\Domains\Phone\Model\Phone;
use App\Domains\User\Model\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;

uses(RefreshDatabase::class);
beforeEach(function () {
    $this->seed();
    $employee = Employee::factory()->has(Phone::factory()->count(1), 'phones')->create();
    User::factory()->for($employee, 'userable')->create();
    // $this->withoutExceptionHandling();
});


describe('Restore Employee', function () {

    it('Restaurando funcionário com sucesso', function () {
        Sanctum::actingAs(
            User::query()->first(),
            ['*']
        );

        $employee = Employee::query()->with(['user', 'phones'])->orderBy('id', 'desc')->first();
        $employee->delete();

        expect($employee->fresh()->trashed())->toBeTrue();


        $response = $this->putJson('api/employee/restore/' . $employee->id);
        $response->assertStatus(201)->assertJson([
            'message' => __('Employee restored successfully.'),
        ]);

        expect($employee->fresh()->trashed())->toBeFalse();
        // Garantir que user ainda está vinculado
        expect($employee->user)->not()->toBeNull();

        // Garantir que phones ainda existem
        expect($employee->phones)->toHaveCount(1);
    });

    it('Usuário não autenticado', function () {

        $employee = Employee::query()->first();


        $response = $this->putJson('api/employee/restore/' . $employee->id);
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

        $response = $this->putJson('api/employee/restore/' . $employee->id);
        $response->assertStatus(403)->assertJson([
            'message' => __('This action is unauthorized.'),
        ]);
    });

    it('Id do funcionário inválido', function () {

        Sanctum::actingAs(
            User::query()->first(),
            ['*']
        );


        $response = $this->putJson('api/employee/restore/66');
        $response->assertStatus(404)->assertJson([
            'message' => 'O registro de Employee com ID(s) 66 não foi encontrado.',
        ]);
    });
});
