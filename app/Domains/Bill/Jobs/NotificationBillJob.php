<?php

namespace App\Domains\Bill\Jobs;

use App\Domains\Bill\Model\Bill;
use App\Domains\Bill\Notification\BillNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Carbon;

class NotificationBillJob implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        $this->onQueue('default');
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $bills = Bill::query()
            ->with(['customer'])
            ->where('notified', false)
            ->get();
        foreach ($bills as $bill) {
            $date = Carbon::parse("{$bill->base_year}-{$bill->base_month}-01");
            $mount = $date->format('F');
            $mount = __($mount);
            $message = "Seu boleto do mês de {$mount} de {$date->format('Y')} já está disponível.";

            $bill->customer->notify(new BillNotification($message, $bill));
            $bill->update(['notified' => true]);
        }
    }
}
