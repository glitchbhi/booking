<?php

namespace App\Notifications;

use App\Models\Ground;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class GroundMaintenanceStatusChanged extends Notification
{
    use Queueable;

    public function __construct(
        public Ground $ground,
        public User $owner,
        public bool $isUnderMaintenance
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $status = $this->isUnderMaintenance ? 'Under Maintenance' : 'Available';
        $statusIcon = $this->isUnderMaintenance ? '🔧' : '✅';

        return (new MailMessage)
            ->subject("Ground Maintenance Status Changed - {$this->ground->name}")
            ->greeting('Hello Admin!')
            ->line("A ground's maintenance status has been changed by the owner. {$statusIcon}")
            ->line('')
            ->line('**Ground Details:**')
            ->line('• Ground Name: ' . $this->ground->name)
            ->line('• Sport Type: ' . $this->ground->sportType->name)
            ->line('• Location: ' . $this->ground->location)
            ->line('• Owner: ' . $this->owner->name . ' (' . $this->owner->email . ')')
            ->line('')
            ->line('**Status Update:**')
            ->line('• Previous Status: ' . (!$this->isUnderMaintenance ? 'Under Maintenance' : 'Available'))
            ->line('• New Status: ' . $status)
            ->line('• Changed At: ' . now()->format('M d, Y h:i A'))
            ->line('')
            ->line($this->isUnderMaintenance 
                ? 'The ground has been marked as under maintenance. New bookings will be prevented.' 
                : 'The ground has been marked as available. Bookings can now be made.')
            ->line('')
            ->line('Thank you for your attention!');
    }
}
