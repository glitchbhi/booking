<?php

namespace App\Notifications;

use App\Models\Booking;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class BookingCancelledForOwner extends Notification
{
    use Queueable;

    public function __construct(
        public Booking $booking,
        public ?string $reason = null
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $message = (new MailMessage)
            ->subject('Booking Cancelled - ' . $this->booking->ground->name)
            ->greeting('Hello ' . $notifiable->name . ',')
            ->line('A booking for your ground has been cancelled.')
            ->line('**Booking Details:**')
            ->line('Booking Number: ' . $this->booking->booking_number)
            ->line('Ground: ' . $this->booking->ground->name)
            ->line('Customer: ' . $this->booking->user->name)
            ->line('Original Date: ' . $this->booking->start_time->format('M d, Y h:i A'))
            ->line('Amount: BTN ' . number_format($this->booking->total_amount, 2));

        if ($this->reason) {
            $message->line('**Cancellation Reason:** ' . $this->reason);
        }

        return $message
            ->action('View Dashboard', route('owner.dashboard'))
            ->line('The time slot is now available for new bookings.');
    }
}
