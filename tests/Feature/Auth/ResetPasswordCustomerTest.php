<?php

use App\Domains\Customer\Model\Customer;
use App\Domains\User\Model\User;
use App\Notifications\ResetPasswordNotification;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Password;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);
beforeEach(function () {
    $this->seed();
});

describe('Reset password Customer', function () {

    it('Envia notificação de redefinição de senha', function () {
        Notification::fake();

        $user = Customer::first();

        $this->postJson('/api/customer/forgot-password', [
            'email' => $user->email,
        ])
            ->assertStatus(200)
            ->assertJson([
                'message' => trans(Password::RESET_LINK_SENT, ['type' => "criar"]),
            ]);

        Notification::assertSentTo($user, ResetPasswordNotification::class);
    });


    it('Resetando senha', function () {
        Notification::fake();

        $user = Customer::first();

        // Solicita envio de token
        $this->postJson('/api/customer/forgot-password', [
            'email' => $user->email,
        ]);

        // Captura o token da notificação fake
        Notification::assertSentTo($user, ResetPasswordNotification::class, function ($notification) use (&$token) {
            $token =  $notification->getToken();
            return true;
        });

        $newPassword = 'new-password-123';

        $this->postJson('/api/customer/reset-password', [
            'email' => $user->email,
            'token' => $token,
            'password' => $newPassword,
            'password_confirmation' => $newPassword,
        ])
            ->assertStatus(200)
            ->assertJson([
                'message' => trans(Password::PASSWORD_RESET),
            ]);

        expect(Hash::check($newPassword, $user->fresh()->password))->toBeTrue();
    });

    // ✅ Teste: falha ao enviar reset para e-mail inexistente
    it('falha ao enviar reset para e-mail inexistente', function () {
        Notification::fake();

        $response = $this->postJson('/api/customer/forgot-password', [
            'email' => 'naoexiste@example.com',
        ]);

        $response->assertStatus(400) // erro de validação
            ->assertJsonStructure(['message']);

        Notification::assertNothingSent();
    });

    // ✅ Teste: falha ao resetar senha com token inválido
    it('falha ao resetar senha com token inválido', function () {
        $user = Customer::first();

        $this->postJson('/api/reset-password', [
            'email' => $user->email,
            'token' => 'token-invalido',
            'password' => 'nova-senha-123',
            'password_confirmation' => 'nova-senha-123',
        ])
            ->assertStatus(400)
            ->assertJsonStructure(['message']);

        // Verifica que a senha NÃO foi alterada
        expect(Hash::check('nova-senha-123', $user->fresh()->password))->toBeFalse();
    });
});
