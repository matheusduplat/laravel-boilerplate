<?php

use App\Domains\Customer\Model\Customer;
use App\Domains\IncomeReport\Model\IncomeReport;
use App\Domains\User\Model\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;

uses(RefreshDatabase::class);
beforeEach(function () {
    $this->seed();


    IncomeReport::factory()->create([
        'customer_id' => Customer::query()->first()->id,
        'attachment' => fake()->uuid(),
    ])->toArray();
});


describe('Delete Income Report', function () {
    it('Deletar um  irrf com sucesso', function () {
        Sanctum::actingAs(
            User::query()->first(),
            ['*']
        );

        $incomeReport = IncomeReport::query()->first();

        $response = $this->deleteJson("api/income-report/destroy/{$incomeReport->id}");
        $response->assertStatus(201)->assertJson([
            'message' => __('Income Report deleted successfully.'),
        ]);

        $this->assertSoftDeleted('income_reports', [
            'id' => $incomeReport->id,
        ]);
    });

    it('Usuário não autenticado', function () {
        $incomeReport = IncomeReport::query()->first();
        $response = $this->deleteJson("api/income-report/destroy/{$incomeReport->id}");
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
        $incomeReport = IncomeReport::query()->first();
        $response = $this->deleteJson("api/income-report/destroy/{$incomeReport->id}");
        $response->assertStatus(403)->assertJson([
            'message' => __('This action is unauthorized.'),
        ]);
    });
});
