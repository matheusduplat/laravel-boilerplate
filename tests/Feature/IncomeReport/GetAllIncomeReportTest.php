<?php

use App\Domains\Bill\Model\Bill;
use App\Domains\Customer\Model\Customer;
use App\Domains\IncomeReport\Model\IncomeReport;
use App\Domains\User\Model\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;

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


describe('Get All Income Report', function () {
    it('Retorna a lista de irrf', function () {
        Sanctum::actingAs(
            User::query()->first(),
            ['*']
        );


        $response = $this->getJson('api/income-report?with_trashed=1');
        $response->assertStatus(200)->assertJsonCount(5);
    });

    it('Usuário não autenticado', function () {

        $response = $this->getJson('api/income-report?with_trashed=1');
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


        $response = $this->getJson('api/income-report?with_trashed=1');
        $response->assertStatus(403)->assertJson([
            'message' => __('This action is unauthorized.'),
        ]);
    });
});
