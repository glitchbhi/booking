<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Ground;
use App\Models\SportsType;
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
        return view('owner.grounds.create', compact('sportsTypes'));
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
            'rate_per_hour' => 'required|numeric|min:0',
            'night_rate_per_hour' => 'nullable|numeric|min:0',
            'capacity' => 'required|string|max:50',
            'capacity_description' => 'nullable|string|max:255',
            'day_rate_start' => 'nullable|date_format:H:i',
            'day_rate_end' => 'nullable|date_format:H:i',
            'night_rate_start' => 'nullable|date_format:H:i',
            'night_rate_end' => 'nullable|date_format:H:i',
            'images' => 'nullable|array|max:4',
            'images.*' => 'nullable|image|mimes:jpeg,jpg,png,gif,webp|max:2048',
            'is_active' => 'boolean',
        ]);

        try {
            $validated['owner_id'] = Auth::id();
            $validated['is_active'] = false; // Manually added grounds require admin approval
            
            // Handle image uploads
            if ($request->hasFile('images')) {
                $images = [];
                foreach ($request->file('images') as $image) {
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
            'rate_per_hour' => 'required|numeric|min:0',
            'night_rate_per_hour' => 'nullable|numeric|min:0',
            'capacity' => 'required|string|max:50',
            'capacity_description' => 'nullable|string|max:255',
            'day_rate_start' => 'nullable|date_format:H:i',
            'day_rate_end' => 'nullable|date_format:H:i',
            'night_rate_start' => 'nullable|date_format:H:i',
            'night_rate_end' => 'nullable|date_format:H:i',
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
            
            $ground->update($validated);

            return redirect()
                ->route('owner.grounds.show', $ground)
                ->with('success', 'Ground updated successfully!');

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
}
