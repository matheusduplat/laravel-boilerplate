<?php

use App\Domains\Customer\Model\Customer;
use App\Domains\RequestManagement\Model\RequestManagement;
use App\Domains\RequestManagement\Notifications\RequestManagementNotification;
use App\Domains\RequestManagementResponse\Model\RequestManagementResponse;
use App\Domains\User\Model\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
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


describe('Read All Notifications', function () {

    it('Marca todas como lidas com sucesso sendo employee', function () {
        Sanctum::actingAs(
            User::query()->first(),
            ['*']
        );
        $user = User::find(1);
        Sanctum::actingAs($user);

        // Cria 3 notificações para o usuário
        $user->notify(new RequestManagementNotification('Notificação 1', RequestManagement::find(1)));
        $user->notify(new RequestManagementNotification('Notificação 2', RequestManagement::find(2)));
        $user->notify(new RequestManagementNotification('Notificação 3', RequestManagement::find(3)));

        expect($user->unreadNotifications)->toHaveCount(3);

        // Act: chama a API para marcar todas como lidas
        $response = $this->putJson('/api/notifications/read-all');

        $response->assertStatus(201)->assertJson([
            'message' => __("All marked as successfully read."),
        ]);

        // Refresh e valida
        $user->refresh();
        expect($user->unreadNotifications)->toHaveCount(0);
        expect($user->notifications()->whereNotNull('read_at')->count())->toBe(3);
    });

    it('Marca todas como lidas com sucesso sendo customer', function () {

        $user = Customer::find(1);

        Sanctum::actingAs(
            $user,
            ['*']
        );
        Sanctum::actingAs($user);

        // Cria 3 notificações para o usuário
        $user->notify(new RequestManagementNotification('Notificação 1', RequestManagement::find(1)));
        $user->notify(new RequestManagementNotification('Notificação 2', RequestManagement::find(2)));
        $user->notify(new RequestManagementNotification('Notificação 3', RequestManagement::find(3)));

        expect($user->unreadNotifications)->toHaveCount(3);

        // Act: chama a API para marcar todas como lidas
        $response = $this->putJson('/api/notifications/read-all');

        $response->assertStatus(201)->assertJson([
            'message' => __("All marked as successfully read."),
        ]);

        // Refresh e valida
        $user->refresh();
        expect($user->unreadNotifications)->toHaveCount(0);
        expect($user->notifications()->whereNotNull('read_at')->count())->toBe(3);
    });




    it('Usuário não autenticado', function () {
        $response = $this->putJson('api/notifications/read-all');
        $response->assertStatus(403)->assertJson([
            'message' => 'Unauthenticated.',
        ]);
    });
});
