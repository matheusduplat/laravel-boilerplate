<?php

use App\Domains\Bill\Model\Bill;
use App\Domains\Customer\Model\Customer;
use App\Domains\DigitalWallet\Model\DigitalWallet;
use App\Domains\User\Model\User;
use DragonCode\Support\Facades\Helpers\Digit;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;

uses(RefreshDatabase::class);
beforeEach(function () {
    $this->seed();
    $this->data = DigitalWallet::factory()->make([
        'customer_id' => Customer::query()->first()->id
    ])->toArray();
    DigitalWallet::factory()
        ->count(2)
        ->create(
            ['customer_id' => Customer::query()->first()->id]
        );
});


describe('Update Digital Wallet', function () {
    it('Atualizando uma  carteirinha com sucesso', function () {
        Sanctum::actingAs(
            User::query()->first(),
            ['*']
        );
        $this->data['number'] = fake()->creditCardNumber();
        $this->data['date_issue'] = fake()->date('Y-m-d');

        $digitalWallet = DigitalWallet::query()->first();

        $response = $this->postJson("api/digital-wallet/update/{$digitalWallet->id}", $this->data);
        $response->assertStatus(201)->assertJson([
            'message' => __('Digital Wallet updated successfully.'),
        ]);

        $this->assertDatabaseHas('digital_wallets', [
            'id' => $digitalWallet->id,
            'customer_id' => $this->data['customer_id'],
            'number' => $this->data['number'],
        ]);
    });


    it('Não enviando dados nenhum para atualizar uma carteirinha', function () {

        Sanctum::actingAs(
            User::query()->first(),
            ['*']
        );


        $digitalWallet = DigitalWallet::query()->first();

        $response = $this->postJson("api/digital-wallet/update/{$digitalWallet->id}", []);
        $response->assertStatus(422)->assertJsonStructure([
            'message',
            'errors'
        ]);
    });

    it('Usuário não autenticado', function () {

        $digitalWallet = DigitalWallet::query()->first();

        $response = $this->postJson("api/digital-wallet/update/{$digitalWallet->id}", []);
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


        $digitalWallet = DigitalWallet::query()->first();

        $response = $this->postJson("api/digital-wallet/update/{$digitalWallet->id}", []);
        $response->assertStatus(403)->assertJson([
            'message' => __('This action is unauthorized.'),
        ]);
    });
});
