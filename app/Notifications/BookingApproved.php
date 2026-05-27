<?php

namespace App\Notifications;

use App\Models\Booking;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class BookingApproved extends Notification
{

    public function __construct(
        private Booking $booking
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
        $url = route('bookings.show', $this->booking);

        return (new MailMessage)
            ->subject('Booking Confirmed! 🎉')
            ->greeting('Great news, ' . $notifiable->name . '!')
            ->line('Your booking has been approved and confirmed by the ground owner.')
            ->line('Booking Details:')
            ->line('- Booking #: ' . $this->booking->booking_number)
            ->line('- Ground: ' . $this->booking->ground->name)
            ->line('- Location: ' . $this->booking->ground->location)
            ->line('- Date & Time: ' . $this->booking->start_time->format('M d, Y h:i A'))
            ->line('- Duration: ' . $this->booking->duration_hours . ' hours')
            ->line('- Total Amount: BTN ' . number_format($this->booking->total_amount, 2))
            ->action('View Booking Details', $url)
            ->line('Please arrive on time and enjoy your booking!')
            ->line('Thank you for choosing our platform!');
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
            'start_time' => $this->booking->start_time,
            'amount' => $this->booking->total_amount,
        ];
    }
}
