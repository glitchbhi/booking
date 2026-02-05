<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class WalletCredited extends Notification
{
    use Queueable;

    public function __construct(
        public float $amount,
        public string $description,
        public float $newBalance
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Wallet Credited - Thunder Booking')
            ->greeting('Hello ' . $notifiable->name . '!')
            ->line('Your wallet has been credited.')
            ->line('**Transaction Details:**')
            ->line('Amount: BTN ' . number_format($this->amount, 2))
            ->line('Description: ' . $this->description)
            ->line('New Balance: BTN ' . number_format($this->newBalance, 2))
            ->action('View Wallet', route('wallet.index'))
            ->line('Thank you for using Thunder Booking System!');
    }
}
