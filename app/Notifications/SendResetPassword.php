<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\HtmlString;

class SendResetPassword extends Notification
{
    use Queueable;
    protected $url;
    /**
     * Create a new notification instance.
     */
    public function __construct($url)
    {
        $this->url = $url;
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
        $url = url($this->url . '&email=' . $notifiable->getEmailForPasswordReset());

        return (new MailMessage)
            ->subject(__('Redefinir Senha'))
            ->greeting('Olá!')
            ->line('Você está recebendo este e-mail porque recebemos uma solicitação de redefinição de senha para sua conta.Clique em redefinir senha para prosseguir')
            ->action('Redefinir Senha', $url)
            ->line(__('Este link de redefinição de senha irá expirar em :count minutos.', ['count' => config('auth.passwords.' . config('auth.defaults.passwords') . '.expire')]))
            ->line(__('Se você não solicitou uma redefinição de senha, nenhuma ação adicional será necessária.'))
            ->salutation(new HtmlString('Cumprimentos,<br> 01 Saúde'));
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
