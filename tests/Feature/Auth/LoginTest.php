<?php

use App\Domains\CodeVerification\Model\CodeVerification;
use App\Domains\User\Model\User;
use App\Notifications\SendCodeVerificationNotification;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Str;

describe('Login', function () {

    it('Sucesso', function () {
        $data = [
            'email' => 'admin@email.com.br',
            'password' => 'admin'
        ];



        $response = $this->postJson('/api/login', $data);

        $response->assertStatus(200)->assertJsonStructure([
            'token',
            'user',
        ]);
    });

    it('Email não verificado', function () {
        $data = [
            'email' => 'admin@email.com.br',
            'password' => 'admin'
        ];

        $user = User::where('email', $data['email'])->first();
        $user->update(['email_verified_at' => null]);


        $response = $this->postJson('/api/login', $data);

        $response->assertStatus(403)->assertJson([
            'message' => 'Email not verified',
        ]);
    });


    it('Senha Incorreta', function () {
        $data = [
            'email' => 'admin@email.com.br',
            'password' => '123'
        ];



        $response = $this->postJson('/api/login', $data);

        $response->assertStatus(403)->assertJson([
            'message' => 'Invalid password',
        ]);
    });

    it('Email Incorreta', function () {
        $data = [
            'email' => 'admin@email.com',
            'password' => 'admin'
        ];



        $response = $this->postJson('/api/login', $data);

        $response->assertStatus(403)->assertJson([
            'message' => 'Invalid email',
        ]);
    });

    it('Sem envia email e senha', function () {
        $response = $this->postJson('/api/login', []);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['email', 'password']);
    });



    it('envia notificação de código ao tentar login', function () {
        Notification::fake();

        $data = [
            'email' => 'admin@email.com.br',
            'password' => 'admin'
        ];

        $user = User::where('email', $data['email'])->first();
        $user->update(['secure_login_email' => true]);


        $response = $this->postJson('/api/login', $data)
            ->assertOk()
            ->assertJson(['message' => 'Code sent to email']);

        Notification::assertSentTo($user, SendCodeVerificationNotification::class);
    });



    it('verifica código corretamente e salva dispositivo confiável', function () {
        $data = [
            'email' => 'admin@email.com.br',
            'password' => 'admin'
        ];

        $user = User::where('email', $data['email'])->first();
        $user->update(['secure_login_email' => true]);

        // Simula código salvo
        $code = '123456';
        CodeVerification::create([
            'email' => $user->email,
            'code' => $code,
            'expires_at' => now()->addMinutes(10),
        ]);

        // Simula dados de dispositivo
        $deviceToken = Str::uuid()->toString();
        $headers = [
            'X-Device-Token' => $deviceToken,
            'User-Agent' => 'Mozilla/5.0 Chrome/114.0',
        ];

        // Envia requisição
        $this->postJson('/api/verify-code', [
            'email' => $user->email,
            'code' => $code,
        ], $headers)
            ->assertOk()
            ->assertJsonStructure(['user', 'token']);

        // Verifica se o código foi removido
        $this->assertDatabaseMissing('code_verifications', [
            'email' => $user->email,
        ]);

        // Verifica se o dispositivo foi salvo
        $this->assertDatabaseHas('trusted_devices', [
            'user_id' => $user->id,
            'user_agent' => 'Mozilla/5.0 Chrome/114.0',
        ]);
    });

    it('retorna erro se código for inválido', function () {
        $data = [
            'email' => 'admin@email.com.br',
            'password' => 'admin'
        ];

        $user = User::where('email', $data['email'])->first();
        $user->update(['secure_login_email' => true]);

        // Código inexistente
        $this->postJson('/api/verify-code', [
            'email' => $user->email,
            'code' => '000000',
        ])
            ->assertStatus(401)
            ->assertJson(['message' => 'Code invalid or expired']);
    });
});
