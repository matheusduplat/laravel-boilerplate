<?php

use App\Domains\User\Model\User;
use App\Notifications\CreatePasswordNotification;
use Illuminate\Support\Facades\Notification;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);
beforeEach(function () {
    $this->seed();
});
describe('Resend Create Password', function () {
    it('reenvia convite para usuário existente', function () {
        Notification::fake();

        $user = User::factory()->create();
        $response = $this->getJson('/api/resend-create-password/' . $user->id);

        $response->assertStatus(200)
            ->assertJson([
                'message' => __('passwords.sent', ['type' => __("create")])
            ]);

        Notification::assertSentTo($user, CreatePasswordNotification::class);
    });

    it('falha ao reenviar convite para usuário inexistente', function () {
        Notification::fake();

        $nonExistingId = 99999;

        $response = $this->getJson('/api/resend-create-password/' . $nonExistingId);

        $response->assertStatus(404)->assertJson([
            'message' => __('exceptions.model_not_found', ['model' => 'User', 'ids' => $nonExistingId]),
        ]);;

        Notification::assertNothingSent();
    });
});
