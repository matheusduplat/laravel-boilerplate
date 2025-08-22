<?php

namespace App\Notifications;

use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\HtmlString;

class ResetPasswordNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(protected string $token, protected string $url)
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

        $url = $this->url . '?token=' . $this->token  . '&email=' . $notifiable->email;
        return (new MailMessage)
            ->subject(__('Redefinir Senha'))
            ->greeting("Olá! {$notifiable->name}")
            ->line('Você está recebendo este e-mail porque recebemos uma solicitação de redefinição de senha para sua conta. Clique em redefinir senha para prosseguir:')
            ->action('Redefinir Senha', url($url))
            // ->line(__('Este link de redefinição de senha irá expirar em :count minutos.', ['count' => config('auth.passwords.' . config('auth.defaults.passwords') . '.expire')]))
            ->line(__('Este link de redefinição de senha irá expirar em 5 horas.'))
            ->line(__('Se você não solicitou uma redefinição de senha, nenhuma ação adicional será necessária.'))
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
    public function getToken()
    {
        return $this->token;
    }
    public function failed(Exception $exception): void
    {
        // handle failed export
        Log::channel('notifications')->error(__($exception->getMessage()));
    }
}
