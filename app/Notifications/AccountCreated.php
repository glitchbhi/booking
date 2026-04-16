<?php

namespace App\Notifications;

use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AccountCreated extends Notification
{

    public function __construct(
        public string $loginMethod
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $message = (new MailMessage)
            ->subject('Welcome to Thunder Booking System!')
            ->greeting('Hello ' . $notifiable->name . '!')
            ->line('Welcome to Thunder Booking System! Your account has been successfully created.')
            ->line('You can now start booking grounds and managing your reservations.');

        if ($this->loginMethod === 'google') {
            $message->line('Your account was created using Google OAuth.')
                ->line('You can login using:')
                ->line('• The "Continue with Google" button')
                ->line('• Email and password (you can set a password using "Forgot Password" on the login page)');
        } else {
            $message->line('Your account was created using email and password.')
                ->line('You can login anytime using your email and password.');
        }

        $message->action('Go to Login', route('login'))
            ->line('Thank you for joining Thunder Booking System!');

        return $message;
    }
}
