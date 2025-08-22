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
    // $this->withoutExceptionHandling();
});


describe('Delete Bill', function () {
    it('Deletar um  boleto com sucesso', function () {
        Sanctum::actingAs(
            User::query()->first(),
            ['*']
        );

        $bill = Bill::query()->first();

        $response = $this->deleteJson("api/bill/destroy/{$bill->id}");
        $response->assertStatus(201)->assertJson([
            'message' => __('Bill deleted successfully.'),
        ]);

        $this->assertSoftDeleted('bills', [
            'id' => $bill->id,
        ]);
    });

    it('Usuário não autenticado', function () {
        $bill = Bill::query()->first();

        $response = $this->deleteJson("api/bill/destroy/{$bill->id}", []);
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

        $response = $this->deleteJson("api/bill/destroy/{$bill->id}", []);
        $response->assertStatus(403)->assertJson([
            'message' => __('This action is unauthorized.'),
        ]);
    });
});
