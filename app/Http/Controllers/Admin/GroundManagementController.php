<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ground;
use App\Models\SportsType;
use App\Models\User;
use Illuminate\Http\Request;

class GroundManagementController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'admin']);
    }

    public function index(Request $request)
    {
        $query = Ground::with(['owner', 'sportType']);

        // Filters
        if ($request->filled('sport_type')) {
            $query->where('sport_type_id', $request->sport_type);
        }

        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active');
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('location', 'like', "%{$search}%");
            });
        }

        $grounds = $query->latest()->paginate(15);
        $sportsTypes = SportsType::active()->get();

        return view('admin.grounds.index', compact('grounds', 'sportsTypes'));
    }

    public function show(Ground $ground)
    {
        $ground->load(['owner', 'sportType', 'availabilities', 'bookings', 'reviews']);
        
        return view('admin.grounds.show', compact('ground'));
    }

    public function toggleStatus(Ground $ground)
    {
        $ground->update([
            'is_active' => !$ground->is_active,
        ]);

        $status = $ground->is_active ? 'activated' : 'deactivated';

        return redirect()
            ->route('admin.grounds.show', $ground)
            ->with('success', "Ground {$status} successfully");
    }

    public function destroy(Ground $ground)
    {
        $ground->delete();

        return redirect()
            ->route('admin.grounds.index')
            ->with('success', 'Ground deleted successfully');
    }

    public function create()
    {
        $owners = User::where('role', 'owner')
            ->where('owner_status', 'approved')
            ->orderBy('name')
            ->get();
        $sportsTypes = SportsType::active()->get();

        return view('admin.grounds.create', compact('owners', 'sportsTypes'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'owner_id' => 'required|exists:users,id',
            'sport_type_id' => 'required|exists:sports_types,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:2000',
            'location' => 'required|string|max:255',
            'address' => 'required|string|max:500',
            'rate_per_hour' => 'required|numeric|min:0',
            'rate_per_hour_night' => 'nullable|numeric|min:0',
            'is_active' => 'boolean',
        ]);

        $ground = Ground::create([
            'owner_id' => $validated['owner_id'],
            'sport_type_id' => $validated['sport_type_id'],
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'location' => $validated['location'],
            'address' => $validated['address'],
            'rate_per_hour' => $validated['rate_per_hour'],
            'is_active' => $request->boolean('is_active', true),
        ]);

        // Create default availabilities (6 AM - 11 PM all days)
        $days = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];
        foreach ($days as $day) {
            $ground->availabilities()->create([
                'day_of_week' => $day,
                'start_time' => '06:00:00',
                'end_time' => '23:00:00',
                'is_active' => true,
            ]);
        }

        return redirect()
            ->route('admin.grounds.show', $ground)
            ->with('success', 'Ground created successfully!');
    }
}
