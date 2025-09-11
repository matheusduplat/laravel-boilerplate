<?php

use App\Domains\Bill\Model\Bill;
use App\Domains\Customer\Model\Customer;
use App\Domains\DigitalWallet\Model\DigitalWallet;
use App\Domains\User\Model\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;

uses(RefreshDatabase::class);
beforeEach(function () {
    $this->seed();
    $this->data = DigitalWallet::factory()->make([
        'customer_id' => Customer::query()->first()->id
    ])->toArray();
});


describe('Create Digital Wallet', function () {
    it('Criando uma nova carteirinha com sucesso', function () {
        Sanctum::actingAs(
            User::query()->first(),
            ['*']
        );
        $this->data['date_issue'] = fake()->date('Y-m-d');
        $this->data['validity'] = fake()->optional()->dateTimeBetween('+1 month', '+2 months')?->format('Y-m-d');
        $response = $this->postJson('api/digital-wallet/store', $this->data);
        $response->assertStatus(201)->assertJson([
            'message' => __('Digital Wallet created successfully.'),
        ]);

        $this->assertDatabaseHas('digital_wallets', [
            'customer_id' => $this->data['customer_id'],
            'number' => $this->data['number'],

        ]);
    });


    it('Não enviando dados nenhum para criar uma nova carteirinha', function () {

        Sanctum::actingAs(
            User::query()->first(),
            ['*']
        );

        $response = $this->postJson('api/digital-wallet/store', []);
        $response->assertStatus(422)->assertJsonStructure([
            'message',
            'errors'
        ]);
    });

    it('Usuário não autenticado', function () {
        $response = $this->postJson('api/digital-wallet/store', []);
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

        $response = $this->postJson('api/digital-wallet/store', $this->data);
        $response->assertStatus(403)->assertJson([
            'message' => __('This action is unauthorized.'),
        ]);
    });
});
