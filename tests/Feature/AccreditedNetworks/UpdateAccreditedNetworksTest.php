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

    $accreditedNetWorks = AccreditedNetworks::factory()->make()->toArray();
    $address = Address::factory()->make()->toArray();

    $this->data = [
        ...$accreditedNetWorks,
        'phones' => Phone::factory()->count(3)->make()->toArray(),
        'address' => $address,
    ];
});


describe('Update accredited networks', function () {

    it('Atualizando uma rede credenciada com sucesso', function () {
        Sanctum::actingAs(
            User::query()->first(),
            ['*']
        );

        $accreditedNetwork = AccreditedNetworks::query()->first();

        $this->data['address']['id'] = $accreditedNetwork->address->id;


        $response = $this->postJson("api/accredited-networks/update/{$accreditedNetwork->id}", $this->data);
        $response->assertStatus(201)->assertJson([
            'message' => __('Accredited Network updated successfully.'),
        ]);

        $this->assertDatabaseHas('accredited_networks', [
            "id" => $accreditedNetwork->id,
            "name" => $this->data['name'],
            "type_service" => $this->data['type_service'],
            "specialties_served" => json_encode($this->data['specialties_served']),
            "exams_attended" => json_encode($this->data['exams_attended']),
            "plans_attended" => json_encode($this->data['plans_attended']),
            "urgency_emergency" => $this->data['urgency_emergency'],
            "status" => $this->data['status'],
        ]);
        $this->assertDatabaseHas('phones', [
            'number' => $this->data['phones'][0]['number'],
            'type' => $this->data['phones'][0]['type'],
        ]);
        $this->assertDatabaseHas('addresses', [
            'id' => $accreditedNetwork->address->id,
            'zip_code' => $this->data['address']['zip_code'],
            'street' => $this->data['address']['street'],
            'number' => $this->data['address']['number'],
        ]);
    });

    it('Não enviando dados nenhum para atualizar uma  rede credenciada', function () {

        Sanctum::actingAs(
            User::query()->first(),
            ['*']
        );

        $accreditedNetwork = AccreditedNetworks::query()->first();
        $response = $this->postJson("api/accredited-networks/update/{$accreditedNetwork->id}", []);
        $response->assertStatus(422)->assertJsonStructure([
            'message',
            'errors'
        ]);
    });

    it('Usuário não autenticado', function () {
        $accreditedNetwork = AccreditedNetworks::query()->first();
        $response = $this->postJson("api/accredited-networks/update/{$accreditedNetwork->id}", []);
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

        $accreditedNetwork = AccreditedNetworks::query()->first();
        $response = $this->postJson("api/accredited-networks/update/{$accreditedNetwork->id}", []);
        $response->assertStatus(403)->assertJson([
            'message' => __('This action is unauthorized.'),
        ]);
    });
});
