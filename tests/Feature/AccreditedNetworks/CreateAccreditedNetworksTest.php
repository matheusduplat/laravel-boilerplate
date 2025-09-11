<?php

use App\Domains\AccreditedNetworks\Model\AccreditedNetworks;
use App\Domains\Address\Model\Address;
use App\Domains\Customer\Model\Customer;
use App\Domains\Phone\Model\Phone;
use App\Domains\Role\Model\Role;
use App\Domains\User\Model\User;
use App\Notifications\CreatePasswordNotification;
use App\Notifications\VerifyMailNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Laravel\Sanctum\Sanctum;

uses(RefreshDatabase::class);
beforeEach(function () {
    $this->seed();

    $accreditedNetWorks = AccreditedNetworks::factory()->make()->toArray();
    $address = Address::factory()->make()->toArray();

    $this->data = [
        ...$accreditedNetWorks,
        'phones' => Phone::factory()->count(3)->make()->toArray(),
        'address' => $address,
    ];
});


describe('Create accredited networks', function () {

    it('Criando uma nova rede credenciada com sucesso', function () {
        Sanctum::actingAs(
            User::query()->first(),
            ['*']
        );
        $response = $this->postJson('api/accredited-networks/store', $this->data);
        $response->assertStatus(201)->assertJson([
            'message' => __('Accredited Network created successfully.'),
        ]);

        $this->assertDatabaseHas('accredited_networks', [
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
            'zip_code' => $this->data['address']['zip_code'],
            'street' => $this->data['address']['street'],
            'number' => $this->data['address']['number'],
        ]);
    });

    it('Não enviando dados nenhum para criar uma nova rede credenciada', function () {

        Sanctum::actingAs(
            User::query()->first(),
            ['*']
        );

        $response = $this->postJson('api/accredited-networks/store', []);
        $response->assertStatus(422)->assertJsonStructure([
            'message',
            'errors'
        ]);
    });

    it('Usuário não autenticado', function () {
        $response = $this->postJson('api/accredited-networks/store', []);
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

        $response = $this->postJson('api/accredited-networks/store', $this->data);
        $response->assertStatus(403)->assertJson([
            'message' => __('This action is unauthorized.'),
        ]);
    });
});
