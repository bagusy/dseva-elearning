<?php

namespace App\Notifications;

use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;

class EmailInvitation extends VerifyEmail
{
    use Queueable;

    private string $fromName;
    private string $token;

    public function __construct(string $fromName, string $token)
    {
        $this->fromName = $fromName;
        $this->token = $token;
    }

    public function via($notifiable): array
    {
        return [
            'mail'
        ];
    }

    public function toMail($notifiable)
    {
        $resetUrl = url('/password/reset/' . urlencode($this->token) . '?email=' . urlencode($notifiable['email']));

        return (new MailMessage)
            ->line('You are invited by ' . $this->fromName . ' to join Cyber-security training at Dseva')
            ->line('Please verify your email by clicking this link below')
            ->action('Verify Account', $resetUrl)
            ->line('Thank you!');
    }
}
