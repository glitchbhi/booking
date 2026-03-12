<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Ground;
use App\Models\OwnerRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminDashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'admin']);
    }

    public function index(Request $request)
    {
        // Date range filter
        $startDate = $request->get('start_date', now()->startOfMonth());
        $endDate = $request->get('end_date', now()->endOfMonth());

        // Total system revenue
        $totalRevenue = Booking::whereIn('status', ['completed'])
            ->whereBetween('created_at', [$startDate, $endDate])
            ->sum('total_amount');

        // User statistics
        $totalUsers = User::where('role', 'user')->count();
        $totalOwners = User::where('role', 'owner')->where('owner_status', 'approved')->count();
        $pendingOwnerRequests = User::where('owner_status', 'pending')->count();
        $suspendedUsers = User::where('is_suspended', true)->count();

        // Booking statistics
        $totalBookings = Booking::whereBetween('created_at', [$startDate, $endDate])->count();
        $activeBookings = Booking::whereIn('status', ['booked', 'ongoing'])->count();
        $completedBookings = Booking::where('status', 'completed')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->count();

        // Ground statistics
        $totalGrounds = Ground::count();
        $activeGrounds = Ground::where('is_active', true)->count();
        $inactiveGrounds = Ground::where('is_active', false)->count();

        // Top grounds by booking count
        $topGrounds = Ground::withCount(['bookings' => function ($q) use ($startDate, $endDate) {
                $q->whereBetween('created_at', [$startDate, $endDate]);
            }])
            ->withSum(['bookings as revenue' => function ($q) use ($startDate, $endDate) {
                $q->whereIn('status', ['completed'])
                  ->whereBetween('created_at', [$startDate, $endDate]);
            }], 'total_amount')
            ->orderBy('bookings_count', 'desc')
            ->limit(10)
            ->get();

        // Most popular sports
        $popularSports = DB::table('sports_types')
            ->join('grounds', 'sports_types.id', '=', 'grounds.sport_type_id')
            ->join('bookings', 'grounds.id', '=', 'bookings.ground_id')
            ->whereBetween('bookings.created_at', [$startDate, $endDate])
            ->select('sports_types.name', DB::raw('COUNT(bookings.id) as booking_count'))
            ->groupBy('sports_types.id', 'sports_types.name')
            ->orderBy('booking_count', 'desc')
            ->limit(5)
            ->get();

        // Revenue chart data - database agnostic
        $revenueBookings = Booking::whereIn('status', ['completed'])
            ->whereBetween('created_at', [$startDate, $endDate])
            ->get(['created_at', 'total_amount']);
        
        $dailyRevenue = $revenueBookings->groupBy(function($booking) {
            return \Carbon\Carbon::parse($booking->created_at)->format('Y-m-d');
        })->map(function($group, $date) {
            return (object)[
                'date' => $date,
                'revenue' => $group->sum('total_amount')
            ];
        })->sortBy('date')->values();

        // Recent bookings
        $recentBookings = Booking::with(['user', 'ground'])
            ->latest()
            ->limit(10)
            ->get();

        // Pending owner requests
        $recentOwnerRequests = OwnerRequest::with('user')
            ->where('status', 'pending')
            ->latest()
            ->limit(5)
            ->get();

        return view('admin.dashboard', compact(
            'totalRevenue',
            'totalUsers',
            'totalOwners',
            'pendingOwnerRequests',
            'suspendedUsers',
            'totalBookings',
            'activeBookings',
            'completedBookings',
            'totalGrounds',
            'activeGrounds',
            'inactiveGrounds',
            'topGrounds',
            'popularSports',
            'dailyRevenue',
            'recentBookings',
            'recentOwnerRequests'
        ));
    }
}
