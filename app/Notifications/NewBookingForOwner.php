<?php

namespace App\Notifications;

use App\Models\Booking;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewBookingForOwner extends Notification
{
    use Queueable;

    public function __construct(
        public Booking $booking
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('New Booking Received - ' . $this->booking->ground->name)
            ->greeting('Hello ' . $notifiable->name . '!')
            ->line('Great news! You have received a new booking.')
            ->line('**Booking Details:**')
            ->line('Booking Number: ' . $this->booking->booking_number)
            ->line('Ground: ' . $this->booking->ground->name)
            ->line('Customer: ' . $this->booking->user->name)
            ->line('Date: ' . $this->booking->start_time->format('M d, Y'))
            ->line('Time: ' . $this->booking->start_time->format('h:i A') . ' - ' . $this->booking->end_time->format('h:i A'))
            ->line('Duration: ' . $this->booking->duration_hours . ' hours')
            ->line('Amount: BTN ' . number_format($this->booking->total_amount, 2))
            ->line('Your Earnings: BTN ' . number_format($this->booking->total_amount - $this->booking->admin_commission, 2) . ' (after 2% platform fee)')
            ->action('View in Dashboard', route('owner.bookings.index'))
            ->line('Thank you for using Thunder Booking System!');
    }
}
