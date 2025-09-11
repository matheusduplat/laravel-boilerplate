<?php

use App\Domains\Bill\Jobs\NotificationBillJob;
use App\Domains\Bill\Model\Bill;
use App\Domains\Bill\Notification\BillNotification;
use App\Domains\Customer\Model\Customer;
use Illuminate\Support\Facades\Notification;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);
beforeEach(function () {
    $this->seed();
    Notification::fake();
    $this->user = Customer::factory()->create();
});



describe('Notificação de boletos', function () {
    it('envia notificação para boletos não notificados', function () {
        // cria boleto ainda não notificado
        $bill = Bill::factory()->create([
            'customer_id' => $this->user->id,
            'notified'  => false,
        ]);

        // roda o job
        (new NotificationBillJob())->handle();

        // verifica se a notificação foi enviada
        Notification::assertSentTo(
            [$this->user],
            BillNotification::class,
            function ($notification, $channels) use ($bill) {
                return $notification->bill->id === $bill->id;
            }
        );

        // garante que foi marcado como notificado
        expect($bill->fresh()->notified)->toBeTrue();
    });

    it('não envia notificação para boletos já notificados', function () {
        // cria boleto já notificado
        $bill = Bill::factory()->create([
            'customer_id' => $this->user->id,
            'notified'  => true,
        ]);

        // roda o job
        (new NotificationBillJob())->handle();
        // verifica que não houve notificação
        Notification::assertNothingSent();

        // continua marcado como notificado
        expect($bill->fresh()->notified)->toBeTrue();
    });
});
