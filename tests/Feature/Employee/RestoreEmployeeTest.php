<?php


use App\Domains\Employee\Model\Employee;
use App\Domains\Phone\Model\Phone;
use App\Domains\User\Model\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;

uses(RefreshDatabase::class);
beforeEach(function () {
    $this->seed();
    $user = User::factory()->create();
    $employee = Employee::factory()->has(Phone::factory()->count(1), 'phones')->create([
        'user_id' => $user->id
    ]);
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


        $response = $this->getJson('api/employee/restore/' . $employee->id);
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


        $response = $this->getJson('api/employee/restore/' . $employee->id);
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

        $response = $this->getJson('api/employee/restore/' . $employee->id);
        $response->assertStatus(403)->assertJson([
            'message' => __('This action is unauthorized.'),
        ]);
    });

    it('Id do funcionário inválido', function () {

        Sanctum::actingAs(
            User::query()->first(),
            ['*']
        );


        $response = $this->getJson('api/employee/restore/66');
        $response->assertStatus(404)->assertJson([
            'message' => 'O registro de Employee com ID(s) 66 não foi encontrado.',
        ]);
    });
});
