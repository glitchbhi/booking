<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class TestEmail extends Command
{
    protected $signature = 'mail:test {email?}';
    protected $description = 'Send a test email to verify SMTP configuration';

    public function handle()
    {
        $email = $this->argument('email') ?? 'thunderbooking975@gmail.com';
        
        try {
            Mail::raw('This is a test email from Thunder Booking system. SMTP is configured successfully!', function ($message) use ($email) {
                $message->to($email)
                    ->subject('Thunder Booking - SMTP Test');
            });
            
            $this->info("✓ Test email sent successfully to: {$email}");
            $this->info('Please check your inbox (and spam folder).');
            return 0;
        } catch (\Exception $e) {
            $this->error('✗ Failed to send email: ' . $e->getMessage());
            return 1;
        }
    }
}
