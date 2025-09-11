<?php

use App\Domains\Customer\Model\Customer;
use App\Domains\RequestManagement\Model\RequestManagement;
use App\Domains\RequestManagementResponse\Model\RequestManagementResponse;
use App\Domains\User\Model\User;
use DragonCode\Contracts\Cashier\Http\Request;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Laravel\Sanctum\Sanctum;

uses(RefreshDatabase::class);
beforeEach(function () {
    $this->seed();
    $customer = Customer::query()->first();
    $user = User::query()->first();

    RequestManagement::factory()
        ->count(3)
        ->has(
            RequestManagementResponse::factory()->count(1)->for($customer, "authorable"),
            'responses'
        )
        ->has(
            RequestManagementResponse::factory()->count(1)->for($user, "authorable"),
            'responses'
        )
        ->create([
            'customer_id' => $customer->id
        ]);

    // $this->withoutExceptionHandling();
});


describe('Show Request Management', function () {

    it('Retorna uma  solicitação com sucesso', function () {
        Sanctum::actingAs(
            User::query()->first(),
            ['*']
        );

        $requestManagement = RequestManagement::query()->first();

        $response = $this->getJson("api/request-management/show/{$requestManagement->id}");
        $response->assertStatus(200);
        $response->assertJsonFragment([
            'id' => $requestManagement->id,
            'type' => $requestManagement->type,
        ]);
    });
    it('Retorna uma  solicitação com sucesso com customer', function () {
        $customer = Customer::query()->first();
        Sanctum::actingAs(
            $customer,
            ['*']
        );

        $requestManagement = RequestManagement::query()->first();

        $response = $this->getJson("api/request-management/show/{$requestManagement->id}");
        $response->assertStatus(200);
        $response->assertJsonFragment([
            'id' => $requestManagement->id,
            'type' => $requestManagement->type,
        ]);
    });

    it('Usuário não autenticado', function () {
        $requestManagement = RequestManagement::query()->first();

        $response = $this->getJson("api/request-management/show/{$requestManagement->id}");
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

        $requestManagement = RequestManagement::query()->first();

        $response = $this->getJson("api/request-management/show/{$requestManagement->id}");
        $response->assertStatus(403)->assertJson([
            'message' => __('This action is unauthorized.'),
        ]);
    });
});
