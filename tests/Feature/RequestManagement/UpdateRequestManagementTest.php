<?php

use App\Domains\Customer\Model\Customer;
use App\Domains\Employee\Model\Employee;
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
    $requestManagement =  RequestManagement::factory()->make()->toArray();

    $this->data = [
        ...$requestManagement,
        'response' => RequestManagementResponse::factory()->make()->toArray(),
    ];

    $customer = Customer::query()->first();
    $user = User::query()->first();

    RequestManagement::factory()
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


describe('Update Request Management', function () {

    it('Atualizar uma  solicitação com sucesso', function () {
        Sanctum::actingAs(
            User::query()->first(),
            ['*']
        );

        $this->data['date_close'] = fake()->optional()->date();
        $this->data['responsible'] = Employee::factory()->user()->count(2)->create()->pluck('id')->toArray();

        if (isset($this->data['attachment'])) {
            Storage::fake('public');

            $file = UploadedFile::fake()->create('documento.pdf', 200, 'application/pdf');

            $this->data['attachment'] = $file;
        }

        $requestManagement = RequestManagement::query()->first();

        $response = $this->postJson("api/request-management/update/{$requestManagement->id}", $this->data);
        $response->assertStatus(201)->assertJson([
            'message' => __('Request Management updated successfully.'),
        ]);

        $this->assertDatabaseHas('request_management', [
            'id' => $requestManagement->id,
            "type" => $this->data['type'],
            "status" => $this->data['status'],
            'note_customer' => $this->data['note_customer'],
        ]);
        $this->assertDatabaseHas('request_management_responses', [
            'request_management_id' => $requestManagement->id,
            'answer' => $this->data['response']['answer'],
        ]);
        $this->assertDatabaseHas('request_management_responsible', [
            'employee_id' => $this->data['responsible'][0],
            'request_management_id' => $requestManagement->id,
        ]);
    });
    it('Atualizar uma  solicitação com sucesso com customer', function () {
        $customer = Customer::query()->first();
        Sanctum::actingAs(
            $customer,
            ['*']
        );

        $this->data['date_close'] = fake()->optional()->date();


        if (isset($this->data['attachment'])) {
            Storage::fake('public');

            $file = UploadedFile::fake()->create('documento.pdf', 200, 'application/pdf');

            $this->data['attachment'] = $file;
        }

        $requestManagement = RequestManagement::query()->first();

        $response = $this->postJson("api/request-management/update/{$requestManagement->id}", $this->data);
        $response->assertStatus(201)->assertJson([
            'message' => __('Request Management updated successfully.'),
        ]);

        $this->assertDatabaseHas('request_management', [
            'id' => $requestManagement->id,
            "type" => $this->data['type'],
            "status" => $this->data['status'],
            'note_customer' => $this->data['note_customer'],
        ]);
        $this->assertDatabaseHas('request_management_responses', [
            'request_management_id' => $requestManagement->id,
            'answer' => $this->data['response']['answer'],
        ]);
    });
    it('Não enviando dados nenhum para atualizar  uma  solicitação', function () {

        Sanctum::actingAs(
            User::query()->first(),
            ['*']
        );

        $requestManagement = RequestManagement::query()->first();

        $response = $this->postJson("api/request-management/update/{$requestManagement->id}", []);
        $response->assertStatus(422)->assertJsonStructure([
            'message',
            'errors'
        ]);
    });

    it('Usuário não autenticado', function () {
        $requestManagement = RequestManagement::query()->first();

        $response = $this->postJson("api/request-management/update/{$requestManagement->id}", []);
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

        $response = $this->postJson("api/request-management/update/{$requestManagement->id}", $this->data);
        $response->assertStatus(403)->assertJson([
            'message' => __('This action is unauthorized.'),
        ]);
    });
});
