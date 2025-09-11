<?php

use App\Domains\AccreditedNetworks\Model\AccreditedNetworks;
use App\Domains\Address\Model\Address;
use App\Domains\Customer\Model\Customer;
use App\Domains\Employee\Model\Employee;
use App\Domains\Phone\Model\Phone;
use App\Domains\User\Model\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;

uses(RefreshDatabase::class);
beforeEach(function () {
    $this->seed();
    AccreditedNetworks::factory()
        ->count(5)
        ->has(Phone::factory()->count(2), 'phones')
        ->has(Address::factory(), 'address')
        ->create();
    // $this->withoutExceptionHandling();
});


describe('Show accredited networks', function () {

    it('Retorna uma rede credenciada', function () {
        Sanctum::actingAs(
            User::query()->first(),
            ['*']
        );

        $accreditedNetworks = AccreditedNetworks::query()->with(['address', 'phones'])->orderBy('id', 'desc')->first();

        $response = $this->getJson('api/accredited-networks/show/' . $accreditedNetworks->id);
        $response->assertStatus(200);
        $response->assertJsonFragment([
            'id' => $accreditedNetworks->id,
            'name' => $accreditedNetworks->name,
            "type_service" => $accreditedNetworks->type_service,
        ]);
    });

    it('Usuário não autenticado', function () {

        $accreditedNetworks = AccreditedNetworks::query()->orderBy('id', 'desc')->first();


        $response = $this->getJson('api/accredited-networks/show/' . $accreditedNetworks->id);
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

        $accreditedNetworks = AccreditedNetworks::query()->orderBy('id', 'desc')->first();


        $response = $this->getJson('api/accredited-networks/show/' . $accreditedNetworks->id);
        $response->assertStatus(403)->assertJson([
            'message' => __('This action is unauthorized.'),
        ]);
    });

    it('Id do funcionário inválido', function () {

        Sanctum::actingAs(
            User::query()->first(),
            ['*']
        );


        $response = $this->getJson('api/accredited-networks/show/66');
        $response->assertStatus(404)->assertJson([
            'message' => 'O registro de AccreditedNetworks com ID(s) 66 não foi encontrado.',
        ]);
    });
});
