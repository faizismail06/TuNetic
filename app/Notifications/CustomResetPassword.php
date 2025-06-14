<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class CustomResetPassword extends Notification
{
    use Queueable;

    /**
     * The password reset URL.
     *
     * @var string
     */
    public $url;

    /**
     * The password reset token expiration count (in minutes).
     *
     * @var int
     */
    public $count;


    /**
     * Create a new notification instance.
     *
     * @param string $url The password reset URL.
     */
    public function __construct(string $url)
    {
        $this->url = $url;
        $this->count = config('auth.passwords.' . config('auth.defaults.passwords') . '.expire');
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
        // Menggunakan template kustom Anda (misalnya: emails.reset-custom)
        // Pastikan Anda meneruskan 'actionUrl' dan 'count' seperti yang
        // diharapkan oleh template Anda.
        return (new MailMessage)
            ->subject('Notifikasi Reset Password Anda')
            ->view('vendor.notifications.email-reset', [ // GANTI DENGAN NAMA VIEW ANDA
                'actionUrl' => $this->url,
                'count' => $this->count,
                'notifiable' => $notifiable,
            ]);
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