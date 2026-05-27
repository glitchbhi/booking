<?php

namespace App\Notifications;

use App\Models\Ground;
use App\Models\User;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OwnerAccountCreated extends Notification
{
    protected $owner;
    protected $email;
    protected $password;
    protected $ground;

    public function __construct(User $owner, string $email, string $password, Ground $ground)
    {
        $this->owner = $owner;
        $this->email = $email;
        $this->password = $password;
        $this->ground = $ground;
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Welcome to ' . config('app.name') . ' - Your Owner Account Created!')
            ->greeting('Welcome, ' . $this->owner->name . '!')
            ->line('Your owner account has been successfully created by our admin team.')
            ->line('Your ground **' . $this->ground->name . '** has been listed and is ready to receive bookings.')
            ->lineBreak()
            ->line('📧 **Login Username (Email):** ' . $this->email)
            ->line('🔐 **Password:** ' . $this->password)
            ->lineBreak()
            ->line('🏟️ **Ground Details:**')
            ->line('• Name: ' . $this->ground->name)
            ->line('• Location: ' . $this->ground->location)
            ->line('• Address: ' . $this->ground->address)
            ->lineBreak()
            ->action('Login to Dashboard', url('/login'))
            ->line('Please keep your login credentials safe and change your password after your first login.')
            ->line('If you have any questions, feel free to contact our support team.')
            ->salutation('Best regards, ' . config('app.name') . ' Team');
    }
}
