<?php

use App\Domains\Bill\Model\Bill;
use App\Domains\Customer\Model\Customer;
use App\Domains\DigitalWallet\Model\DigitalWallet;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;

uses(RefreshDatabase::class);
beforeEach(function () {
    $this->seed();

    DigitalWallet::factory()
        ->count(5)
        ->create([
            'customer_id' => Customer::query()->first()->id,
        ]);
    // $this->withoutExceptionHandling();
});


describe('Digital Wallet to Customer', function () {
    it('Retorna a lista de boletos do cliente autenticado', function () {
        $customer = Customer::query()->first();
        Sanctum::actingAs(
            $customer,
            ['*']
        );



        $response = $this->getJson("api/digital-wallet/to-customer");
        $response->assertJsonPath('customer.id', $customer->id);
    });

    it('Usuário não autenticado', function () {

        $response = $this->getJson('api/digital-wallet/to-customer');
        $response->assertStatus(403)->assertJson([
            'message' => 'Unauthenticated.',
        ]);
    });

    it('Usuário sem permissão', function () {
        $user =  Customer::factory()->create([
            'password' => 'password',
        ]);

        Sanctum::actingAs(
            $user,
            ['*']
        );

        $bill = Bill::query()->first();

        $response = $this->getJson('api/digital-wallet/to-customer');
        $response->assertStatus(403)->assertJson([
            'message' => __('This action is unauthorized.'),
        ]);
    });
});
