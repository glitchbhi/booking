<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class FixApprovedOwners extends Command
{
    protected $signature = 'fix:approved-owners';
    protected $description = 'Fix users who have owner_status=approved but role=user';

    public function handle()
    {
        $this->info('Checking for users with incorrect role...');
        
        $stuckUsers = User::where('owner_status', 'approved')
            ->where('role', '!=', 'owner')
            ->get();
        
        if ($stuckUsers->count() === 0) {
            $this->info('✓ No issues found. All approved owners have correct role.');
            return 0;
        }
        
        $this->warn("Found {$stuckUsers->count()} user(s) with wrong role:");
        
        foreach ($stuckUsers as $user) {
            $this->line("  - {$user->name} ({$user->email})");
            $user->update(['role' => 'owner']);
            $this->info("    ✓ Updated to owner role");
        }
        
        $this->info("\n✓ All approved owners now have correct role!");
        return 0;
    }
}
