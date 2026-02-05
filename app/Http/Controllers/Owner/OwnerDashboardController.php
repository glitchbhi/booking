<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OwnerDashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'owner']);
    }

    public function index(Request $request)
    {
        $groundIds = Auth::user()->grounds()->pluck('id');
        $totalGrounds = Auth::user()->grounds()->count();
        
        // Date range filter
        $startDate = $request->get('start_date', now()->startOfMonth());
        $endDate = $request->get('end_date', now()->endOfMonth());

        // Total revenue
        $totalRevenue = Booking::whereIn('ground_id', $groundIds)
            ->whereIn('status', ['completed'])
            ->whereBetween('created_at', [$startDate, $endDate])
            ->sum('total_amount');

        // Admin commission (owner gets 98%)
        $ownerRevenue = $totalRevenue * 0.98;
        $adminCommission = $totalRevenue * 0.02;

        // Booking statistics
        $totalBookings = Booking::whereIn('ground_id', $groundIds)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->count();

        $onlineBookings = Booking::whereIn('ground_id', $groundIds)
            ->where('booking_type', 'online')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->count();

        $offlineBookings = Booking::whereIn('ground_id', $groundIds)
            ->where('booking_type', 'offline')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->count();

        // Peak hours analysis
        $peakHours = Booking::whereIn('ground_id', $groundIds)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->select(DB::raw('HOUR(start_time) as hour'), DB::raw('COUNT(*) as bookings'))
            ->groupBy('hour')
            ->orderBy('bookings', 'desc')
            ->limit(8)
            ->get();

        // Daily revenue chart data (last 7 days)
        $dailyRevenue = Booking::whereIn('ground_id', $groundIds)
            ->whereIn('status', ['completed'])
            ->whereBetween('created_at', [now()->subDays(7), now()])
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('SUM(total_amount) as total'))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Top performing grounds
        $topGrounds = Auth::user()->grounds()
            ->withCount(['bookings' => function ($q) use ($startDate, $endDate) {
                $q->whereBetween('created_at', [$startDate, $endDate]);
            }])
            ->withSum(['bookings as revenue' => function ($q) use ($startDate, $endDate) {
                $q->whereIn('status', ['completed'])
                  ->whereBetween('created_at', [$startDate, $endDate]);
            }], 'total_amount')
            ->orderBy('bookings_count', 'desc')
            ->limit(5)
            ->get();

        // Recent bookings
        $recentBookings = Booking::whereIn('ground_id', $groundIds)
            ->with(['ground', 'user'])
            ->latest()
            ->limit(10)
            ->get();

        return view('owner.dashboard', compact(
            'totalRevenue',
            'ownerRevenue',
            'adminCommission',
            'totalBookings',
            'totalGrounds',
            'onlineBookings',
            'offlineBookings',
            'peakHours',
            'dailyRevenue',
            'topGrounds',
            'recentBookings'
        ));
    }
}
