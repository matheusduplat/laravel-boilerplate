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


    $this->data = IncomeReport::factory()->make([
        'attachment' => fake()->uuid(),
        'customer_id' => Customer::query()->first()->id
    ])->toArray();
});


describe('Update Income Report', function () {
    it('Atualizar um  irrf com sucesso', function () {
        Sanctum::actingAs(
            User::query()->first(),
            ['*']
        );

        $incomeReport = IncomeReport::query()->first();


        $response = $this->postJson("api/income-report/update/{$incomeReport->id}", $this->data);
        $response->assertStatus(201)->assertJson([
            'message' => __('Income Report updated successfully.'),
        ]);

        $this->assertDatabaseHas('income_reports', [
            'id' => $incomeReport->id,
            'year' => $this->data['year'],
            'customer_id' => $this->data['customer_id']
        ]);
    });


    it('Não enviando dados nenhum para atualizar um  irrf', function () {

        Sanctum::actingAs(
            User::query()->first(),
            ['*']
        );
        $incomeReport = IncomeReport::query()->first();
        $response = $this->postJson("api/income-report/update/{$incomeReport->id}", []);
        $response->assertStatus(422)->assertJsonStructure([
            'message',
            'errors'
        ]);
    });

    it('Usuário não autenticado', function () {
        $incomeReport = IncomeReport::query()->first();
        $response = $this->postJson("api/income-report/update/{$incomeReport->id}", []);
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
        $response = $this->postJson("api/income-report/update/{$incomeReport->id}", $this->data);
        $response->assertStatus(403)->assertJson([
            'message' => __('This action is unauthorized.'),
        ]);
    });
});
