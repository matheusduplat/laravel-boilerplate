<?php

use App\Domains\Bill\Model\Bill;
use App\Domains\Copaticipation\Model\Copaticipation;
use App\Domains\Customer\Model\Customer;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;

uses(RefreshDatabase::class);
beforeEach(function () {
    $this->seed();

    Copaticipation::factory()
        ->count(5)
        ->create([
            'customer_id' => Customer::query()->first()->id,
            'base64' => fake()->uuid(),
        ]);
    // $this->withoutExceptionHandling();
});


describe('Get with Pagination Copaticipation to Customer', function () {
    it('Retorna a lista de copaticipacoes do cliente autenticado', function () {
        $customer = Customer::query()->first();
        Sanctum::actingAs(
            $customer,
            ['*']
        );



        $response = $this->getJson("api/copaticipation/to-customer");
        $response->assertJsonCount(5, 'items');
        $response->assertJsonStructure([
            'items',
            'pagination_more',
            'perPage',
            'total',
            'lastPage',
            'currentPage',
        ]);
    });

    it('Usuário não autenticado', function () {

        $response = $this->getJson('api/copaticipation/to-customer');
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

        $response = $this->getJson('api/copaticipation/to-customer');
        $response->assertStatus(403)->assertJson([
            'message' => __('This action is unauthorized.'),
        ]);
    });
});
