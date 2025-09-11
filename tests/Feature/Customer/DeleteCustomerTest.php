<?php

use App\Domains\Address\Model\Address;
use App\Domains\Customer\Model\Customer;
use App\Domains\Phone\Enums\PhoneType;
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

    Customer::factory()
        ->has(Phone::factory()->count(2), 'phones')
        ->has(Address::factory(), 'address')
        ->create([
            'password' => '12345678',
        ]);
});


describe('Deleted Customer', function () {

    it('Deletando um cliente com sucesso', function () {
        Sanctum::actingAs(
            User::query()->first(),
            ['*']
        );
        $customer = Customer::orderBy('id', 'desc')->first();

        $response = $this->deleteJson("api/customer/destroy/{$customer->id}");
        $response->assertStatus(201)->assertJson([
            'message' => __('Customer deleted successfully.'),
        ]);

        $this->assertSoftDeleted('customers', [
            'id' => $customer->id,
        ]);
        $this->assertSoftDeleted('phones', [
            'phoneable_id' => $customer->id,
            'phoneable_type' => 'Customer'
        ]);

        $this->assertSoftDeleted('addresses', [
            'addressable_id' => $customer->id,
            'addressable_type' => 'Customer'
        ]);
    });



    it('Usuário não autenticado', function () {
        $customer = Customer::with(['phones', 'address'])->orderBy('id', 'desc')->first();
        $response = $this->deleteJson("api/customer/destroy/{$customer->id}");
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

        $customer = Customer::with(['phones', 'address'])->orderBy('id', 'desc')->first();
        $response = $this->deleteJson("api/customer/destroy/{$customer->id}");
        $response->assertStatus(403)->assertJson([
            'message' => __('This action is unauthorized.'),
        ]);
    });

    it('Id do cliente inválido', function () {

        Sanctum::actingAs(
            User::query()->first(),
            ['*']
        );


        $response = $this->deleteJson('api/customer/destroy/66');
        $response->assertStatus(404)->assertJson([
            'message' => 'O registro de Customer com ID(s) 66 não foi encontrado.',
        ]);
    });
});
