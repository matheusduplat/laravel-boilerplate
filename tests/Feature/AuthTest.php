<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthTest extends TestCase
{

    /**
     * A basic feature test login.
     */
    public function test_login_success(): void
    {
        $user = User::factory()->create();
        $response = $this->postJson('api/login', ['email' => $user->email, 'password' => 'password'], ['Accept' => 'application/json']);

        $response->assertStatus(200);
    }
    public function test_login_failed(): void
    {
        $user = User::factory()->create();
        $response = $this->postJson('api/login', ['email' => $user->email, 'password' => 'password1'], ['Accept' => 'application/json']);

        $response->assertStatus(401)->assertJson(['error' => 'Email ou senha invalido']);
    }
    public function test_me(): void
    {
        $user = User::factory()->create();
        $token = JWTAuth::fromUser($user);
        $headers = [

            'Authorization' => 'Bearer ' . $token,
            'Accept' => 'application/json'

        ];

        $response = $this->getJson('api/me', $headers);

        $response->assertStatus(200);
    }
    public function test_refresh(): void
    {
        $user = User::factory()->create();
        $token = JWTAuth::fromUser($user);
        $headers = [
            'Authorization' => 'Bearer ' . $token,
            'Accept' => 'application/json'
        ];

        $response = $this->getJson('api/refresh', $headers);

        $response->assertStatus(200)->assertJsonStructure(['token', 'user']);
    }
    public function test_logout(): void
    {
        $user = User::factory()->create();
        $token = JWTAuth::fromUser($user);
        $headers = [
            'Authorization' => 'Bearer ' . $token,
            'Accept' => 'application/json'
        ];

        $response = $this->getJson('api/logout', $headers);

        $response->assertStatus(200)->assertJson(['message' => 'Deslogado com sucesso']);
    }
}
