<?php

namespace App\Notifications;

use App\Models\Booking;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class BookingRejected extends Notification
{

    public function __construct(
        private Booking $booking,
        private string $reason = 'Payment proof was rejected by the ground owner.'
    ) {}

    /**
     * Get the notification's delivery channels.
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $url = route('grounds.index');

        return (new MailMessage)
            ->subject('Booking Update - Payment Verification')
            ->greeting('Hello ' . $notifiable->name . ',')
            ->line('Unfortunately, your payment proof for the following booking could not be verified.')
            ->line('Booking Details:')
            ->line('- Booking #: ' . $this->booking->booking_number)
            ->line('- Ground: ' . $this->booking->ground->name)
            ->line('- Date & Time: ' . $this->booking->start_time->format('M d, Y h:i A'))
            ->line('- Amount: BTN ' . number_format($this->booking->total_amount, 2))
            ->line('Reason: ' . $this->reason)
            ->line('The booking has been cancelled and the time slot is now available for others.')
            ->action('Browse Other Grounds', $url)
            ->line('If you believe this is an error, please contact our support team.')
            ->line('Thank you for your understanding.');
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray(object $notifiable): array
    {
        return [
            'booking_id' => $this->booking->id,
            'booking_number' => $this->booking->booking_number,
            'ground_name' => $this->booking->ground->name,
            'reason' => $this->reason,
        ];
    }
}
