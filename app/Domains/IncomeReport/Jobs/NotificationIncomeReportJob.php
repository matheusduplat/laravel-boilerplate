<?php

namespace App\Domains\IncomeReport\Jobs;

use App\Domains\IncomeReport\Model\IncomeReport;
use App\Domains\IncomeReport\Notifications\IncomeReportNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class NotificationIncomeReportJob implements ShouldQueue
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
        $incomeReports = IncomeReport::query()
            ->with(['customer'])
            ->where('notified', false)
            ->get();


        foreach ($incomeReports as $incomeReport) {
            $message = "Seu Informe de rendimento do ano de {$incomeReport->year} já está disponível.";


            $incomeReport->customer->notify(new IncomeReportNotification($message, $incomeReport));
            $incomeReport->update(['notified' => true]);
        }
    }
}
