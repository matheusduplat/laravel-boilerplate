<?php

use App\Domains\Employee\Model\Employee;
use App\Domains\Phone\Model\Phone;
use App\Domains\Role\Model\Role;
use App\Domains\User\Model\User;
use App\Notifications\CreatePasswordNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Laravel\Sanctum\Sanctum;

uses(RefreshDatabase::class);
beforeEach(function () {
    $this->seed();
    $role = Role::query()->where('name', 'Administrador')->first();

    $this->data = [
        'name' => fake()->name(),
        'email' => fake()->email(),
        'role' => $role->id,
        'phones' => Phone::factory()->count(3)->make()->toArray(),
    ];
});


describe('Create Employee', function () {

    it('Criando um novo funcionário com sucesso', function () {
        Sanctum::actingAs(
            User::query()->first(),
            ['*']
        );

        $response = $this->postJson('api/employee/store', $this->data);
        $response->assertStatus(201)->assertJson([
            'message' => __('Employee created successfully.'),
        ]);
        $this->assertDatabaseHas('users', [
            'email' => $this->data['email'],
        ]);
        $this->assertDatabaseHas('employees', [
            'name' => $this->data['name'],
        ]);
        $this->assertDatabaseHas('phones', [
            'number' => $this->data['phones'][0]['number'],
            'type' => $this->data['phones'][0]['type'],
        ]);
    });

    it('Não enviando dados nenhum para criar um novo funcionário', function () {

        Sanctum::actingAs(
            User::query()->first(),
            ['*']
        );

        $response = $this->postJson('api/employee/store', []);
        $response->assertStatus(422)->assertJsonStructure([
            'message',
            'errors'
        ]);
    });

    it('Usuário não autenticado', function () {
        $response = $this->postJson('api/employee/store', []);
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

        $response = $this->postJson('api/employee/store', $this->data);
        $response->assertStatus(403)->assertJson([
            'message' => __('This action is unauthorized.'),
        ]);
    });

    it('Criando um novo funcionário e enviando notificação de criar senha com sucesso', function () {
        // Fake de notificação para capturar o envio de e-mail
        Notification::fake();

        Sanctum::actingAs(
            User::query()->first(),
            ['*']
        );

        $response = $this->postJson('api/employee/store', $this->data);

        // Mantendo todas as asserções existentes
        $response->assertStatus(201)->assertJson([
            'message' => __('Employee created successfully.'),
        ]);

        $this->assertDatabaseHas('users', [
            'email' => $this->data['email'],
        ]);

        $this->assertDatabaseHas('employees', [
            'name' => $this->data['name'],
        ]);

        $this->assertDatabaseHas('phones', [
            'number' => $this->data['phones'][0]['number'],
            'type' => $this->data['phones'][0]['type'],
        ]);

        // Nova asserção: verifica se a notificação de criação de senha foi enviada
        $user = User::where('email', $this->data['email'])->first();
        Notification::assertSentTo($user, CreatePasswordNotification::class);
    });
});
