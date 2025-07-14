<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\HtmlString;

class SendCodeVerificationNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(protected string $code)
    {
        $this->onQueue('mail');
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $appName = config('app.name');
        return (new MailMessage)
            ->subject('Código de verificação')
            ->greeting('Olá ' . $notifiable->name . '!')
            ->line('O seu código de verificação é: ' . $this->code)
            ->line('Esse código expira em 10 minutos.')
            ->line('Agradecemos por utilizar nossos serviços.')
            ->salutation(new HtmlString("Cumprimentos<br> Equipe  {$appName}"));
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
