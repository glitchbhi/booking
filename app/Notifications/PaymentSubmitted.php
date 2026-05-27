<?php

namespace App\Notifications;

use App\Models\Booking;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PaymentSubmitted extends Notification
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
        $url = route('owner.bookings.index');

        return (new MailMessage)
            ->subject('Payment Proof Submitted - Action Required')
            ->greeting('Hello ' . $notifiable->name . ',')
            ->line('A user has submitted payment proof for a booking on your ground.')
            ->line('Please review the payment proof and approve or reject the booking.')
            ->line('Booking Details:')
            ->line('- Ground: ' . $this->booking->ground->name)
            ->line('- User: ' . $this->booking->user->name)
            ->line('- Date & Time: ' . $this->booking->start_time->format('M d, Y h:i A'))
            ->line('- Duration: ' . $this->booking->duration_hours . ' hours')
            ->line('- Amount: BTN ' . number_format($this->booking->total_amount, 2))
            ->action('Review Booking', $url)
            ->line('Please take action as soon as possible to provide a good user experience.')
            ->line('Thank you for using our platform!');
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
            'user_name' => $this->booking->user->name,
            'amount' => $this->booking->total_amount,
        ];
    }
}
