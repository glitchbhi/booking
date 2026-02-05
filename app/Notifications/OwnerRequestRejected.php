<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OwnerRequestRejected extends Notification
{
    use Queueable;

    public function __construct(
        public string $reason = ''
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $message = (new MailMessage)
            ->subject('Owner Request Update - Thunder Booking System')
            ->greeting('Hello ' . $notifiable->name)
            ->line('We regret to inform you that your request to become a ground owner has not been approved at this time.');

        if ($this->reason) {
            $message->line('Reason: ' . $this->reason);
        }

        return $message->line('You can submit a new request in the future if your circumstances change.')
                       ->line('Thank you for your interest in Thunder Booking System.');
    }
}
