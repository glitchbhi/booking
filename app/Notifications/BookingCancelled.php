<?php

namespace App\Notifications;

use App\Models\Booking;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class BookingCancelled extends Notification
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
        $message = (new MailMessage)
            ->subject('Booking Cancelled - ' . $this->booking->booking_number)
            ->greeting('Hello ' . $notifiable->name . '!')
            ->line('Your booking has been cancelled.')
            ->line('Booking Number: ' . $this->booking->booking_number)
            ->line('Ground: ' . $this->booking->ground->name)
            ->line('Amount: BTN ' . number_format($this->booking->total_amount, 2))
            ->line('For refunds, please contact the ground owner directly.');

        return $message->action('View Booking', route('bookings.show', $this->booking))
                       ->line('Thank you for using Thunder Booking System!');
    }
}
