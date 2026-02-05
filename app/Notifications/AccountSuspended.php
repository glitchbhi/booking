<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AccountSuspended extends Notification
{
    use Queueable;

    public function __construct(
        public \DateTime $suspendedUntil
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Account Suspension Notice - Thunder Booking System')
            ->greeting('Hello ' . $notifiable->name)
            ->line('Your account has been temporarily suspended from making new bookings.')
            ->line('Suspension Period: Until ' . $this->suspendedUntil->format('M d, Y h:i A'))
            ->line('Reason: Multiple late cancellations or no-shows')
            ->line('During this period, you will not be able to create new bookings.')
            ->line('Your account will be automatically reactivated after the suspension period.')
            ->line('Please ensure you cancel bookings at least 4 hours in advance to avoid future suspensions.');
    }
}
