<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Tests\TestCase;

class ResetPasswordTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_forgot_password_failed(): void
    {
        $response = $this->postJson('/api/forgot-password');

        $response->assertStatus(422)->assertJsonStructure(['error']);
    }
    public function test_forgot_password_success(): void
    {
        $user = User::factory()->create();
        $response = $this->postJson('/api/forgot-password', ['email' => $user->email]);

        $response->assertStatus(200)->assertExactJson(['Email de redefinição de senha enviado.']);
    }
    public function test_reset_password_success(): void
    {
        $user = User::factory()->create();

        $token = Password::createToken($user);
        $payload = [
            'email' => $user->email,
            'password' => 'newpassword',
            'password_confirmation' => 'newpassword',
            'token' => $token
        ];

        $response = $this->postJson('/api/reset-password', $payload);

        $this->assertTrue(Hash::check('newpassword', User::first()->password));

        $response->assertStatus(200)->assertExactJson(['Senha Redefinida com Sucesso.']);
    }
    public function test_reset_password_failed(): void
    {
        $response = $this->postJson('/api/reset-password');

        $response->assertStatus(422)->assertJsonStructure(['error']);
    }
}
