<?php

namespace App\Observers;

use App\Models\Ground;
use App\Services\SlotGenerator;

class GroundObserver
{
    protected SlotGenerator $slotGenerator;

    public function __construct(SlotGenerator $slotGenerator)
    {
        $this->slotGenerator = $slotGenerator;
    }

    /**
     * Handle the Ground "created" event.
     * Generate initial slots when a ground is created
     */
    public function created(Ground $ground): void
    {
        try {
            // Generate slots for the next 90 days
            $this->slotGenerator->regenerateSlotsForGround($ground, 90);
        } catch (\Exception $e) {
            // Log error but don't break the creation process
            \Log::error("Failed to generate slots for ground {$ground->id}: {$e->getMessage()}");
        }
    }

    /**
     * Handle the Ground "updated" event.
     * Regenerate slots if ANY schedule-related fields changed
     */
    public function updated(Ground $ground): void
    {
        // Check if any slot-related or schedule-related fields have changed
        $fieldsToCheck = [
            'opening_time',
            'closing_time',
            'slot_duration',
            'day_rate_start',
            'day_rate_end',
            'night_rate_start',
            'night_rate_end',
            'rate_per_hour',
            'night_rate_per_hour',
            'is_active',
            'is_under_maintenance',
        ];

        $changedFields = $ground->getChanges();
        $hasRelevantChanges = false;

        // Check if any of the important fields changed
        foreach ($fieldsToCheck as $field) {
            if (isset($changedFields[$field])) {
                $hasRelevantChanges = true;
                break;
            }
        }

        // Also check wasChanged for post-save modifications
        if (!$hasRelevantChanges) {
            $hasRelevantChanges = $ground->wasChanged($fieldsToCheck);
        }

        if ($hasRelevantChanges) {
            try {
                // Force regenerate slots for the next 90 days
                // This will delete old slots and create new ones
                $slotsCreated = $this->slotGenerator->regenerateSlotsForGround($ground, 90);
                
                \Log::info("Slots regenerated for ground {$ground->id}: {$slotsCreated} slots created");
            } catch (\Exception $e) {
                // Log error but don't break the update process
                \Log::error("Failed to regenerate slots for ground {$ground->id}: {$e->getMessage()}", [
                    'exception' => $e,
                    'changed_fields' => $changedFields,
                ]);
            }
        }
    }

    /**
     * Handle the Ground "deleting" event.
     * Clean up slots when a ground is deleted (soft delete)
     */
    public function deleting(Ground $ground): void
    {
        // Delete associated slots
        $ground->slots()->delete();
    }

    /**
     * Handle the Ground "restored" event.
     * Regenerate slots when a ground is restored from soft delete
     */
    public function restored(Ground $ground): void
    {
        try {
            // Regenerate slots for the next 90 days
            $this->slotGenerator->regenerateSlotsForGround($ground, 90);
        } catch (\Exception $e) {
            // Log error but don't break the restore process
            \Log::error("Failed to regenerate slots for restored ground {$ground->id}: {$e->getMessage()}");
        }
    }
}
