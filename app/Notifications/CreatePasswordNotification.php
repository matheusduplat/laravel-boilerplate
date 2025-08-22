<?php

namespace App\Notifications;

use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\HtmlString;

class CreatePasswordNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(protected $token, protected $url)
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

        $url = $this->url . '?token=' . $this->token . '&email=' . $notifiable->email;


        return (new MailMessage)
            ->subject('Crie sua senha')
            ->greeting("Olá! {$notifiable->name}")
            ->line('Seja bem vindo ao nosso sistema ' . config('app.name') . ' e esse é um email de criação de senha, mas antes de poder utilizar o sistema, precisamos que crie uma senha clicando no botão abaixo')
            ->action('Criar Senha', url($url))
            // ->line(__('Este link de criação de senha irá expirar em :count minutos.', ['count' => config('auth.passwords.create_password.expire')]))
            ->line(__('Este link de criação de senha irá expirar em 3 dias.'))
            ->line(__('Se você não solicitou uma criação de senha, nenhuma ação adicional será necessária.'))
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
