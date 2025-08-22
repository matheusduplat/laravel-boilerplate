<?php

use App\Domains\Bill\Model\Bill;
use App\Domains\Customer\Model\Customer;
use App\Domains\User\Model\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;

uses(RefreshDatabase::class);
beforeEach(function () {
    $this->seed();
    Bill::factory()
        ->count(5)
        ->create([
            'customer_id' => Customer::query()->first()->id,
            'base64' => fake()->uuid(),
        ]);
});


describe('Show Bill', function () {

    it('Retorna somente um boleto', function () {
        Sanctum::actingAs(
            User::query()->first(),
            ['*']
        );
        $bill = Bill::inRandomOrder()->first();
        $response = $this->getJson('api/bill/show/' . $bill->id);
        $response->assertStatus(200);
        $response->assertJsonFragment([
            'id' => $bill->id,
            'title_id' => $bill->title_id,
        ]);
    });
    it('Usuário não autenticado', function () {
        $bill = Bill::inRandomOrder()->first();
        $response = $this->getJson('api/bill/show/' . $bill->id);
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

        $bill = Bill::inRandomOrder()->first();
        $response = $this->getJson('api/bill/show/' . $bill->id);
        $response->assertStatus(403)->assertJson([
            'message' => __('This action is unauthorized.'),
        ]);
    });
});
