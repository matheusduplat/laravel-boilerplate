<?php

namespace App\Domains\IncomeReport\Notifications;

use App\Domains\IncomeReport\Http\Resources\IncomeReportResource;
use App\Domains\IncomeReport\Model\IncomeReport;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class IncomeReportNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(protected string $message, public IncomeReport $incomeReport)
    {
        $this->onQueue('default');
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database', 'broadcast'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->line('The introduction to the notification.')
            ->action('Notification Action', url('/'))
            ->line('Thank you for using our application!');
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'message'   => $this->message,
            'incomeReport' => new IncomeReportResource($this->incomeReport)
        ];
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return $this->toDatabase($notifiable);
    }
    public function databaseType(object $notifiable): string
    {
        return 'incomeReportNotification';
    }

    public function toBroadcast(object $notifiable): BroadcastMessage
    {
        return (new BroadcastMessage($this->toDatabase($notifiable)))->onQueue('default');
    }
    public function broadcastType(): string
    {
        return 'incomeReportNotification';
    }
}
