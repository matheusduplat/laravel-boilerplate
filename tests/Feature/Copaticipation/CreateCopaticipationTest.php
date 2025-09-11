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
    $this->data = Copaticipation::factory()->make([
        'base64' => fake()->uuid(),
        'customer_id' => Customer::query()->first()->id
    ])->toArray();
});


describe('Create Copaticipation', function () {
    it('Criando uma nova copaticipação com sucesso', function () {
        Sanctum::actingAs(
            User::query()->first(),
            ['*']
        );
        $response = $this->postJson('api/copaticipation/store', $this->data);
        $response->assertStatus(201)->assertJson([
            'message' => __('Copaticipation created successfully.'),
        ]);

        $this->assertDatabaseHas('copaticipations', [
            'base_month' => $this->data['base_month'],
            'base_year' => $this->data['base_year'],
        ]);
    });


    it('Não enviando dados nenhum para criar uma nova copaticipação', function () {

        Sanctum::actingAs(
            User::query()->first(),
            ['*']
        );

        $response = $this->postJson('api/copaticipation/store', []);
        $response->assertStatus(422)->assertJsonStructure([
            'message',
            'errors'
        ]);
    });

    it('Usuário não autenticado', function () {
        $response = $this->postJson('api/copaticipation/store', []);
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

        $response = $this->postJson('api/copaticipation/store', $this->data);
        $response->assertStatus(403)->assertJson([
            'message' => __('This action is unauthorized.'),
        ]);
    });
});
