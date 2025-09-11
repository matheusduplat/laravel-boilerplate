<?php

use App\Domains\Bill\Model\Bill;
use App\Domains\Copaticipation\Model\Copaticipation;
use App\Domains\Customer\Model\Customer;
use App\Domains\User\Model\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;

uses(RefreshDatabase::class);
beforeEach(function () {
    $this->seed();
    Copaticipation::factory()
        ->count(5)
        ->create([
            'customer_id' => Customer::query()->first()->id,
            'base64' => fake()->uuid(),
        ]);
});


describe('Show Copaticipation', function () {

    it('Retorna somente uma copaticipacao', function () {
        Sanctum::actingAs(
            User::query()->first(),
            ['*']
        );
        $copaticipation = Copaticipation::inRandomOrder()->first();
        $response = $this->getJson('api/copaticipation/show/' . $copaticipation->id);
        $response->assertStatus(200);
        $response->assertJsonFragment([
            'id' => $copaticipation->id,
            'base_year' => $copaticipation->base_year,
            'base_month' => $copaticipation->base_month,
        ]);
    });
    it('Usuário não autenticado', function () {
        $copaticipation = Copaticipation::inRandomOrder()->first();
        $response = $this->getJson('api/copaticipation/show/' . $copaticipation->id);
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

        $copaticipation = Copaticipation::inRandomOrder()->first();
        $response = $this->getJson('api/copaticipation/show/' . $copaticipation->id);
        $response->assertStatus(403)->assertJson([
            'message' => __('This action is unauthorized.'),
        ]);
    });
});
