<?php

use App\Domains\Bill\Model\Bill;
use App\Domains\User\Model\User;
use Illuminate\Support\Facades\Http;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);
beforeEach(function () {
    $this->seed();
    $this->seed('CreateCustomerMocksSeeder');
});
describe('Download Bill', function () {
    it('retorna um PDF autenticado', function () {
        // cria usuário fake
        $user = User::first();

        // simula resposta da API externa (caso seu controller busque em outra API)
        $fakePdf = base64_encode('%PDF-1.4 FAKE PDF FILE');
        Http::fake([
            'https://minha-api.com/pdf*' => Http::response(['file' => $fakePdf], 200),
        ]);

        $bill = Bill::first();
        // chama a rota autenticado
        $response = $this->actingAs($user)
            ->get("api/bill/download/{$bill->title_id}");

        // valida se retornou status 200
        $response->assertStatus(200);

        // valida headers
        $response->assertHeader('Content-Type', 'application/pdf');
        $response->assertHeader('Content-Disposition', 'inline; filename="boleto-' . $bill->base_month . '.pdf"');
    });
});
