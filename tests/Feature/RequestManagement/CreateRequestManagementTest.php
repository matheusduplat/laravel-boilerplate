<?php

use App\Domains\Customer\Model\Customer;
use App\Domains\Employee\Model\Employee;
use App\Domains\RequestManagement\Model\RequestManagement;
use App\Domains\User\Model\User;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Laravel\Sanctum\Sanctum;

uses(RefreshDatabase::class);
beforeEach(function () {
    $this->seed();
    $customer = Customer::query()->first();
    $this->data = RequestManagement::factory()
        ->make([
            'customer_id' => $customer->id
        ])
        ->toArray();
    // $this->withoutExceptionHandling();
});


describe('Create Request Management', function () {

    it('Criando uma nova solicitação com sucesso', function () {
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


        $response = $this->postJson('api/request-management/store', $this->data);
        $response->assertStatus(201)->assertJson([
            'message' => __('Request Management created successfully.'),
        ]);

        $this->assertDatabaseHas('request_management', [
            'customer_id' => $this->data['customer_id'],
            "type" => $this->data['type'],
            "status" => $this->data['status'],
            'note_customer' => $this->data['note_customer'],
        ]);
        $this->assertDatabaseHas('request_management_responsible', [
            'employee_id' => $this->data['responsible'][0],
        ]);
    });

    it('Criando uma nova solicitação com sucesso usando customer', function () {
        $customer = Customer::query()->first();
        Sanctum::actingAs(
            $customer,
            ['*']
        );

        $this->data['date_close'] = fake()->optional()->date();
        $this->data['customer_id'] = $customer->id;


        if (isset($this->data['attachment'])) {
            Storage::fake('public');

            $file = UploadedFile::fake()->create('documento.pdf', 200, 'application/pdf');

            $this->data['attachment'] = $file;
        }


        $response = $this->postJson('api/request-management/store', $this->data);
        $response->assertStatus(201)->assertJson([
            'message' => __('Request Management created successfully.'),
        ]);

        $this->assertDatabaseHas('request_management', [
            'customer_id' => $this->data['customer_id'],
            "type" => $this->data['type'],
            "status" => $this->data['status'],
            'note_customer' => $this->data['note_customer'],
        ]);
    });

    it('Não enviando dados nenhum para criar uma nova rede credenciada', function () {

        Sanctum::actingAs(
            User::query()->first(),
            ['*']
        );

        $response = $this->postJson('api/request-management/store', []);
        $response->assertStatus(422)->assertJsonStructure([
            'message',
            'errors'
        ]);
    });

    it('Usuário não autenticado', function () {
        $response = $this->postJson('api/request-management/store', []);
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

        $response = $this->postJson('api/request-management/store', $this->data);
        $response->assertStatus(403)->assertJson([
            'message' => __('This action is unauthorized.'),
        ]);
    });
});
