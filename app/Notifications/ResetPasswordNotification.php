<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Auth\Notifications\ResetPassword;

use Illuminate\Notifications\Notification;

class ResetPasswordNotification extends Notification
{
    use Queueable;
    protected string $token;

    /**
     * Create a new notification instance.
     */
    public function __construct(string $token)
    {
        $this->token = $token;
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
        ->subject('🔒 Reset Your Password')
        ->greeting('Hello ' . ($notifiable->name ?? 'there') . ' 👋')
        ->line('We received a request to reset your account password.')
        ->line('Click the button below to set a new password:')
        ->action('🔁 Reset Password', url(route('password.reset', ['token' => $this->token, 'email' => $notifiable->getEmailForPasswordReset()])))
        ->line('This link will expire in 5 minutes.')
        ->line('If you didn’t request a password reset, you can safely ignore this email.')
        ->salutation("Thanks,\nThe " . config('app.name') . " Team");
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
