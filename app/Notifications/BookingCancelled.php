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
            ->line('Original Amount: BTN ' . number_format($this->booking->total_amount, 2));

        if ($this->booking->is_refunded) {
            $message->line('Refund Amount: BTN ' . number_format($this->booking->refund_amount, 2))
                    ->line('The refund has been credited to your wallet.');
        } else {
            $message->line('Refund: No refund (late cancellation)')
                    ->line('Please note: Cancellations less than 4 hours before start time are not eligible for refunds.');
        }

        return $message->action('View Booking', route('bookings.show', $this->booking))
                       ->line('Thank you for using Thunder Booking System!');
    }
}
