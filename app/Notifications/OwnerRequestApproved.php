<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OwnerRequestApproved extends Notification
{
    use Queueable;

    public function __construct(
        public string $notes = ''
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $message = (new MailMessage)
            ->subject('Owner Request Approved - Thunder Booking System')
            ->greeting('Congratulations ' . $notifiable->name . '!')
            ->line('Your request to become a ground owner has been approved!')
            ->line('Your ground has been automatically created and is now active!')
            ->line('Users can now view and book your ground on the Thunder Booking platform.');

        if ($this->notes) {
            $message->line('Admin Notes: ' . $this->notes);
        }

        return $message->action('View Your Grounds', route('owner.grounds.index'))
                       ->line('You can view and manage your ground from the owner dashboard.')
                       ->line('Thank you for joining Thunder Booking System!');
    }
}
