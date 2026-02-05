<?php

namespace App\Notifications;

use App\Models\OwnerRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OwnerRequestSubmitted extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public OwnerRequest $ownerRequest
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Owner Request Submitted - ' . config('app.name'))
            ->greeting('Hello ' . $notifiable->name . '!')
            ->line('Thank you for submitting your request to become a ground owner on ' . config('app.name') . '.')
            ->line('**Ground Details:**')
            ->line('• Ground Name: ' . $this->ownerRequest->ground_name)
            ->line('• Category: ' . $this->ownerRequest->category)
            ->line('• Location: ' . $this->ownerRequest->business_address)
            ->line('• Contact: ' . $this->ownerRequest->contact_number)
            ->line('')
            ->line('**Pricing:**')
            ->line('• Day Time: ₹' . number_format($this->ownerRequest->price_day, 2) . ' per hour')
            ->when($this->ownerRequest->available_at_night, function ($message) {
                return $message->line('• Night Time: ₹' . number_format($this->ownerRequest->price_night, 2) . ' per hour');
            })
            ->line('')
            ->line('Your request is currently being reviewed by our admin team.')
            ->line('You will receive an email notification once your request has been processed.')
            ->line('This typically takes 1-2 business days.')
            ->action('View My Profile', url('/profile'))
            ->line('Thank you for choosing ' . config('app.name') . '!');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'title' => 'Owner Request Submitted',
            'message' => 'Your request to become an owner has been submitted and is under review.',
            'owner_request_id' => $this->ownerRequest->id,
            'ground_name' => $this->ownerRequest->ground_name,
            'status' => 'pending',
        ];
    }
}
