<?php

use App\Domains\Address\Model\Address;
use App\Domains\Customer\Model\Customer;
use App\Domains\Phone\Enums\PhoneType;
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

    Customer::factory()
        ->count(5)
        ->has(Phone::factory()->count(2), 'phones')
        ->has(Address::factory(), 'address')
        ->customerRole()
        ->create([
            'password' => '12345678',
        ]);
    // $this->withoutExceptionHandling();
});


describe('Update Address Customer', function () {

    it('Atualizar endereço do cliente com sucesso', function () {
        Sanctum::actingAs(
            User::query()->first(),
            ['*']
        );
        $customer = Customer::with(['address'])->orderBy('id', 'desc')->first();

        $this->data = [
            ...$customer->address->toArray(),
            'street' => fake()->streetName(),
        ];


        $response = $this->postJson("api/customer/update/address/{$customer->id}", $this->data);
        $response->assertStatus(201)->assertJson([
            'message' => __('Address updated successfully.'),
        ]);

        $this->assertDatabaseHas('addresses', [
            'id' => $customer->address->id,
            'street' => $this->data['street'],
        ]);
    });

    it('Não enviando dados nenhum para atualizar endereço do cliente', function () {

        Sanctum::actingAs(
            User::query()->first(),
            ['*']
        );
        $customer = Customer::with(['phones', 'address'])->orderBy('id', 'desc')->first();
        $response = $this->postJson("api/customer/update/address/{$customer->id}", []);
        $response->assertStatus(422)->assertJsonStructure([
            'message',
            'errors'
        ]);
    });

    it('Usuário não autenticado', function () {
        $customer = Customer::with(['phones', 'address'])->orderBy('id', 'desc')->first();
        $response = $this->postJson("api/customer/update/address/{$customer->id}", []);
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

        $customer = Customer::with(['phones', 'address'])->orderBy('id', 'desc')->first();
        $response = $this->postJson("api/customer/update/address/{$customer->id}", []);
        $response->assertStatus(403)->assertJson([
            'message' => __('This action is unauthorized.'),
        ]);
    });

    it('Atualizar um cliente com sucesso sendo customer', function () {
        $customer = Customer::with(['address'])->orderBy('id', 'desc')->get()->first();
        Sanctum::actingAs(
            $customer,
            ['*'],
            'customer'
        );

        $this->data = [
            ...$customer->address->toArray(),
            'street' => fake()->streetName(),
        ];

        $response = $this->postJson("api/customer/update/address/{$customer->id}", $this->data);
        $response->assertStatus(201)->assertJson([
            'message' => __('Address updated successfully.'),
        ]);
        $this->assertDatabaseHas('addresses', [
            'id' => $customer->address->id,
            'street' => $this->data['street'],
        ]);
    });

    it('Erro ao atualizar um endereço que não pertence ao cliente autenticado', function () {
        Sanctum::actingAs(
            Customer::query()->first(),
            ['*'],
            'customer'
        );

        $customer = Customer::with(['address'])->orderBy('id', 'desc')->get()->first();

        $this->data = [
            ...$customer->address->toArray(),
            'street' => fake()->streetName(),
        ];


        $response = $this->postJson("api/customer/update/address/{$customer->id}", $this->data);
        $response->assertStatus(403)->assertJson([
            'message' => __("You cannot edit another customer's address."),
        ]);
    });
});
