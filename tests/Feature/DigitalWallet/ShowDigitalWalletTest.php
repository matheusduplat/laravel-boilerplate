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

    DigitalWallet::factory()
        ->count(2)
        ->create(
            ['customer_id' => Customer::query()->first()->id]
        );
});


describe('Show Digital Wallet', function () {
    it('Retornando uma  carteirinha com sucesso', function () {
        Sanctum::actingAs(
            User::query()->first(),
            ['*']
        );


        $digitalWallet = DigitalWallet::inRandomOrder()->first();

        $response = $this->getJson("api/digital-wallet/show/{$digitalWallet->id}");
        $response->assertStatus(200);
        $response->assertJsonFragment([
            'id' => $digitalWallet->id,
            'number' => $digitalWallet->number,
        ]);
    });

    it('Usuário não autenticado', function () {

        $digitalWallet = DigitalWallet::query()->first();

        $response = $this->getJson("api/digital-wallet/show/{$digitalWallet->id}");
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

        $response = $this->getJson("api/digital-wallet/show/{$digitalWallet->id}");
        $response->assertStatus(403)->assertJson([
            'message' => __('This action is unauthorized.'),
        ]);
    });
});
