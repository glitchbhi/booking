<?php

namespace Tests\Feature;

use App\Models\Ground;
use App\Models\User;
use App\Models\SportsType;
use App\Services\SlotGenerator;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class SlotUpdateTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected SlotGenerator $slotGenerator;
    protected User $owner;
    protected Ground $ground;

    public function setUp(): void
    {
        parent::setUp();
        $this->slotGenerator = app(SlotGenerator::class);
        $this->owner = User::factory()->create(['role' => 'owner']);
        $sportsType = SportsType::factory()->create();
        
        $this->ground = Ground::factory()->create([
            'owner_id' => $this->owner->id,
            'sport_type_id' => $sportsType->id,
            'opening_time' => '06:00',
            'closing_time' => '22:00',
            'slot_duration' => 60,
        ]);
    }

    /**
     * Test that slots are generated on ground creation
     */
    public function test_slots_generated_on_ground_creation()
    {
        $this->assertGreaterThan(0, $this->ground->slots()->count());
    }

    /**
     * Test that slots have correct times based on ground settings
     */
    public function test_slots_have_correct_times()
    {
        $slots = $this->ground->slots()->where('slot_date', now()->toDateString())->get();
        
        $this->assertGreaterThan(0, $slots->count());
        
        // First slot should start at opening time
        $firstSlot = $slots->first();
        $this->assertEquals('06:00', $firstSlot->start_time->format('H:i'));
        
        // No slots should extend past closing time
        $lastSlot = $slots->last();
        $this->assertLessThanOrEqual('22:00', $lastSlot->end_time->format('H:i'));
    }

    /**
     * Test that slots are regenerated when opening time changes
     */
    public function test_slots_regenerated_on_opening_time_change()
    {
        $oldCount = $this->ground->slots()->count();
        $oldFirstSlot = $this->ground->slots()->orderBy('start_time')->first();
        
        // Update opening time
        $this->ground->update(['opening_time' => '08:00']);
        
        // Refresh ground to get updated slots
        $this->ground->refresh();
        
        // Should have slots regenerated
        $this->assertGreaterThan(0, $this->ground->slots()->count());
        
        // New first slot should start at new opening time
        $newFirstSlot = $this->ground->slots()
            ->where('slot_date', now()->toDateString())
            ->orderBy('start_time')
            ->first();
        
        if ($newFirstSlot) {
            $this->assertEquals('08:00', $newFirstSlot->start_time->format('H:i'));
        }
    }

    /**
     * Test that slots are regenerated when closing time changes
     */
    public function test_slots_regenerated_on_closing_time_change()
    {
        $oldLastSlot = $this->ground->slots()
            ->where('slot_date', now()->toDateString())
            ->orderBy('end_time', 'desc')
            ->first();
        
        // Update closing time to earlier time
        $this->ground->update(['closing_time' => '20:00']);
        
        // Refresh
        $this->ground->refresh();
        
        // New last slot should not extend past closing time
        $newLastSlot = $this->ground->slots()
            ->where('slot_date', now()->toDateString())
            ->orderBy('end_time', 'desc')
            ->first();
        
        if ($newLastSlot) {
            $this->assertLessThanOrEqual('20:00', $newLastSlot->end_time->format('H:i'));
        }
    }

    /**
     * Test that slots are regenerated when slot duration changes
     */
    public function test_slots_regenerated_on_duration_change()
    {
        $oldSlots = $this->ground->slots()->count();
        
        // Update slot duration from 60 to 30 minutes
        $this->ground->update(['slot_duration' => 30]);
        
        // Refresh
        $this->ground->refresh();
        
        // With shorter duration, should have more slots
        $newSlots = $this->ground->slots()->count();
        $this->assertGreaterThan($oldSlots, $newSlots);
    }

    /**
     * Test that API returns updated slots
     */
    public function test_api_returns_updated_slots()
    {
        $date = now()->toDateString();
        
        $response = $this->getJson("/api/grounds/{$this->ground->id}/slots?date={$date}");
        
        $response->assertSuccessful();
        $response->assertJsonStructure([
            'success',
            'slots' => [
                '*' => ['id', 'start_time', 'end_time', 'display', 'is_available']
            ],
            'opening_time',
            'closing_time',
        ]);
    }

    /**
     * Test schedule info API endpoint
     */
    public function test_schedule_info_api_returns_correct_data()
    {
        $response = $this->getJson("/api/grounds/{$this->ground->id}/schedule-info");
        
        $response->assertSuccessful();
        $response->assertJsonStructure([
            'success',
            'ground' => [
                'id',
                'name',
                'opening_time',
                'closing_time',
                'slot_duration',
                'rate_per_hour',
                'is_active',
                'is_under_maintenance',
            ],
            'slot_statistics' => [
                'total_slots',
                'available_slots',
                'booked_slots',
                'occupancy_rate',
            ]
        ]);
        
        $response->assertJsonPath('ground.opening_time', '06:00');
        $response->assertJsonPath('ground.closing_time', '22:00');
    }

    /**
     * Test that owner can trigger slot regeneration via API
     */
    public function test_owner_can_regenerate_slots_via_api()
    {
        $response = $this->actingAs($this->owner)
            ->postJson("/api/grounds/{$this->ground->id}/regenerate-slots", [
                'days_ahead' => 90,
            ]);
        
        $response->assertSuccessful();
        $response->assertJsonPath('success', true);
        $response->assertJsonStructure([
            'success',
            'message',
            'slots_created',
            'opening_time',
            'closing_time',
            'slot_duration',
        ]);
    }

    /**
     * Test that invalid times are rejected
     */
    public function test_invalid_times_rejected()
    {
        $this->assertThrows(function () {
            $this->ground->update([
                'opening_time' => '22:00',
                'closing_time' => '06:00', // Before opening time
            ]);
        });
    }

    /**
     * Test that ground model is synchronized after update
     */
    public function test_ground_synchronization_after_update()
    {
        $this->assertTrue($this->ground->isSynchronized());
        
        $this->ground->update(['opening_time' => '08:00']);
        
        $this->ground->refresh();
        $this->assertTrue($this->ground->isSynchronized());
    }

    /**
     * Test that schedule info is accurate
     */
    public function test_schedule_info_is_accurate()
    {
        $info = $this->ground->getScheduleInfo();
        
        $this->assertEquals('06:00', $info['opening_time']->format('H:i'));
        $this->assertEquals('22:00', $info['closing_time']->format('H:i'));
        $this->assertGreaterThan(0, $info['total_slots']);
        $this->assertGreaterThan(0, $info['available_slots']);
        $this->assertEquals(0, $info['booked_slots']); // No bookings yet
    }

    /**
     * Test that no slots extend past closing time
     */
    public function test_no_slots_past_closing_time()
    {
        $slots = $this->ground->slots()->get();
        
        foreach ($slots as $slot) {
            $this->assertLessThanOrEqual(
                $this->ground->closing_time,
                $slot->end_time,
                "Slot {$slot->id} extends past closing time"
            );
        }
    }

    /**
     * Test slot availability queries
     */
    public function test_slot_availability_queries()
    {
        $date = now()->toDateString();
        
        // Get available slots
        $available = $this->ground->getAvailableSlotsForDate($date);
        $this->assertGreaterThan(0, $available->count());
        
        // Check all are marked available
        foreach ($available as $slot) {
            $this->assertTrue($slot->is_available);
        }
        
        // Check has available slots
        $this->assertTrue($this->ground->hasAvailableSlots($date));
    }
}
