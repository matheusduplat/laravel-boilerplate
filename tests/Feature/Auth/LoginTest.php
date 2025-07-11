<?php


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


    it('Senha Incorreta', function () {
        $data = [
            'email' => 'admin@email.com.br',
            'password' => '123'
        ];



        $response = $this->postJson('/api/login', $data);

        $response->assertStatus(403)->assertJson([
            'message' => 'Senha invalida',
        ]);
    });

    it('Email Incorreta', function () {
        $data = [
            'email' => 'admin@email.com',
            'password' => 'admin'
        ];



        $response = $this->postJson('/api/login', $data);

        $response->assertStatus(403)->assertJson([
            'message' => 'Email invalido',
        ]);
    });
    it('Sem envia email e senha', function () {
        $response = $this->postJson('/api/login', []);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['email', 'password']);
    });
});
