<?php

namespace App\Notifications;

use App\Models\OwnerRequest;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewOwnerRequest extends Notification
{
    public function __construct(
        public OwnerRequest $ownerRequest
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('New Owner Request - Thunder Booking')
            ->greeting('Hello Admin!')
            ->line('A new owner request has been submitted.')
            ->line('**Applicant Details:**')
            ->line('Name: ' . $this->ownerRequest->user->name)
            ->line('Email: ' . $this->ownerRequest->user->email)
            ->line('Submitted: ' . $this->ownerRequest->created_at->format('M d, Y h:i A'))
            ->line('**Reason:**')
            ->line($this->ownerRequest->reason ?? 'Not provided')
            ->action('Review Request', route('admin.owner-requests.show', $this->ownerRequest))
            ->line('Please review this request at your earliest convenience.');
    }
}
