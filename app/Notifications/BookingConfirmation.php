<?php

namespace App\Notifications;

use App\Models\Booking;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class BookingConfirmation extends Notification
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
            ->subject('Booking Confirmation - ' . $this->booking->booking_number)
            ->greeting('Hello ' . $notifiable->name . '!')
            ->line('Your booking has been confirmed.')
            ->line('Booking Number: ' . $this->booking->booking_number)
            ->line('Ground: ' . $this->booking->ground->name)
            ->line('Start Time: ' . $this->booking->start_time->format('M d, Y h:i A'))
            ->line('End Time: ' . $this->booking->end_time->format('M d, Y h:i A'))
            ->line('Duration: ' . $this->booking->duration_hours . ' hours')
            ->line('Total Amount: BTN ' . number_format($this->booking->total_amount, 2))
            ->action('View Booking', route('bookings.show', $this->booking))
            ->line('Thank you for using Thunder Booking System!');
    }
}
