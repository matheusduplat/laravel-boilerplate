<?php

use App\Domains\AccreditedNetworks\Model\AccreditedNetworks;
use App\Domains\Address\Model\Address;
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
});


describe('Delete accredited networks', function () {

    it('Deletando uma rede credenciada com sucesso', function () {
        Sanctum::actingAs(
            User::query()->first(),
            ['*']
        );

        $accreditedNetwork = AccreditedNetworks::query()->inRandomOrder()->first();

        $response = $this->deleteJson("api/accredited-networks/destroy/{$accreditedNetwork->id}");
        $response->assertStatus(201)->assertJson([
            'message' => __('Accredited Network deleted successfully.'),
        ]);

        $this->assertSoftDeleted('accredited_networks', [
            "id" => $accreditedNetwork->id,
        ]);
        $this->assertSoftDeleted('phones', [
            "phoneable_id" => $accreditedNetwork->id,
            "phoneable_type" => "AccreditedNetworks"
        ]);
        $this->assertSoftDeleted('addresses', [
            "addressable_id" => $accreditedNetwork->id,
            "addressable_type" => "AccreditedNetworks"
        ]);
    });


    it('Usuário não autenticado', function () {
        $accreditedNetwork = AccreditedNetworks::query()->inRandomOrder()->first();
        $response = $this->deleteJson("api/accredited-networks/destroy/{$accreditedNetwork->id}");
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

        $accreditedNetwork = AccreditedNetworks::query()->inRandomOrder()->first();
        $response = $this->deleteJson("api/accredited-networks/destroy/{$accreditedNetwork->id}");
        $response->assertStatus(403)->assertJson([
            'message' => __('This action is unauthorized.'),
        ]);
    });
});
