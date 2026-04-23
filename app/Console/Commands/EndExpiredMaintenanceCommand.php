<?php

namespace App\Console\Commands;

use App\Models\Ground;
use Illuminate\Console\Command;

class EndExpiredMaintenanceCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'maintenance:end-expired';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Automatically end maintenance for grounds where maintenance period has expired';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $grounds = Ground::where('is_under_maintenance', true)
            ->whereNotNull('maintenance_end_date')
            ->where('maintenance_end_date', '<=', now())
            ->get();

        $count = 0;
        foreach ($grounds as $ground) {
            if ($ground->checkAndEndMaintenance()) {
                $count++;
                $this->info("✓ Ground '{$ground->name}' maintenance ended automatically");
            }
        }

        $this->info("Total grounds updated: {$count}");
    }
}
