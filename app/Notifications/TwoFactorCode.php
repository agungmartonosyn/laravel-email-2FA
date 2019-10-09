<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TwoFactorCode extends Notification
{
    use Queueable;

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->line('Kode dua faktor Anda adalah '.$notifiable->two_factor_token)
                    ->action('Verifikasi di Sini', route('verify.index'))
                    ->line('Kode akan kedaluwarsa dalam 10 menit')
                    ->line('Jika Anda belum mencoba masuk, abaikan pesan ini.');
    }
}