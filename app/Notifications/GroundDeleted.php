<?php

namespace App\Notifications;

use App\Models\Ground;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class GroundDeleted extends Notification
{
    use Queueable;

    public function __construct(
        public Ground $ground,
        public User $owner
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Ground Deleted - ' . $this->ground->name)
            ->greeting('Hello Admin!')
            ->line('A ground has been deleted by the owner.')
            ->line('')
            ->line('**Ground Details:**')
            ->line('• Ground Name: ' . $this->ground->name)
            ->line('• Sport Type: ' . $this->ground->sportType->name)
            ->line('• Location: ' . $this->ground->location)
            ->line('• Owner: ' . $this->owner->name . ' (' . $this->owner->email . ')')
            ->line('• Total Bookings: ' . $this->ground->total_bookings)
            ->line('• Deleted At: ' . now()->format('M d, Y h:i A'))
            ->line('')
            ->line('The ground has been permanently removed from the system.')
            ->line('Thank you for your attention!');
    }
}
