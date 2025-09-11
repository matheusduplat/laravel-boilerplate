<?php

use App\Domains\Address\Model\Address;
use App\Domains\Customer\Model\Customer;
use App\Domains\Employee\Model\Employee;
use App\Domains\Phone\Model\Phone;
use App\Domains\Role\Model\Role;
use App\Domains\User\Model\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;

uses(RefreshDatabase::class);
beforeEach(function () {
    $this->seed();
    Customer::factory()
        ->count(5)
        ->has(Phone::factory()->count(2), 'phones')
        ->has(Address::factory(), 'address')
        ->has(User::factory()->customer(), 'user')
        ->create();
});


describe('Get All Customer', function () {

    it('Retorna a lista de cliente', function () {
        Sanctum::actingAs(
            User::query()->first(),
            ['*']
        );
        $response = $this->getJson('api/customer?with_trashed=1');
        $response->assertStatus(200)->assertJsonCount(6);
    });
    it('Usuário não autenticado', function () {
        $response = $this->getJson('api/customer');
        $response->assertStatus(403)->assertJson([
            'message' => 'Unauthenticated.',
        ]);
    });

    it('Usuário sem permissão', function () {
        $user =  User::factory()->for(Employee::factory(), 'userable')->create();

        Sanctum::actingAs(
            $user,
            ['*']
        );

        $response = $this->getJson('api/customer');
        $response->assertStatus(403)->assertJson([
            'message' => __('This action is unauthorized.'),
        ]);
    });
});
