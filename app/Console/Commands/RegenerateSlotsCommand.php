<?php

namespace App\Console\Commands;

use App\Models\Ground;
use App\Services\SlotGenerator;
use Illuminate\Console\Command;

class RegenerateSlotsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'slots:regenerate {--ground-id= : Regenerate slots for a specific ground} {--all : Regenerate slots for all grounds} {--days=90 : Number of days ahead to regenerate}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Regenerate booking slots for ground(s)';

    protected SlotGenerator $slotGenerator;

    public function __construct(SlotGenerator $slotGenerator)
    {
        parent::__construct();
        $this->slotGenerator = $slotGenerator;
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $daysAhead = $this->option('days', 90);

        if ($this->option('all')) {
            $this->regenerateAllGrounds($daysAhead);
        } elseif ($groundId = $this->option('ground-id')) {
            $this->regenerateSingleGround($groundId, $daysAhead);
        } else {
            $this->error('Please specify --ground-id or --all option');
            return 1;
        }

        return 0;
    }

    protected function regenerateSingleGround($groundId, $daysAhead)
    {
        $ground = Ground::find($groundId);

        if (!$ground) {
            $this->error("Ground with ID {$groundId} not found");
            return;
        }

        $this->info("Regenerating slots for ground: {$ground->name}");
        $this->info("Opening: {$ground->opening_time}, Closing: {$ground->closing_time}");

        try {
            $created = $this->slotGenerator->regenerateSlotsForGround($ground, $daysAhead);
            $this->info("✓ Successfully created {$created} slots");
        } catch (\Exception $e) {
            $this->error("✗ Failed: {$e->getMessage()}");
        }
    }

    protected function regenerateAllGrounds($daysAhead)
    {
        $grounds = Ground::all();
        $this->info("Regenerating slots for {$grounds->count()} ground(s)...");

        $successCount = 0;
        $failureCount = 0;

        foreach ($grounds as $ground) {
            $this->line("Processing: {$ground->name}...");

            try {
                $created = $this->slotGenerator->regenerateSlotsForGround($ground, $daysAhead);
                $this->info("  ✓ Created {$created} slots");
                $successCount++;
            } catch (\Exception $e) {
                $this->error("  ✗ Failed: {$e->getMessage()}");
                $failureCount++;
            }
        }

        $this->info("\n=== Summary ===");
        $this->info("Success: {$successCount}");
        $this->error("Failed: {$failureCount}");
    }
}
