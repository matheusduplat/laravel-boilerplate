<?php

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
    Customer::factory()
        ->has(Phone::factory()->count(2), 'phones')
        ->has(Address::factory(), 'address')
        ->has(User::factory()->customer(), 'user')
        ->create();
    // $this->withoutExceptionHandling();
});


describe('Restore Customer', function () {

    it('Restaurando cliente com sucesso', function () {
        Sanctum::actingAs(
            User::query()->first(),
            ['*']
        );

        $customer = Customer::query()->with(['address', 'phones'])->orderBy('id', 'desc')->first();
        $customer->delete();

        expect($customer->fresh()->trashed())->toBeTrue();


        $response = $this->getJson('api/customer/restore/' . $customer->id);
        $response->assertStatus(201)->assertJson([
            'message' => __('Customer restored successfully.'),
        ]);

        expect($customer->fresh()->trashed())->toBeFalse();

        expect($customer->address)->not()->toBeNull();

        // Garantir que phones ainda existem
        expect($customer->phones)->toHaveCount(2);
    });

    it('Usuário não autenticado', function () {

        $customer = Customer::query()->orderBy('id', 'desc')->first();


        $response = $this->getJson('api/customer/restore/' . $customer->id);
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

        $customer = Customer::query()->orderBy('id', 'desc')->first();


        $response = $this->getJson('api/customer/restore/' . $customer->id);
        $response->assertStatus(403)->assertJson([
            'message' => __('This action is unauthorized.'),
        ]);
    });

    it('Id do funcionário inválido', function () {

        Sanctum::actingAs(
            User::query()->first(),
            ['*']
        );


        $response = $this->getJson('api/customer/restore/66');
        $response->assertStatus(404)->assertJson([
            'message' => 'O registro de Customer com ID(s) 66 não foi encontrado.',
        ]);
    });
});
