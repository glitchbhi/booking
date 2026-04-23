<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Ground;
use App\Models\Review;
use App\Services\BookingService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class BookingController extends Controller
{
    public function __construct(
        protected BookingService $bookingService
    ) {}

    public function create(Ground $ground)
    {
        $this->authorize('create', Booking::class);

        return view('bookings.create', compact('ground'));
    }

    public function store(Request $request, Ground $ground)
    {
        $this->authorize('create', Booking::class);

        $validated = $request->validate([
            'start_time' => 'required|date|after_or_equal:now',
            'duration_hours' => 'required|numeric|min:1|max:168',
            'duration_unit' => 'required|in:hours,days',
            'notes' => 'nullable|string|max:500',
            'payment_proof' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ], [
            'duration_hours.min' => 'Minimum booking duration is 1 hour.',
            'duration_hours.required' => 'Please select a booking duration.',
            'start_time.after_or_equal' => 'Booking time must be in the future.',
            'payment_proof.image' => 'Payment proof must be an image.',
            'payment_proof.max' => 'Payment proof image must not exceed 2MB.',
        ]);

        try {
            $startDateTime = Carbon::parse($validated['start_time']);
            $duration = (int) $validated['duration_hours'];
            
            // Calculate end time
            if ($validated['duration_unit'] === 'hours') {
                $endDateTime = $startDateTime->copy()->addHours($duration);
            } else {
                $endDateTime = $startDateTime->copy()->addDays($duration);
            }

            // Handle payment proof upload
            $paymentProofPath = null;
            if ($request->hasFile('payment_proof')) {
                $paymentProofPath = $request->file('payment_proof')->store('payment_proofs', 'public');
            }

            // Create booking
            $booking = $this->bookingService->createBooking(
                $ground,
                Auth::user(),
                $startDateTime,
                $endDateTime,
                'online',
                $paymentProofPath
            );

            return redirect()
                ->route('bookings.show', $booking)
                ->with('success', 'Booking created successfully!');

        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', $e->getMessage());
        }
    }

    public function show(Booking $booking)
    {
        $this->authorize('view', $booking);

        $booking->load(['ground', 'user', 'review']);

        return view('bookings.show', compact('booking'));
    }

    public function downloadPDF(Booking $booking)
    {
        $this->authorize('view', $booking);

        $booking->load(['ground', 'user', 'review']);

        // Return a print-friendly view that user can save as PDF
        return view('bookings.pdf', compact('booking'));
    }

    public function updatePaymentProof(Request $request, Booking $booking)
    {
        $this->authorize('uploadPaymentProof', $booking);

        $validated = $request->validate([
            'payment_proof' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ], [
            'payment_proof.required' => 'Please select a payment screenshot to upload.',
            'payment_proof.image' => 'Payment proof must be an image.',
            'payment_proof.max' => 'Payment proof image must not exceed 2MB.',
        ]);

        if ($booking->payment_proof && Storage::disk('public')->exists($booking->payment_proof)) {
            Storage::disk('public')->delete($booking->payment_proof);
        }

        $paymentProofPath = $request->file('payment_proof')->store('payment_proofs', 'public');

        $booking->update([
            'payment_proof' => $paymentProofPath,
        ]);

        return redirect()
            ->route('bookings.show', $booking)
            ->with('success', 'Payment screenshot uploaded successfully.');
    }

    public function index()
    {
        $bookings = Auth::user()
            ->bookings()
            ->with(['ground', 'review'])
            ->latest()
            ->paginate(10);

        return view('bookings.index', compact('bookings'));
    }

    public function cancel(Booking $booking)
    {
        $this->authorize('cancel', $booking);

        return view('bookings.cancel', compact('booking'));
    }

    public function destroy(Request $request, Booking $booking)
    {
        $this->authorize('cancel', $booking);

        $validated = $request->validate([
            'reason' => 'nullable|string|max:500',
        ]);

        try {
            $this->bookingService->cancelBooking($booking, $validated['reason'] ?? null);

            return redirect()
                ->route('bookings.show', $booking)
                ->with('success', 'Booking cancelled successfully');

        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function checkAvailability(Request $request, Ground $ground)
    {
        $validated = $request->validate([
            'start_time' => 'required|date',
            'duration_hours' => 'required|numeric|min:1|max:168',
            'duration_unit' => 'required|in:hours,days',
        ], [
            'duration_hours.min' => 'Minimum booking duration is 1 hour.',
            'duration_hours.required' => 'Please select a booking duration.',
        ]);

        try {
            $startDateTime = Carbon::parse($validated['start_time']);
            
            if ($validated['duration_unit'] === 'hours') {
                $endDateTime = $startDateTime->copy()->addHours($validated['duration_hours']);
            } else {
                $endDateTime = $startDateTime->copy()->addDays($validated['duration_hours']);
            }

            $isAvailable = $this->bookingService->isSlotAvailable($ground, $startDateTime, $endDateTime);
            
            // Get specific availability message
            $message = $this->getAvailabilityMessage($ground, $startDateTime, $endDateTime, $isAvailable);
            
            $durationHours = $endDateTime->diffInHours($startDateTime);
            $totalAmount = $this->bookingService->calculateBookingAmount($ground, $startDateTime, $endDateTime);
            $effectiveRate = $durationHours > 0 ? $totalAmount / $durationHours : $ground->rate_per_hour;

            return response()->json([
                'available' => $isAvailable,
                'duration_hours' => $durationHours,
                'rate_per_hour' => $effectiveRate,
                'day_rate' => $ground->rate_per_hour,
                'night_rate' => $ground->night_rate_per_hour,
                'total_amount' => $totalAmount,
                'message' => $message,
                'maintenance_info' => $this->getMaintenanceInfo($ground)
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'available' => false,
                'message' => $e->getMessage()
            ], 400);
        }
    }

    /**
     * Get specific availability message including maintenance information
     */
    protected function getAvailabilityMessage(Ground $ground, Carbon $startTime, Carbon $endTime, bool $isAvailable): string
    {
        if (!$isAvailable) {
            // Check for maintenance conflicts first
            if ($ground->is_under_maintenance) {
                return 'This ground is currently under maintenance and not available for booking.';
            }

            if ($ground->maintenance_start_date && $ground->maintenance_end_date) {
                $maintenanceStart = $ground->maintenance_start_date;
                $maintenanceEnd = $ground->maintenance_end_date;
                
                if ($startTime->lt($maintenanceEnd) && $endTime->gt($maintenanceStart)) {
                    return "This ground is under maintenance from {$maintenanceStart->format('M d, Y h:i A')} to {$maintenanceEnd->format('M d, Y h:i A')}. Please choose a different time.";
                }
            }

            if ($ground->maintenance_start_date && !$ground->maintenance_end_date) {
                $maintenanceStart = $ground->maintenance_start_date;
                if ($startTime->gte($maintenanceStart)) {
                    return "This ground is under maintenance starting {$maintenanceStart->format('M d, Y h:i A')} and is not available for booking.";
                }
            }

            return 'This time slot is already booked. Please choose a different time.';
        }

        return 'Slot is available';
    }

    /**
     * Get maintenance information for display
     */
    protected function getMaintenanceInfo(Ground $ground): ?array
    {
        if (!$ground->maintenance_start_date) {
            return null;
        }

        return [
            'start_date' => $ground->maintenance_start_date->format('M d, Y h:i A'),
            'end_date' => $ground->maintenance_end_date ? $ground->maintenance_end_date->format('M d, Y h:i A') : null,
            'reason' => $ground->maintenance_reason,
            'is_under_maintenance' => $ground->is_under_maintenance,
            'remaining_time' => $ground->getMaintenanceRemainingTime()
        ];
    }
}
