<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class RemoveSuspensions extends Command
{
    protected $signature = 'users:remove-suspensions';
    protected $description = 'Remove expired user suspensions';

    public function handle(): int
    {
        $this->info('Checking for expired suspensions...');

        $users = User::where('is_suspended', true)
            ->where('suspended_until', '<=', now())
            ->get();

        foreach ($users as $user) {
            $user->update([
                'is_suspended' => false,
                'suspended_until' => null,
                'late_cancel_count' => 0, // Reset counter
            ]);

            $this->info("Removed suspension for user #{$user->id} - {$user->name}");
        }

        $this->info("Removed {$users->count()} suspensions");

        return Command::SUCCESS;
    }
}
