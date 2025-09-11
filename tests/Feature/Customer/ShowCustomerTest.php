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


describe('Show Customer', function () {

    it('Retorna somente um cliente', function () {
        Sanctum::actingAs(
            User::query()->first(),
            ['*']
        );
        $customer = Customer::with(['phones'])->inRandomOrder()->first();
        $response = $this->getJson('api/customer/show/' . $customer->id);
        $response->assertStatus(200);
        $response->assertJsonFragment([
            'id' => $customer->id,
            'name' => $customer->name,
        ]);
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
