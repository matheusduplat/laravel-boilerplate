<?php

use App\Domains\Bill\Model\Bill;
use App\Domains\Customer\Model\Customer;
use App\Domains\IncomeReport\Model\IncomeReport;
use App\Domains\User\Model\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Pest\ArchPresets\Custom;

uses(RefreshDatabase::class);
beforeEach(function () {
    $this->seed();
    IncomeReport::factory()
        ->count(5)
        ->create([
            'customer_id' => Customer::query()->first()->id,
            'attachment' => fake()->uuid(),
        ])->toArray();
    // $this->withoutExceptionHandling();
});


describe('Get with Pagination Income Report to Customer', function () {
    it('Retorna a lista de irrf do cliente autenticado', function () {
        $customer = Customer::query()->first();
        Sanctum::actingAs(
            $customer,
            ['*']
        );

        $response = $this->getJson("api/income-report/to-customer");
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

        $response = $this->getJson('api/income-report/to-customer');
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

        $response = $this->getJson('api/income-report/to-customer');
        $response->assertStatus(403)->assertJson([
            'message' => __('This action is unauthorized.'),
        ]);
    });
});
