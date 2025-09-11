<?php

use App\Domains\Customer\Model\Customer;
use App\Domains\IncomeReport\Model\IncomeReport;
use App\Domains\User\Model\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;

uses(RefreshDatabase::class);
beforeEach(function () {
    $this->seed();
    $this->data = IncomeReport::factory()->make([
        'attachment' => fake()->uuid(),
        'customer_id' => Customer::query()->first()->id
    ])->toArray();
});


describe('Create Income Report', function () {
    it('Criando um novo irrf com sucesso', function () {
        Sanctum::actingAs(
            User::query()->first(),
            ['*']
        );
        $response = $this->postJson('api/income-report/store', $this->data);
        $response->assertStatus(201)->assertJson([
            'message' => __('Income Report created successfully.'),
        ]);

        $this->assertDatabaseHas('income_reports', [
            'year' => $this->data['year'],
            'customer_id' => $this->data['customer_id']
        ]);
    });


    it('Não enviando dados nenhum para criar um novo irrf', function () {

        Sanctum::actingAs(
            User::query()->first(),
            ['*']
        );

        $response = $this->postJson('api/income-report/store', []);
        $response->assertStatus(422)->assertJsonStructure([
            'message',
            'errors'
        ]);
    });

    it('Usuário não autenticado', function () {
        $response = $this->postJson('api/income-report/store', []);
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

        $response = $this->postJson('api/income-report/store', $this->data);
        $response->assertStatus(403)->assertJson([
            'message' => __('This action is unauthorized.'),
        ]);
    });
});
