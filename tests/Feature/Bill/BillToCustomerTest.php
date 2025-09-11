<?php

use App\Domains\Bill\Model\Bill;
use App\Domains\Customer\Model\Customer;
use App\Domains\User\Model\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Pest\ArchPresets\Custom;

uses(RefreshDatabase::class);
beforeEach(function () {
    $this->seed();

    Bill::factory()
        ->count(5)
        ->create([
            'customer_id' => Customer::query()->first()->id,
            'base64' => fake()->uuid(),
        ]);
    // $this->withoutExceptionHandling();
});


describe('Get with Pagination Bill to Customer', function () {
    it('Retorna a lista de boletos do cliente autenticado', function () {
        $customer = Customer::query()->first();
        Sanctum::actingAs(
            $customer,
            ['*']
        );



        $response = $this->getJson("api/bill/to-customer");
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

        $response = $this->getJson('api/bill/to-customer');
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

        $response = $this->getJson('api/bill/to-customer');
        $response->assertStatus(403)->assertJson([
            'message' => __('This action is unauthorized.'),
        ]);
    });
});
