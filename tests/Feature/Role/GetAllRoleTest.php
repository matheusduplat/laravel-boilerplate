<?php

use App\Domains\Permission\Model\Permission;
use App\Domains\Role\Model\Role;
use App\Domains\User\Model\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;

uses(RefreshDatabase::class);
beforeEach(function () {
    $this->seed();
    Role::factory()->count(4)->hasAttached(Permission::get()->random(3), [], 'permissions')->create();
});


describe('Get All Role', function () {
    $url = 'api/role';
    it('Retorna a lista de funcionário', function () use ($url) {
        Sanctum::actingAs(
            User::query()->first(),
            ['*']
        );
        $response = $this->getJson("{$url}?with_trashed=1");
        $response->assertStatus(200)->assertJsonCount(5);
    });
    it('Usuário não autenticado', function () use ($url) {
        $response = $this->getJson($url);
        $response->assertStatus(403)->assertJson([
            'message' => 'Unauthenticated.',
        ]);
    });

    it('Usuário sem permissão', function () use ($url) {
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

        $response = $this->getJson($url);
        $response->assertStatus(403)->assertJson([
            'message' => __('This action is unauthorized.'),
        ]);
    });
});
