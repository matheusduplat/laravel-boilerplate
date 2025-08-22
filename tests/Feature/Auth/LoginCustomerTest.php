<?php

use App\Domains\CodeVerification\Model\CodeVerification;
use App\Domains\Customer\Model\Customer;
use App\Domains\User\Model\User;
use App\Notifications\SendCodeVerificationNotification;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Str;
use Illuminate\Foundation\Testing\RefreshDatabase;

$data = [
    'cpf' => "123.456.789-00",
    'password' => 'customer'
];
uses(RefreshDatabase::class);
beforeEach(function () {
    $this->seed();
});
describe('Login Customer', function () use ($data) {

    it('Sucesso', function () use ($data) {
        $response = $this->postJson('/api/customer/login', $data);

        $response->assertStatus(200)->assertJsonStructure([
            'token',
            'user',
        ]);
    });

    it('Email não verificado', function () use ($data) {


        $user = Customer::where('cpf', $data['cpf'])->first();
        $user->update(['email_verified_at' => null]);


        $response = $this->postJson('/api/customer/login', $data);

        $response->assertStatus(403)->assertJson([
            'message' => __('Email not verified'),
        ]);
    });


    it('Senha Incorreta', function () use ($data) {
        $data['password'] = '123456';

        $response = $this->postJson('/api/customer/login', $data);

        $response->assertStatus(403)->assertJson([
            'message' => __('Invalid password'),
        ]);
    });

    it('Cpf Incorreta', function () use ($data) {
        $data['cpf'] = '000.000.000-00';

        $response = $this->postJson('/api/customer/login', $data);

        $response->assertStatus(403)->assertJson([
            'message' => __('Customer not found'),
        ]);
    });

    it('Sem envia cpf e senha', function () {
        $response = $this->postJson('/api/customer/login', []);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['cpf', 'password']);
    });



    it('envia notificação de código ao tentar login', function () use ($data) {
        Notification::fake();

        $user = Customer::where('cpf', $data['cpf'])->first();
        $user->update(['secure_login_email' => true]);


        $response = $this->postJson('/api/customer/login', $data)
            ->assertOk()
            ->assertJson(['message' => __('Code sent to email')]);

        Notification::assertSentTo($user, SendCodeVerificationNotification::class);
    });



    it('verifica código corretamente e salva dispositivo confiável', function () use ($data) {

        $user = Customer::where('cpf', $data['cpf'])->first();
        $user->update(['secure_login_email' => true]);

        // Simula código salvo
        $code = '123456';
        CodeVerification::create([
            'email' => $user->email,
            'code' => $code,
            'guard' => 'customer',
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
            'owner_id' => $user->id,
            'owner_type' => $user->getMorphClass(),
            'user_agent' => 'Mozilla/5.0 Chrome/114.0',
        ]);
    });

    it('retorna erro se código for inválido', function () use ($data) {

        $user = Customer::where('cpf', $data['cpf'])->first();
        $user->update(['secure_login_email' => true]);

        // Código inexistente
        $this->postJson('/api/verify-code', [
            'email' => $user->email,
            'code' => '000000',
        ])
            ->assertStatus(401)
            ->assertJson(['message' => __('Code invalid or expired')]);
    });
});
