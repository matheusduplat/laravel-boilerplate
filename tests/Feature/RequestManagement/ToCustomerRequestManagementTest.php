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
        ->count(5)
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


describe('To Customer Request Management', function () {

    it('Retorna lista de solicitação com sucesso paginado', function () {
        $customer = Customer::query()->first();
        Sanctum::actingAs(
            $customer,
            ['*']
        );

        $response = $this->getJson("api/request-management/to-customer?with_trashed=0");
        $response->assertStatus(200);
        $response->assertJsonCount(5, 'items');
        $response->assertJsonStructure([
            'items',
            'pagination_more',
            'perPage',
            'total',
            'lastPage',
            'currentPage',
        ]);
    });
    it('Usuário não autenticado', function () {

        $response = $this->getJson("api/request-management/to-customer?with_trashed=0");
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


        $response = $this->getJson("api/request-management/to-customer?with_trashed=0");
        $response->assertStatus(403)->assertJson([
            'message' => __('This action is unauthorized.'),
        ]);
    });
});
