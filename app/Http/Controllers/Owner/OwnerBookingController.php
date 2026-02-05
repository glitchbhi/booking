<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Ground;
use App\Services\BookingService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OwnerBookingController extends Controller
{
    public function __construct(
        protected BookingService $bookingService
    ) {
        $this->middleware(['auth', 'owner']);
    }

    public function index(Request $request)
    {
        $grounds = Auth::user()->grounds()->pluck('id');
        
        $query = Booking::with(['user', 'ground'])
            ->whereIn('ground_id', $grounds);

        // Filters
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('type')) {
            $query->where('booking_type', $request->type);
        }

        if ($request->filled('ground_id')) {
            $query->where('ground_id', $request->ground_id);
        }

        $bookings = $query->latest()->paginate(15);
        $ownerGrounds = Auth::user()->grounds;

        return view('owner.bookings.index', compact('bookings', 'ownerGrounds'));
    }

    public function create()
    {
        $grounds = Auth::user()->grounds;
        return view('owner.bookings.create', compact('grounds'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'ground_id' => 'required|exists:grounds,id',
            'customer_name' => 'required|string|max:255',
            'customer_phone' => 'nullable|string|max:20',
            'start_time' => 'required|date',
            'duration_hours' => 'required|numeric|min:1|max:168',
            'notes' => 'nullable|string|max:1000',
        ]);

        // Verify ownership
        $ground = Ground::findOrFail($validated['ground_id']);
        if ($ground->owner_id !== Auth::id()) {
            abort(403);
        }

        try {
            $startDateTime = Carbon::parse($validated['start_time']);
            $endDateTime = $startDateTime->copy()->addHours((float) $validated['duration_hours']);

            // Create offline booking (using owner as user)
            $booking = $this->bookingService->createBooking(
                $ground,
                Auth::user(),
                $startDateTime,
                $endDateTime,
                'offline'
            );

            // Store customer details in metadata
            $booking->update([
                'cancellation_reason' => json_encode([
                    'customer_name' => $validated['customer_name'],
                    'customer_phone' => $validated['customer_phone'],
                    'notes' => $validated['notes'] ?? null,
                ])
            ]);

            return redirect()
                ->route('owner.bookings.index')
                ->with('success', 'Offline booking created successfully!');

        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', $e->getMessage());
        }
    }
}
