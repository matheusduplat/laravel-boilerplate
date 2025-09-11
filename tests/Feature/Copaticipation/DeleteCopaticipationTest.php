<?php

use App\Domains\copaticipation\Model\Bill;
use App\Domains\Copaticipation\Model\Copaticipation;
use App\Domains\Customer\Model\Customer;
use App\Domains\User\Model\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;

uses(RefreshDatabase::class);
beforeEach(function () {
    $this->seed();

    Copaticipation::factory()->create([
        'customer_id' => Customer::query()->first()->id,
        'base64' => fake()->uuid(),
    ])->toArray();
    // $this->withoutExceptionHandling();
});


describe('Delete Copaticipation', function () {
    it('Deletar uma copaticipação com sucesso', function () {
        Sanctum::actingAs(
            User::query()->first(),
            ['*']
        );

        $copaticipation = Copaticipation::query()->first();

        $response = $this->deleteJson("api/copaticipation/destroy/{$copaticipation->id}");
        $response->assertStatus(201)->assertJson([
            'message' => __('Copaticipation deleted successfully.'),
        ]);

        $this->assertSoftDeleted('copaticipations', [
            'id' => $copaticipation->id,
        ]);
    });

    it('Usuário não autenticado', function () {
        $copaticipation = Copaticipation::query()->first();

        $response = $this->deleteJson("api/copaticipation/destroy/{$copaticipation->id}", []);
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

        $copaticipation = Copaticipation::query()->first();

        $response = $this->deleteJson("api/copaticipation/destroy/{$copaticipation->id}", []);
        $response->assertStatus(403)->assertJson([
            'message' => __('This action is unauthorized.'),
        ]);
    });
});
