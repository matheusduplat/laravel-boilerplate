<?php

use App\Domains\Address\Model\Address;
use App\Domains\Customer\Model\Customer;
use App\Domains\Phone\Model\Phone;
use App\Domains\Role\Model\Role;
use App\Domains\User\Model\User;
use App\Notifications\CreatePasswordNotification;
use App\Notifications\VerifyMailNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Laravel\Sanctum\Sanctum;

uses(RefreshDatabase::class);
beforeEach(function () {
    $this->seed();

    $customer = Customer::factory()->make()->toArray();
    $address = Address::factory()->make()->toArray();

    $this->data = [
        ...$customer,
        'birth_date' => fake()->date(),
        'phones' => Phone::factory()->count(3)->make()->toArray(),
        'address' => $address,
    ];
});


describe('Create Customer', function () {

    it('Criando um novo cliente com sucesso', function () {
        Sanctum::actingAs(
            User::query()->first(),
            ['*']
        );
        $response = $this->postJson('api/customer/store', $this->data);
        $response->assertStatus(201)->assertJson([
            'message' => __('Customer created successfully.'),
        ]);

        $this->assertDatabaseHas('customers', [
            'name' => $this->data['name'],
            'cpf' => $this->data['cpf'],
            'email' => $this->data['email'],
        ]);
        $this->assertDatabaseHas('phones', [
            'number' => $this->data['phones'][0]['number'],
            'type' => $this->data['phones'][0]['type'],
        ]);
        $this->assertDatabaseHas('addresses', [
            'zip_code' => $this->data['address']['zip_code'],
            'street' => $this->data['address']['street'],
            'number' => $this->data['address']['number'],
        ]);
    });

    it('Não enviando dados nenhum para criar um novo cliente', function () {

        Sanctum::actingAs(
            User::query()->first(),
            ['*']
        );

        $response = $this->postJson('api/customer/store', []);
        $response->assertStatus(422)->assertJsonStructure([
            'message',
            'errors'
        ]);
    });

    it('Usuário não autenticado', function () {
        $response = $this->postJson('api/customer/store', []);
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

        $response = $this->postJson('api/customer/store', $this->data);
        $response->assertStatus(403)->assertJson([
            'message' => __('This action is unauthorized.'),
        ]);
    });

    it('Criando um novo cliente e enviando notificação de criar senha com sucesso', function () {
        // Fake de notificação para capturar o envio de e-mail
        Notification::fake();

        Sanctum::actingAs(
            User::query()->first(),
            ['*']
        );

        $response = $this->postJson('api/customer/store', $this->data);

        // Mantendo todas as asserções existentes
        $response->assertStatus(201)->assertJson([
            'message' => __('Customer created successfully.'),
        ]);

        $this->assertDatabaseHas('customers', [
            'name' => $this->data['name'],
            'cpf' => $this->data['cpf'],
            'email' => $this->data['email'],
        ]);
        $this->assertDatabaseHas('phones', [
            'number' => $this->data['phones'][0]['number'],
            'type' => $this->data['phones'][0]['type'],
        ]);
        $this->assertDatabaseHas('addresses', [
            'zip_code' => $this->data['address']['zip_code'],
            'street' => $this->data['address']['street'],
            'number' => $this->data['address']['number'],
        ]);

        // Nova asserção: verifica se a notificação de criação de senha foi enviada
        $user = Customer::where('email', $this->data['email'])->first();
        Notification::assertSentTo($user, VerifyMailNotification::class);
    });
});
