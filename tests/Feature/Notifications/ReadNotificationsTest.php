<?php

use App\Domains\Customer\Model\Customer;
use App\Domains\RequestManagement\Model\RequestManagement;
use App\Domains\RequestManagement\Notifications\RequestManagementNotification;
use App\Domains\RequestManagementResponse\Model\RequestManagementResponse;
use App\Domains\User\Model\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
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


describe('Read Notifications', function () {

    it('Marcar notificações como lida com sucesso sendo employee', function () {
        // Arrange: cria um usuário autenticado
        $user = User::find(1);
        Sanctum::actingAs($user);

        // Envia notificação para o usuário
        $user->notify(new RequestManagementNotification('Teste de leitura', RequestManagement::find(1)));

        $notification = $user->notifications()->first();

        expect($notification)->not->toBeNull();
        expect($notification->read_at)->toBeNull();

        // Act: chama a API para marcar como lida
        $response = $this->putJson("api/notifications/read/{$notification->id}");
        $response->assertStatus(201)->assertJson([
            'message' => __("Marked as successfully read."),
        ]);

        // Refresh para validar read_at
        $notification->refresh();
        expect($notification->read_at)->not->toBeNull();
    });

    it('Marcar notificações como lida cpom sucesso sendo customer', function () {
        // Arrange: cria um usuário autenticado
        $user = Customer::find(1);
        Sanctum::actingAs($user);

        // Envia notificação para o usuário
        $user->notify(new RequestManagementNotification('Teste de leitura', RequestManagement::find(1)));

        $notification = $user->notifications()->first();

        expect($notification)->not->toBeNull();
        expect($notification->read_at)->toBeNull();

        // Act: chama a API para marcar como lida
        $response = $this->putJson("api/notifications/read/{$notification->id}");
        $response->assertStatus(201)->assertJson([
            'message' => __("Marked as successfully read."),
        ]);

        // Refresh para validar read_at
        $notification->refresh();
        expect($notification->read_at)->not->toBeNull();
    });
    it('Usuário não autenticado', function () {
        $user = User::find(1);
        // Envia notificação para o usuário
        $user->notify(new RequestManagementNotification('Teste de leitura', RequestManagement::find(1)));
        $notification = $user->notifications()->first();
        $response = $this->putJson("api/notifications/read/{$notification->id}");
        $response->assertStatus(403)->assertJson([
            'message' => 'Unauthenticated.',
        ]);
    });
    it('Id do funcionário inválido', function () {

        Sanctum::actingAs(
            User::query()->first(),
            ['*']
        );


        $response = $this->putJson("api/notifications/read/66");
        $response->assertStatus(404)->assertJson([
            'message' => __('exceptions.model_not_found', ['model' => 'DatabaseNotification', 'ids' => "66"]),
        ]);
    });
});
