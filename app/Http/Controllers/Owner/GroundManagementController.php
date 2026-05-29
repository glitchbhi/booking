<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Ground;
use App\Models\SportsType;
use App\Models\User;
use App\Notifications\GroundDeleted;
use App\Notifications\GroundMaintenanceStatusChanged;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class GroundManagementController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'owner']);
    }

    public function index()
    {
        $grounds = Auth::user()->grounds()->with('sportType')->latest()->get();
        return view('owner.grounds.index', compact('grounds'));
    }

    public function create()
    {
        $sportsTypes = SportsType::active()->get();
        return view('owner.grounds.create-multi-step', compact('sportsTypes'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'sport_type_id' => 'required|exists:sports_types,id',
            'description' => 'nullable|string',
            'location' => 'required|string|max:255',
            'address' => 'nullable|string|max:500',
            'phone' => 'required|string|max:20',
            'bank_name' => 'nullable|string|max:255',
            'account_number' => 'nullable|string|max:50',
            'rate_per_hour' => 'required|numeric|min:0',
            'night_rate_per_hour' => 'nullable|numeric|min:0',
            'capacity' => 'required|string|max:50',
            'capacity_description' => 'nullable|string|max:255',
            'day_rate_start' => 'nullable|date_format:H:i',
            'day_rate_end' => 'nullable|date_format:H:i',
            'night_rate_start' => 'nullable|date_format:H:i',
            'night_rate_end' => 'nullable|date_format:H:i',
            'opening_time' => 'required|date_format:H:i',
            'closing_time' => 'required|date_format:H:i|after:opening_time',
            'slot_duration' => 'nullable|integer|min:15|max:480',
            'images' => 'nullable|array|max:4',
            'images.*' => 'nullable|image|mimes:jpeg,jpg,png,gif,webp|max:2048',
            'is_active' => 'boolean',
        ]);

        try {
            $validated['owner_id'] = Auth::id();
            $validated['is_active'] = false; // Manually added grounds require admin approval
            $validated['slot_duration'] = $validated['slot_duration'] ?? 60; // Default 60 minutes
            
            // Handle image uploads (max 4)
            if ($request->hasFile('images')) {
                $images = [];
                $uploadedImages = array_slice($request->file('images'), 0, 4);
                foreach ($uploadedImages as $image) {
                    $path = $image->store('grounds', 'public');
                    $images[] = $path;
                }
                $validated['images'] = $images;
            }

            $ground = Ground::create($validated);

            return redirect()
                ->route('owner.grounds.show', $ground)
                ->with('success', 'Ground submitted successfully! It will be active once admin approves it.');

        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', 'Failed to create ground: ' . $e->getMessage());
        }
    }

    public function show(Ground $ground)
    {
        $this->authorize('view', $ground);
        
        $ground->load(['sportType', 'availabilities', 'bookings' => function ($q) {
            $q->latest()->limit(10);
        }]);

        return view('owner.grounds.show', compact('ground'));
    }

    public function edit(Ground $ground)
    {
        $this->authorize('update', $ground);
        
        $sportsTypes = SportsType::active()->get();
        return view('owner.grounds.edit', compact('ground', 'sportsTypes'));
    }

    public function update(Request $request, Ground $ground)
    {
        $this->authorize('update', $ground);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'sport_type_id' => 'required|exists:sports_types,id',
            'description' => 'nullable|string',
            'location' => 'required|string|max:255',
            'address' => 'nullable|string|max:500',
            'phone' => 'required|string|max:20',
            'bank_name' => 'nullable|string|max:255',
            'account_number' => 'nullable|string|max:50',
            'rate_per_hour' => 'required|numeric|min:0',
            'night_rate_per_hour' => 'nullable|numeric|min:0',
            'capacity' => 'required|string|max:50',
            'capacity_description' => 'nullable|string|max:255',
            'day_rate_start' => 'nullable|date_format:H:i',
            'day_rate_end' => 'nullable|date_format:H:i',
            'night_rate_start' => 'nullable|date_format:H:i',
            'night_rate_end' => 'nullable|date_format:H:i',
            'opening_time' => 'required|date_format:H:i',
            'closing_time' => 'required|date_format:H:i|after:opening_time',
            'slot_duration' => 'nullable|integer|min:15|max:480',
            'is_active' => 'boolean',
            'images' => 'nullable|array',
            'images.*' => 'nullable|image|mimes:jpeg,jpg,png,gif,webp|max:2048',
            'remove_images' => 'nullable|array',
        ]);

        try {
            // Handle image removal
            $currentImages = $ground->images ?? [];
            if ($request->has('remove_images')) {
                foreach ($request->remove_images as $index) {
                    if (isset($currentImages[$index])) {
                        Storage::disk('public')->delete($currentImages[$index]);
                        unset($currentImages[$index]);
                    }
                }
                $currentImages = array_values($currentImages); // Re-index
            }

            // Handle new image uploads
            if ($request->hasFile('images')) {
                $remainingSlots = 4 - count($currentImages);
                $newImages = array_slice($request->file('images'), 0, $remainingSlots);
                
                foreach ($newImages as $image) {
                    if (count($currentImages) < 4) {
                        $path = $image->store('grounds', 'public');
                        $currentImages[] = $path;
                    }
                }
            }

            $validated['images'] = $currentImages;
            unset($validated['remove_images']);
            
            // Track what's changing to provide appropriate feedback
            $scheduleChanged = $ground->opening_time != $validated['opening_time'] ||
                             $ground->closing_time != $validated['closing_time'] ||
                             $ground->slot_duration != ($validated['slot_duration'] ?? 60);
            
            $ground->update($validated);

            // Provide feedback about slot regeneration
            $message = 'Ground updated successfully!';
            if ($scheduleChanged) {
                $message .= ' Booking slots have been automatically regenerated based on the new schedule.';
            }

            return redirect()
                ->route('owner.grounds.show', $ground)
                ->with('success', $message);

        } catch (\Exception $e) {
            return back()->with('error', 'Failed to update ground: ' . $e->getMessage());
        }
    }

    public function editAvailability(Ground $ground)
    {
        $this->authorize('update', $ground);
        
        $ground->load('availabilities');
        return view('owner.grounds.availability', compact('ground'));
    }

    public function updateAvailability(Request $request, Ground $ground)
    {
        $this->authorize('update', $ground);

        $validated = $request->validate([
            'availabilities' => 'required|array',
            'availabilities.*.day_of_week' => 'required|in:monday,tuesday,wednesday,thursday,friday,saturday,sunday',
            'availabilities.*.start_time' => 'required|date_format:H:i',
            'availabilities.*.end_time' => 'required|date_format:H:i|after:availabilities.*.start_time',
            'availabilities.*.is_active' => 'boolean',
        ]);

        try {
            // Delete existing availabilities
            $ground->availabilities()->delete();

            // Create new availabilities
            foreach ($validated['availabilities'] as $availability) {
                $ground->availabilities()->create($availability);
            }

            return redirect()
                ->route('owner.grounds.show', $ground)
                ->with('success', 'Availability updated successfully!');

        } catch (\Exception $e) {
            return back()->with('error', 'Failed to update availability: ' . $e->getMessage());
        }
    }

    public function toggleMaintenance(Ground $ground)
    {
        $this->authorize('update', $ground);

        $wasUnderMaintenance = $ground->is_under_maintenance;
        
        $ground->update([
            'is_under_maintenance' => !$ground->is_under_maintenance,
            // Clear schedule if toggling off
            'maintenance_start_date' => $ground->is_under_maintenance ? null : $ground->maintenance_start_date,
            'maintenance_end_date' => $ground->is_under_maintenance ? null : $ground->maintenance_end_date,
            'maintenance_reason' => $ground->is_under_maintenance ? null : $ground->maintenance_reason,
        ]);

        $status = $ground->is_under_maintenance ? 'under maintenance' : 'available';

        // Get all admins and send notification
        $admins = User::where('role', 'admin')->get();
        foreach ($admins as $admin) {
            $admin->notify(new GroundMaintenanceStatusChanged(
                $ground,
                Auth::user(),
                $ground->is_under_maintenance
            ));
        }

        return redirect()
            ->route('owner.grounds.show', $ground)
            ->with('success', "Ground is now {$status}");
    }

    public function scheduleMaintenance(Request $request, Ground $ground)
    {
        $this->authorize('update', $ground);

        $validated = $request->validate([
            'maintenance_action' => 'nullable|in:start,start-now,end',
            'maintenance_start_date' => 'required_if:maintenance_action,start|nullable|date|after:now',
            'maintenance_end_date' => 'nullable|date|after:maintenance_start_date',
            'maintenance_reason' => 'nullable|string|max:500',
        ]);

        try {
            $action = $validated['maintenance_action'] ?? 'start';
            
            if ($action === 'end') {
                // End maintenance immediately (Available Now)
                $ground->update([
                    'is_under_maintenance' => false,
                    'maintenance_start_date' => null,
                    'maintenance_end_date' => null,
                    'maintenance_reason' => null,
                ]);

                $message = 'Ground is now available for bookings';
                $isMaintenance = false;
            } elseif ($action === 'start-now') {
                // Mark as under maintenance immediately (no specific end date)
                $ground->update([
                    'is_under_maintenance' => true,
                    'maintenance_start_date' => now(),
                    'maintenance_end_date' => null,
                    'maintenance_reason' => $validated['maintenance_reason'] ?? null,
                ]);

                $message = 'Ground marked as under maintenance';
                $isMaintenance = true;
            } else {
                // Schedule maintenance with specific dates
                $startDate = $validated['maintenance_start_date'];
                $endDate = $validated['maintenance_end_date'];
                
                $ground->update([
                    'is_under_maintenance' => true,
                    'maintenance_start_date' => $startDate,
                    'maintenance_end_date' => $endDate,
                    'maintenance_reason' => $validated['maintenance_reason'] ?? null,
                ]);

                $message = 'Maintenance scheduled successfully.' . ($ground->maintenance_end_date ? ' Ground will be automatically available on ' . $ground->maintenance_end_date->format('M d, Y h:i A') : '');
                $isMaintenance = true;
            }

            // Notify admins
            $admins = User::where('role', 'admin')->get();
            foreach ($admins as $admin) {
                $admin->notify(new GroundMaintenanceStatusChanged(
                    $ground,
                    Auth::user(),
                    $isMaintenance
                ));
            }

            return redirect()
                ->route('owner.grounds.show', $ground)
                ->with('success', $message);

        } catch (\Exception $e) {
            return back()->with('error', 'Failed to update maintenance status: ' . $e->getMessage());
        }
    }

    public function destroy(Ground $ground)
    {
        $this->authorize('delete', $ground);

        $owner = Auth::user();
        $groundName = $ground->name;
        
        // Get all admins and send notification
        $admins = User::where('role', 'admin')->get();
        foreach ($admins as $admin) {
            $admin->notify(new GroundDeleted($ground, $owner));
        }

        $ground->delete();

        return redirect()
            ->route('owner.grounds.index')
            ->with('success', 'Ground deleted successfully');
    }
}

