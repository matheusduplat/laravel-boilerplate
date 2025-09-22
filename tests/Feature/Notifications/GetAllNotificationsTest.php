<?php


use App\Domains\User\Model\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;

uses(RefreshDatabase::class);
beforeEach(function () {
    $this->seed();
});


describe('Get All Notifications', function () {

    it('Retorna a lista de notificações', function () {
        Sanctum::actingAs(
            User::query()->first(),
            ['*']
        );
        $response = $this->getJson('api/notifications');
        $response->assertStatus(200)->assertJsonStructure([
            'readNotifications',
            "unreadNotifications"
        ]);
    });
    it('Usuário não autenticado', function () {
        $response = $this->getJson('api/notifications');
        $response->assertStatus(403)->assertJson([
            'message' => 'Unauthenticated.',
        ]);
    });
});
