<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ResetPasswordNotification extends Notification
{
    use Queueable;

    public function __construct(
        public string $token
    ) {
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $frontendUrl = rtrim(
            config('app.frontend_url', 'http://localhost:3000'),
            '/'
        );

        $url = $frontendUrl
            . '/reset-password?token=' . $this->token
            . '&email=' . urlencode($notifiable->getEmailForPasswordReset());

        $firstName = explode(' ', trim($notifiable->name))[0];

        return (new MailMessage)
            ->subject('Reset Password | PathSkill')
            ->greeting("Halo, {$firstName}!")
            ->line('Kami menerima permintaan untuk mengatur ulang password akun PathSkill Anda.')
            ->line('Klik tombol di bawah ini untuk membuat password baru.')
            ->action('Buat Password Baru', $url)
            ->line('Tautan reset password ini berlaku selama **60 menit**.')
            ->line('Jika Anda tidak melakukan permintaan ini, Anda dapat mengabaikan email ini dengan aman. Password Anda tidak akan berubah tanpa tindakan apa pun.')
            ->salutation("Salam hangat,\nTim PathSkill");
    }
}