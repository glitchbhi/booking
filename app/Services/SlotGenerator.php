<?php

namespace App\Services;

use App\Models\Ground;
use App\Models\GroundSlot;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class SlotGenerator
{
    /**
     * Generate slots for a ground on a specific date or date range
     * 
     * @param Ground $ground
     * @param Carbon|string|null $startDate - Start date for slot generation (defaults to today)
     * @param Carbon|string|null $endDate - End date for slot generation (defaults to start date)
     * @return Collection of generated slots
     */
    public function generateSlotsForGround(Ground $ground, $startDate = null, $endDate = null): Collection
    {
        $startDate = $this->parseDate($startDate ?? now());
        $endDate = $this->parseDate($endDate ?? $startDate);

        // Validate date range
        if ($startDate->isAfter($endDate)) {
            throw new \InvalidArgumentException('Start date cannot be after end date');
        }

        $slots = collect();

        // Generate slots for each date in range
        $currentDate = $startDate->clone();
        while ($currentDate->lessThanOrEqualTo($endDate)) {
            $daySlots = $this->generateSlotsForDate($ground, $currentDate);
            $slots = $slots->merge($daySlots);
            $currentDate->addDay();
        }

        return $slots;
    }

    /**
     * Generate slots for a ground on a specific date
     * 
     * @param Ground $ground
     * @param Carbon|string $date
     * @return Collection of generated slots
     */
    public function generateSlotsForDate(Ground $ground, $date): Collection
    {
        $date = $this->parseDate($date);
        $dayOfWeek = strtolower($date->format('l'));

        $slots = collect();

        // Get day availability for this day of week
        $availability = $ground->availabilities()
            ->where('day_of_week', $dayOfWeek)
            ->where('is_active', true)
            ->first();

        if (!$availability) {
            return $slots;
        }

        // Parse opening and closing times
        $openingTime = Carbon::createFromTimeString($ground->opening_time);
        $closingTime = Carbon::createFromTimeString($ground->closing_time);

        // Validate times
        if ($openingTime->greaterThanOrEqualTo($closingTime)) {
            throw new \InvalidArgumentException('Opening time must be before closing time');
        }

        // Validate against availability
        $availStart = Carbon::createFromTimeString($availability->start_time->format('H:i:s'));
        $availEnd = Carbon::createFromTimeString($availability->end_time->format('H:i:s'));

        if ($openingTime->isBefore($availStart) || $closingTime->isAfter($availEnd)) {
            // Use the most restrictive times
            $openingTime = $openingTime->greaterThan($availStart) ? $openingTime : $availStart;
            $closingTime = $closingTime->lessThan($availEnd) ? $closingTime : $availEnd;
        }

        // Generate slots
        $slotDuration = $ground->slot_duration ?? 60; // Default 60 minutes
        $currentTime = $openingTime->clone();

        while ($currentTime->addMinutes($slotDuration)->lessThanOrEqualTo($closingTime)) {
            $slotStart = $currentTime->clone()->subMinutes($slotDuration);
            $slotEnd = $currentTime->clone();

            $slots->push([
                'ground_id' => $ground->id,
                'slot_date' => $date->toDateString(),
                'start_time' => $slotStart->format('H:i:s'),
                'end_time' => $slotEnd->format('H:i:s'),
                'is_available' => true,
            ]);
        }

        return $slots;
    }

    /**
     * Generate and store slots in the database for a ground
     * Automatically deletes existing slots and creates fresh ones
     * 
     * @param Ground $ground
     * @param Carbon|string|null $startDate
     * @param Carbon|string|null $endDate
     * @param bool $deleteExisting - Whether to delete existing slots first
     * @return int Number of slots created
     */
    public function createSlotsForGround(
        Ground $ground,
        $startDate = null,
        $endDate = null,
        bool $deleteExisting = true
    ): int {
        $startDate = $this->parseDate($startDate ?? now());
        $endDate = $this->parseDate($endDate ?? $startDate->clone()->addDays(30)); // Default 30 days ahead

        // Validate times before proceeding
        if (!$ground->opening_time || !$ground->closing_time) {
            \Log::warning("Cannot generate slots: Ground {$ground->id} missing opening/closing times");
            return 0;
        }

        try {
            $this->validateTimes($ground->opening_time, $ground->closing_time);
        } catch (\InvalidArgumentException $e) {
            \Log::error("Cannot generate slots for ground {$ground->id}: {$e->getMessage()}");
            return 0;
        }

        // Delete existing slots for the date range if requested
        if ($deleteExisting) {
            $deletedCount = GroundSlot::where('ground_id', $ground->id)
                ->whereBetween('slot_date', [$startDate->toDateString(), $endDate->toDateString()])
                ->delete();
            
            \Log::debug("Deleted {$deletedCount} old slots for ground {$ground->id}");
        }

        // Generate and create slots
        $slots = $this->generateSlotsForGround($ground, $startDate, $endDate);
        $createdCount = 0;
        $failedCount = 0;

        foreach ($slots as $slot) {
            try {
                GroundSlot::create($slot);
                $createdCount++;
            } catch (\Exception $e) {
                // Skip duplicate slots (unique constraint) or other errors
                $failedCount++;
            }
        }

        if ($failedCount > 0) {
            \Log::debug("Skipped {$failedCount} duplicate or invalid slots for ground {$ground->id}");
        }

        return $createdCount;
    }

    /**
     * Regenerate slots for a ground (typically called when times change)
     * Completely clears old slots and generates fresh ones
     * 
     * @param Ground $ground
     * @param int $daysAhead - Number of days ahead to regenerate (default: 90)
     * @return int Number of slots created
     * @throws \InvalidArgumentException if ground times are invalid
     */
    public function regenerateSlotsForGround(Ground $ground, int $daysAhead = 90): int
    {
        // Validate that opening/closing times exist and are valid
        if (!$ground->opening_time || !$ground->closing_time) {
            throw new \InvalidArgumentException(
                "Ground {$ground->id} must have opening_time and closing_time set"
            );
        }

        try {
            $this->validateTimes($ground->opening_time, $ground->closing_time);
        } catch (\InvalidArgumentException $e) {
            throw new \InvalidArgumentException(
                "Ground {$ground->id} has invalid times: {$e->getMessage()}"
            );
        }

        $startDate = now();
        $endDate = now()->addDays($daysAhead);

        // Delete ALL existing slots for this ground in the date range
        $deletedCount = GroundSlot::where('ground_id', $ground->id)
            ->whereBetween('slot_date', [$startDate->toDateString(), $endDate->toDateString()])
            ->forceDelete(); // Use forceDelete to ensure removal

        \Log::info("Regenerating slots for ground {$ground->id}: Deleted {$deletedCount} old slots", [
            'opening_time' => $ground->opening_time,
            'closing_time' => $ground->closing_time,
            'slot_duration' => $ground->slot_duration,
            'date_range' => "{$startDate->toDateString()} to {$endDate->toDateString()}",
        ]);

        // Create fresh slots
        $createdCount = $this->createSlotsForGround($ground, $startDate, $endDate, false);

        \Log::info("Regenerated slots for ground {$ground->id}: Created {$createdCount} new slots");

        return $createdCount;
    }

    /**
     * Get available slots for a ground on a specific date
     * 
     * @param Ground $ground
     * @param Carbon|string $date
     * @return Collection
     */
    public function getAvailableSlotsForDate(Ground $ground, $date): Collection
    {
        $date = $this->parseDate($date);

        return GroundSlot::where('ground_id', $ground->id)
            ->where('slot_date', $date->toDateString())
            ->where('is_available', true)
            ->orderBy('start_time')
            ->get();
    }

    /**
     * Mark a slot as unavailable (booked)
     * 
     * @param GroundSlot $slot
     * @return bool
     */
    public function markSlotAsBooked(GroundSlot $slot): bool
    {
        return $slot->update(['is_available' => false]);
    }

    /**
     * Mark a slot as available (cancellation)
     * 
     * @param GroundSlot $slot
     * @return bool
     */
    public function markSlotAsAvailable(GroundSlot $slot): bool
    {
        return $slot->update(['is_available' => true]);
    }

    /**
     * Helper method to parse date
     * 
     * @param Carbon|string $date
     * @return Carbon
     */
    private function parseDate($date): Carbon
    {
        if ($date instanceof Carbon) {
            return $date->clone()->startOfDay();
        }

        return Carbon::parse($date)->startOfDay();
    }

    /**
     * Get opening and closing times for a ground
     * 
     * @param Ground $ground
     * @return array
     */
    public function getGroundTimings(Ground $ground): array
    {
        return [
            'opening_time' => $ground->opening_time,
            'closing_time' => $ground->closing_time,
            'slot_duration' => $ground->slot_duration,
        ];
    }

    /**
     * Validate slot generation times
     * 
     * @param string|Carbon $openingTime
     * @param string|Carbon $closingTime
     * @return bool
     * @throws \InvalidArgumentException
     */
    public function validateTimes($openingTime, $closingTime): bool
    {
        $opening = Carbon::createFromTimeString(
            $openingTime instanceof Carbon ? $openingTime->format('H:i:s') : $openingTime
        );
        $closing = Carbon::createFromTimeString(
            $closingTime instanceof Carbon ? $closingTime->format('H:i:s') : $closingTime
        );

        if ($opening->greaterThanOrEqualTo($closing)) {
            throw new \InvalidArgumentException('Opening time must be before closing time');
        }

        return true;
    }
}
