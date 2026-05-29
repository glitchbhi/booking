<?php

namespace App\Http\Controllers\Api;

use App\Models\Ground;
use App\Models\GroundSlot;
use App\Services\SlotGenerator;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class GroundSlotsController extends Controller
{
    protected SlotGenerator $slotGenerator;

    public function __construct(SlotGenerator $slotGenerator)
    {
        $this->slotGenerator = $slotGenerator;
    }

    /**
     * Get available slots for a ground on a specific date
     */
    public function getSlots(Request $request, Ground $ground)
    {
        $request->validate([
            'date' => 'required|date|after_or_equal:' . now()->toDateString(),
        ]);

        $date = $request->input('date');
        
        // Fetch slots from database
        $slots = $ground->getAvailableSlotsForDate($date);

        if ($slots->isEmpty()) {
            return response()->json([
                'success' => true,
                'slots' => [],
                'message' => 'No slots available for this date'
            ]);
        }

        return response()->json([
            'success' => true,
            'slots' => $slots->map(function ($slot) {
                return [
                    'id' => $slot->id,
                    'start_time' => $slot->start_time->format('H:i'),
                    'end_time' => $slot->end_time->format('H:i'),
                    'display' => $slot->start_time->format('H:i') . ' - ' . $slot->end_time->format('H:i'),
                    'is_available' => (bool) $slot->is_available,
                ];
            })->toArray(),
            'opening_time' => $ground->opening_time ? Carbon::parse($ground->opening_time)->format('H:i') : null,
            'closing_time' => $ground->closing_time ? Carbon::parse($ground->closing_time)->format('H:i') : null,
        ]);
    }

    /**
     * Get slots for a date range
     */
    public function getSlotsDateRange(Request $request, Ground $ground)
    {
        $request->validate([
            'start_date' => 'required|date|after_or_equal:' . now()->toDateString(),
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        
        // Fetch slots from database
        $slots = $ground->getAvailableSlotsForDateRange($startDate, $endDate);

        if ($slots->isEmpty()) {
            return response()->json([
                'success' => true,
                'slots' => [],
                'message' => 'No slots available in the selected date range'
            ]);
        }

        // Group slots by date
        $slotsByDate = $slots->groupBy(function ($slot) {
            return $slot->slot_date;
        })->map(function ($daySlots) {
            return $daySlots->map(function ($slot) {
                return [
                    'id' => $slot->id,
                    'start_time' => $slot->start_time->format('H:i'),
                    'end_time' => $slot->end_time->format('H:i'),
                    'display' => $slot->start_time->format('H:i') . ' - ' . $slot->end_time->format('H:i'),
                    'is_available' => (bool) $slot->is_available,
                ];
            })->toArray();
        })->toArray();

        return response()->json([
            'success' => true,
            'slots_by_date' => $slotsByDate,
            'total_slots' => count($slots),
        ]);
    }

    /**
     * Regenerate slots for a ground (manual trigger)
     * Requires owner/admin authorization
     */
    public function regenerateSlots(Request $request, Ground $ground)
    {
        // Only owner or admin can regenerate slots
        $this->authorize('update', $ground);

        $request->validate([
            'days_ahead' => 'nullable|integer|min:7|max:365',
        ]);

        try {
            $daysAhead = $request->input('days_ahead', 90);
            
            $createdCount = $this->slotGenerator->regenerateSlotsForGround($ground, $daysAhead);

            return response()->json([
                'success' => true,
                'message' => "Successfully regenerated {$createdCount} booking slots",
                'slots_created' => $createdCount,
                'opening_time' => $ground->opening_time ? Carbon::parse($ground->opening_time)->format('H:i') : null,
                'closing_time' => $ground->closing_time ? Carbon::parse($ground->closing_time)->format('H:i') : null,
                'slot_duration' => $ground->slot_duration,
            ]);
        } catch (\InvalidArgumentException $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
            ], 400);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Failed to regenerate slots: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get ground's current schedule information
     */
    public function getScheduleInfo(Ground $ground)
    {
        $totalSlots = $ground->slots()->count();
        $availableSlots = $ground->slots()->where('is_available', true)->count();
        $bookedSlots = $totalSlots - $availableSlots;

        return response()->json([
            'success' => true,
            'ground' => [
                'id' => $ground->id,
                'name' => $ground->name,
                'opening_time' => $ground->opening_time ? Carbon::parse($ground->opening_time)->format('H:i') : null,
                'closing_time' => $ground->closing_time ? Carbon::parse($ground->closing_time)->format('H:i') : null,
                'slot_duration' => $ground->slot_duration ?? 60,
                'rate_per_hour' => $ground->rate_per_hour,
                'night_rate_per_hour' => $ground->night_rate_per_hour,
                'day_rate_start' => $ground->day_rate_start ? Carbon::parse($ground->day_rate_start)->format('H:i') : null,
                'day_rate_end' => $ground->day_rate_end ? Carbon::parse($ground->day_rate_end)->format('H:i') : null,
                'night_rate_start' => $ground->night_rate_start ? Carbon::parse($ground->night_rate_start)->format('H:i') : null,
                'night_rate_end' => $ground->night_rate_end ? Carbon::parse($ground->night_rate_end)->format('H:i') : null,
                'is_active' => $ground->is_active,
                'is_under_maintenance' => $ground->is_under_maintenance,
            ],
            'slot_statistics' => [
                'total_slots' => $totalSlots,
                'available_slots' => $availableSlots,
                'booked_slots' => $bookedSlots,
                'occupancy_rate' => $totalSlots > 0 ? round(($bookedSlots / $totalSlots) * 100, 2) : 0,
            ],
        ]);
    }
}
