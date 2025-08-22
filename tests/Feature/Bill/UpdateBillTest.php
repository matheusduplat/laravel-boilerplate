<?php

use App\Domains\Bill\Model\Bill;
use App\Domains\Customer\Model\Customer;
use App\Domains\User\Model\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;

uses(RefreshDatabase::class);
beforeEach(function () {
    $this->seed();

    Bill::factory()->create([
        'customer_id' => Customer::query()->first()->id,
        'base64' => fake()->uuid(),
    ])->toArray();

    $this->data = Bill::factory()->make([
        'base64' => fake()->uuid(),
    ])->toArray();
});


describe('Update Bill', function () {
    it('Atualizando um novo boleto com sucesso', function () {
        Sanctum::actingAs(
            User::query()->first(),
            ['*']
        );

        $bill = Bill::query()->first();

        $response = $this->postJson("api/bill/update/{$bill->id}", $this->data);
        $response->assertStatus(201)->assertJson([
            'message' => __('Bill updated successfully.'),
        ]);

        $this->assertDatabaseHas('bills', [
            'id' => $bill->id,
            'title_id' => $this->data['title_id'],
            'base_month' => $this->data['base_month'],
            'base_year' => $this->data['base_year'],
        ]);
    });


    it('Não enviando dados nenhum para atualizar um  boleto', function () {

        Sanctum::actingAs(
            User::query()->first(),
            ['*']
        );

        $bill = Bill::query()->first();

        $response = $this->postJson("api/bill/update/{$bill->id}", []);
        $response->assertStatus(422)->assertJsonStructure([
            'message',
            'errors'
        ]);
    });

    it('Usuário não autenticado', function () {
        $bill = Bill::query()->first();

        $response = $this->postJson("api/bill/update/{$bill->id}", []);
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

        $bill = Bill::query()->first();

        $response = $this->postJson("api/bill/update/{$bill->id}", []);
        $response->assertStatus(403)->assertJson([
            'message' => __('This action is unauthorized.'),
        ]);
    });
});
