<?php

namespace App\Notifications;

use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\HtmlString;

class VerifyMailNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(protected string $url)
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
        return (new MailMessage)
            ->subject(__('Verificação de email'))
            ->greeting("Olá! {$notifiable->name}")
            ->line('Você se cadastrou no sistema ' . config('app.name') . ' e esse é um email de verificação, mas antes de poder utilizar o sistema, precisamos que confirme a verificação do email clicando no botão abaixo')
            ->action('Confirmar Verificação', $this->url)
            ->line(__('Caso não tenha sido você que efetuou o cadastro, ignore esse email'))
            ->salutation(new HtmlString('Cumprimentos<br>' . config('app.name')));
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
    public function failed(Exception $exception): void
    {
        // handle failed export
        Log::channel('notifications')->error(__($exception->getMessage()));
    }
}
