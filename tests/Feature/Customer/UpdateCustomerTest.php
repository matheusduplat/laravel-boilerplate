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
        ->has(Phone::factory()->count(2), 'phones')
        ->has(Address::factory(), 'address')
        ->create([
            'password' => '12345678',
        ]);
});


describe('Update Customer', function () {

    it('Atualizar um cliente com sucesso', function () {
        Sanctum::actingAs(
            User::query()->first(),
            ['*']
        );
        $customer = Customer::with(['phones', 'address'])->orderBy('id', 'desc')->first();
        $phones = $customer->phones->toArray();

        $this->data = [
            ...$customer->toArray(),
            'name' => fake()->name(),
            'birth_date' => fake()->date(),
            'phones' => [
                [
                    'id' => $phones[0]['id'],
                    'type' => $phones[0]['type'],
                    'whatsapp' => $phones[0]['whatsapp'],
                    'number' => fake()->phoneNumber(),
                ],
                [
                    'id' => $phones[1]['id'],
                    'type' => $phones[1]['type'],
                    'whatsapp' => $phones[1]['whatsapp'],
                    'number' => $phones[1]['number'],
                ],
                [
                    'number' => fake()->phoneNumber(),
                    'type' => PhoneType::FIXED,
                    'whatsapp' => false,

                ]
            ],
            'address' => [
                ...$customer->address->toArray(),
                'street' => fake()->streetName(),
            ]
        ];




        $response = $this->postJson("api/customer/update/{$customer->id}", $this->data);
        $response->assertStatus(201)->assertJson([
            'message' => __('Customer updated successfully.'),
        ]);

        $this->assertDatabaseHas('customers', [
            'id' => $customer->id,
            'name' => $this->data['name'],
            'birth_date' => $this->data['birth_date'] . ' 00:00:00',
        ]);
        $this->assertDatabaseHas('phones', [
            'id' => $this->data['phones'][0]['id'],
            'number' => $this->data['phones'][0]['number'],
        ]);
        $this->assertDatabaseHas('phones', [
            'number' => $this->data['phones'][2]['number'],
            'type' => $this->data['phones'][2]['type'],
        ]);
        $this->assertDatabaseHas('addresses', [
            'id' => $customer->address->id,
            'street' => $this->data['address']['street'],
            'number' => $this->data['address']['number'],
        ]);
    });

    it('Não enviando dados nenhum para atualizar um cliente', function () {

        Sanctum::actingAs(
            User::query()->first(),
            ['*']
        );
        $customer = Customer::with(['phones', 'address'])->orderBy('id', 'desc')->first();
        $response = $this->postJson("api/customer/update/{$customer->id}", []);
        $response->assertStatus(422)->assertJsonStructure([
            'message',
            'errors'
        ]);
    });

    it('Usuário não autenticado', function () {
        $customer = Customer::with(['phones', 'address'])->orderBy('id', 'desc')->first();
        $response = $this->postJson("api/customer/update/{$customer->id}", []);
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
        $response = $this->postJson("api/customer/update/{$customer->id}", []);
        $response->assertStatus(403)->assertJson([
            'message' => __('This action is unauthorized.'),
        ]);
    });

    it('Atualizar um cliente com sucesso sendo customer', function () {
        Sanctum::actingAs(
            Customer::query()->first(),
            ['*']
        );
        $customer = Customer::with(['phones', 'address'])->orderBy('id', 'desc')->first();
        $phones = $customer->phones->toArray();

        $this->data = [
            ...$customer->toArray(),
            'name' => fake()->name(),
            'birth_date' => fake()->date(),
            'phones' => [
                [
                    'id' => $phones[0]['id'],
                    'type' => $phones[0]['type'],
                    'whatsapp' => $phones[0]['whatsapp'],
                    'number' => fake()->phoneNumber(),
                ],
                [
                    'id' => $phones[1]['id'],
                    'type' => $phones[1]['type'],
                    'whatsapp' => $phones[1]['whatsapp'],
                    'number' => $phones[1]['number'],
                ],
                [
                    'number' => fake()->phoneNumber(),
                    'type' => PhoneType::FIXED,
                    'whatsapp' => false,

                ]
            ],
            'address' => [
                ...$customer->address->toArray(),
                'street' => fake()->streetName(),
            ]
        ];




        $response = $this->postJson("api/customer/update/{$customer->id}", $this->data);
        $response->assertStatus(201)->assertJson([
            'message' => __('Customer updated successfully.'),
        ]);

        $this->assertDatabaseHas('customers', [
            'id' => $customer->id,
            'name' => $this->data['name'],
            'birth_date' => $this->data['birth_date'] . ' 00:00:00',
        ]);
        $this->assertDatabaseHas('phones', [
            'id' => $this->data['phones'][0]['id'],
            'number' => $this->data['phones'][0]['number'],
        ]);
        $this->assertDatabaseHas('phones', [
            'number' => $this->data['phones'][2]['number'],
            'type' => $this->data['phones'][2]['type'],
        ]);
        $this->assertDatabaseHas('addresses', [
            'id' => $customer->address->id,
            'street' => $this->data['address']['street'],
            'number' => $this->data['address']['number'],
        ]);
    });
});
