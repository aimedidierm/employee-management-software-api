<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ResetPasswordNotification extends Notification
{
    use Queueable;

    protected $token;

    /**
     * Create a new notification instance.
     *
     * @param string $token
     */
    public function __construct($token)
    {
        $this->token = $token;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via($notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail($notifiable): MailMessage
    {
        $frontendAppUrl = env('FRONTEND_APP_URL');
        $url = "{$frontendAppUrl}/reset-password?token={$this->token}&email={$notifiable->email}";


        return (new MailMessage)
            ->subject(trans('Reset Password Notification'))
            ->line(trans('You are receiving this email because we received a password reset request for your account.'))
            ->action(trans('Reset Password'), $url)
            ->line(trans('If you did not request a password reset, no further action is required.'))
            ->line(trans('Thank you for using our application!'));
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray($notifiable): array
    {
        return [
            // You can add additional data here if needed
        ];
    }
}
