<?php

use App\Domains\Bill\Jobs\NotificationBillJob;
use App\Domains\Bill\Model\Bill;
use App\Domains\Bill\Notification\BillNotification;
use App\Domains\Customer\Model\Customer;
use App\Domains\IncomeReport\Jobs\NotificationIncomeReportJob;
use App\Domains\IncomeReport\Model\IncomeReport;
use App\Domains\IncomeReport\Notifications\IncomeReportNotification;
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
        $incomeReport = IncomeReport::factory()->create([
            'customer_id' => $this->user->id,
            'notified'  => false,
        ]);

        // roda o job
        (new NotificationIncomeReportJob())->handle();

        // verifica se a notificação foi enviada
        Notification::assertSentTo(
            [$this->user],
            IncomeReportNotification::class,
            function ($notification, $channels) use ($incomeReport) {
                return $notification->incomeReport->id === $incomeReport->id;
            }
        );

        // garante que foi marcado como notificado
        expect($incomeReport->fresh()->notified)->toBeTrue();
    });

    it('não envia notificação para boletos já notificados', function () {
        // cria boleto já notificado
        $incomeReport = IncomeReport::factory()->create([
            'customer_id' => $this->user->id,
            'notified'  => true,
        ]);

        // roda o job
        (new NotificationIncomeReportJob())->handle();
        // verifica que não houve notificação
        Notification::assertNothingSent();

        // continua marcado como notificado
        expect($incomeReport->fresh()->notified)->toBeTrue();
    });
});
