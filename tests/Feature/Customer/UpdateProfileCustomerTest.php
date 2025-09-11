<?php

use App\Domains\Address\Model\Address;
use App\Domains\Customer\Model\Customer;
use App\Domains\Phone\Enums\PhoneType;
use App\Domains\Phone\Model\Phone;
use App\Domains\User\Model\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;

uses(RefreshDatabase::class);
beforeEach(function () {
    $this->seed();
    Customer::factory()
        ->count(5)
        ->has(Phone::factory()->count(2), 'phones')
        ->has(Address::factory(), 'address')
        ->has(User::factory()->customer(), 'user')
        ->create();
    // $this->withoutExceptionHandling();
});


describe('Update Phones Customer', function () {

    it('Atualizar perfil do cliente com sucesso', function () {
        Sanctum::actingAs(
            User::query()->first(),
            ['*']
        );
        $customer = Customer::orderBy('id', 'desc')->first();

        $this->data = [
            ...$customer->toArray(),
            'name' => fake()->name(),
            'birth_date' => fake()->date(),
            'email' => $customer->user->email
        ];


        $response = $this->postJson("api/customer/update/profile/{$customer->id}", $this->data);
        $response->assertStatus(201)->assertJson([
            'message' => __('Profile updated successfully.'),
        ]);
        $this->assertDatabaseHas('customers', [
            'id' => $customer->id,
            'name' => $this->data['name'],
        ]);
    });

    it('Não enviando dados nenhum para atualizar perfil do cliente', function () {

        Sanctum::actingAs(
            User::query()->first(),
            ['*']
        );
        $customer = Customer::orderBy('id', 'desc')->first();
        $response = $this->postJson("api/customer/update/profile/{$customer->id}", []);
        $response->assertStatus(422)->assertJsonStructure([
            'message',
            'errors'
        ]);
    });

    it('Usuário não autenticado', function () {
        $customer = Customer::orderBy('id', 'desc')->first();
        $response = $this->postJson("api/customer/update/profile/{$customer->id}", []);
        $response->assertStatus(403)->assertJson([
            'message' => 'Unauthenticated.',
        ]);
    });

    it('Usuário sem permissão', function () {
        $user =  User::factory()->for(Customer::factory(), 'userable')->create();
        Sanctum::actingAs(
            $user,
            ['*']
        );
        $customer = Customer::with(['phones', 'address'])->orderBy('id', 'desc')->first();
        $response = $this->postJson("api/customer/update/profile/{$customer->id}", []);
        $response->assertStatus(403)->assertJson([
            'message' => __('This action is unauthorized.'),
        ]);
    });

    it('Atualizar perfil do cliente com sucesso sendo customer', function () {
        $customer = Customer::with(['address'])->orderBy('id', 'desc')->get()->first();
        Sanctum::actingAs(
            $customer->user,
            ['*'],
        );

        $this->data = [
            ...$customer->toArray(),
            'name' => fake()->name(),
            'birth_date' => fake()->date(),
            'email' => $customer->user->email
        ];

        $response = $this->postJson("api/customer/update/profile/{$customer->id}", $this->data);
        $response->assertStatus(201)->assertJson([
            'message' => __('Profile updated successfully.'),
        ]);
        $this->assertDatabaseHas('customers', [
            'id' => $customer->id,
            'name' => $this->data['name'],

        ]);
    });

    it('Erro ao atualizar  perfil que não pertence ao cliente autenticado', function () {
        $customer = Customer::query()->first();
        Sanctum::actingAs(
            $customer->user,
            ['*'],
        );

        $customer = Customer::orderBy('id', 'desc')->get()->first();



        $response = $this->postJson("api/customer/update/profile/{$customer->id}", []);
        $response->assertStatus(403)->assertJson([
            'message' => __("You cannot edit another customer's profile."),
        ]);
    });
});
