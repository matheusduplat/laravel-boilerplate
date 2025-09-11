<?php

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
});


describe('Show Income Report', function () {

    it('Retorna somente um irrf', function () {
        Sanctum::actingAs(
            User::query()->first(),
            ['*']
        );
        $income_report = IncomeReport::inRandomOrder()->first();
        $response = $this->getJson('api/income-report/show/' . $income_report->id);
        $response->assertStatus(200);
        $response->assertJsonFragment([
            'id' => $income_report->id,
            'year' => $income_report->year,
            'attachment' => $income_report->attachment,
        ]);
    });
    it('Usuário não autenticado', function () {
        $income_report = IncomeReport::inRandomOrder()->first();
        $response = $this->getJson('api/income-report/show/' . $income_report->id);
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

        $income_report = IncomeReport::inRandomOrder()->first();
        $response = $this->getJson('api/income-report/show/' . $income_report->id);
        $response->assertStatus(403)->assertJson([
            'message' => __('This action is unauthorized.'),
        ]);
    });
});
