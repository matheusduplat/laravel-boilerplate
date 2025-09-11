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

    it('Atualizar telefone do cliente com sucesso', function () {
        Sanctum::actingAs(
            User::query()->first(),
            ['*']
        );
        $customer = Customer::with(['phones'])->orderBy('id', 'desc')->first();
        $phones = $customer->phones->toArray();

        $this->data = [
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
        ];


        $response = $this->postJson("api/customer/update/phone/{$customer->id}", $this->data);
        $response->assertStatus(201)->assertJson([
            'message' => __('Phones updated successfully.'),
        ]);

        $this->assertDatabaseHas('phones', [
            'id' => $this->data['phones'][0]['id'],
            'number' => $this->data['phones'][0]['number'],
        ]);
        $this->assertDatabaseHas('phones', [
            'number' => $this->data['phones'][2]['number'],
            'type' => $this->data['phones'][2]['type'],
        ]);
    });

    it('Não enviando dados nenhum para atualizar telefone do cliente', function () {

        Sanctum::actingAs(
            User::query()->first(),
            ['*']
        );
        $customer = Customer::with(['phones', 'address'])->orderBy('id', 'desc')->first();
        $response = $this->postJson("api/customer/update/phone/{$customer->id}", []);
        $response->assertStatus(422)->assertJsonStructure([
            'message',
            'errors'
        ]);
    });

    it('Usuário não autenticado', function () {
        $customer = Customer::with(['phones', 'address'])->orderBy('id', 'desc')->first();
        $response = $this->postJson("api/customer/update/phone/{$customer->id}", []);
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
        $response = $this->postJson("api/customer/update/phone/{$customer->id}", []);
        $response->assertStatus(403)->assertJson([
            'message' => __('This action is unauthorized.'),
        ]);
    });

    it('Atualizar telefone do cliente com sucesso sendo customer', function () {
        $customer = Customer::with(['phones'])->orderBy('id', 'desc')->get()->first();

        Sanctum::actingAs(
            $customer->user,
            ['*'],
        );


        $phones = $customer->phones->toArray();

        $this->data = [
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
        ];

        $response = $this->postJson("api/customer/update/phone/{$customer->id}", $this->data);
        $response->assertStatus(201)->assertJson([
            'message' => __('Phones updated successfully.'),
        ]);
        $this->assertDatabaseHas('phones', [
            'id' => $this->data['phones'][0]['id'],
            'number' => $this->data['phones'][0]['number'],
        ]);
        $this->assertDatabaseHas('phones', [
            'number' => $this->data['phones'][2]['number'],
            'type' => $this->data['phones'][2]['type'],
        ]);
    });

    it('Erro ao atualizar um endereço que não pertence ao cliente autenticado', function () {
        $customer = Customer::query()->first();
        Sanctum::actingAs(
            $customer->user,
            ['*'],
        );

        $customer = Customer::with(['address'])->orderBy('id', 'desc')->get()->first();



        $response = $this->postJson("api/customer/update/phone/{$customer->id}", []);
        $response->assertStatus(403)->assertJson([
            'message' => __("You cannot edit another customer's phones."),
        ]);
    });
});
