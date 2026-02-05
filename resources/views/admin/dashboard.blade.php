@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <h1 class="text-3xl font-bold text-gray-900 mb-6">Admin Dashboard</h1>
    
    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Total Revenue</p>
                    <p class="text-2xl font-bold text-gray-900">BTN {{ number_format($totalRevenue, 2) }}</p>
                </div>
                <i class="fas fa-rupee-sign text-green-500 text-3xl"></i>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Admin Commission</p>
                    <p class="text-2xl font-bold text-gray-900">BTN {{ number_format($adminCommission, 2) }}</p>
                </div>
                <i class="fas fa-coins text-yellow-500 text-3xl"></i>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Total Bookings</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $totalBookings }}</p>
                </div>
                <i class="fas fa-calendar-check text-blue-500 text-3xl"></i>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Total Grounds</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $totalGrounds }}</p>
                </div>
                <i class="fas fa-store text-indigo-500 text-3xl"></i>
            </div>
        </div>
    </div>

    <!-- Second Row Stats -->
    <div class="grid grid-cols-1 md:grid-cols-5 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Total Users</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $totalUsers }}</p>
                </div>
                <i class="fas fa-users text-purple-500 text-3xl"></i>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Total Owners</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $totalOwners }}</p>
                </div>
                <i class="fas fa-user-tie text-teal-500 text-3xl"></i>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Pending Requests</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $pendingOwnerRequests }}</p>
                </div>
                <i class="fas fa-clock text-orange-500 text-3xl"></i>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Inactive Grounds</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $inactiveGrounds }}</p>
                </div>
                <i class="fas fa-exclamation-circle text-yellow-500 text-3xl"></i>
            </div>
            @if($inactiveGrounds > 0)
                <a href="{{ route('admin.grounds.index', ['status' => 'inactive']) }}" class="text-xs text-blue-600 hover:text-blue-800 mt-2 inline-block">
                    <i class="fas fa-arrow-right"></i> Review grounds
                </a>
            @endif
        </div>

        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Suspended Users</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $suspendedUsers }}</p>
                </div>
                <i class="fas fa-ban text-red-500 text-3xl"></i>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <a href="{{ route('admin.bookings.index') }}" class="bg-gradient-to-r from-blue-500 to-blue-600 text-white rounded-xl shadow-lg p-6 hover:from-blue-600 hover:to-blue-700 transform hover:scale-105 transition-all">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-semibold mb-1">View All Bookings</h3>
                    <p class="text-sm text-blue-100">Manage all platform bookings</p>
                </div>
                <i class="fas fa-calendar-alt text-4xl opacity-80"></i>
            </div>
        </a>
        
        <a href="{{ route('admin.grounds.index') }}" class="bg-gradient-to-r from-green-500 to-green-600 text-white rounded-xl shadow-lg p-6 hover:from-green-600 hover:to-green-700 transform hover:scale-105 transition-all">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-semibold mb-1">Manage Grounds</h3>
                    <p class="text-sm text-green-100">View and manage all grounds</p>
                </div>
                <i class="fas fa-store text-4xl opacity-80"></i>
            </div>
        </a>
        
        <a href="{{ route('admin.users.index') }}" class="bg-gradient-to-r from-purple-500 to-purple-600 text-white rounded-xl shadow-lg p-6 hover:from-purple-600 hover:to-purple-700 transform hover:scale-105 transition-all">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-semibold mb-1">Manage Users</h3>
                    <p class="text-sm text-purple-100">User management dashboard</p>
                </div>
                <i class="fas fa-users-cog text-4xl opacity-80"></i>
            </div>
        </a>
    </div>

    <!-- Popular Sports & Top Grounds -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-bold text-gray-900 mb-4">Popular Sports</h2>
            <div class="space-y-3">
                @foreach($popularSports as $sport)
                    <div class="flex justify-between items-center">
                        <span class="text-gray-600">{{ $sport->name }}</span>
                        <div class="flex items-center">
                            <div class="w-48 h-6 bg-gray-200 rounded-full overflow-hidden mr-3">
                                <div class="h-full bg-indigo-600" style="width: {{ $popularSports->max('booking_count') > 0 ? ($sport->booking_count / $popularSports->max('booking_count')) * 100 : 0 }}%"></div>
                            </div>
                            <span class="font-semibold text-gray-900">{{ $sport->booking_count }} bookings</span>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-bold text-gray-900 mb-4">Top Performing Grounds</h2>
            <div class="space-y-3">
                @foreach($topGrounds as $ground)
                    <div class="flex justify-between items-center">
                        <div>
                            <span class="text-gray-900 font-semibold">{{ $ground->name }}</span>
                            <p class="text-xs text-gray-500">{{ $ground->location }}</p>
                        </div>
                        <div class="text-right">
                            <p class="font-semibold text-gray-900">{{ $ground->bookings_count }} bookings</p>
                            <p class="text-xs text-gray-500">BTN {{ number_format($ground->revenue ?? 0, 2) }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Recent Activity -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-xl font-bold text-gray-900">Recent Bookings</h2>
                <a href="{{ route('admin.grounds.index') }}" class="text-indigo-600 hover:text-indigo-800 text-sm">View All →</a>
            </div>
            <div class="space-y-3">
                @foreach($recentBookings as $booking)
                    <div class="flex justify-between items-center border-b border-gray-200 pb-2">
                        <div>
                            <p class="text-sm font-semibold text-gray-900">{{ $booking->ground->name }}</p>
                            <p class="text-xs text-gray-500">{{ $booking->user->name }}</p>
                        </div>
                        <div class="text-right">
                            <p class="text-sm font-semibold text-gray-900">BTN {{ number_format($booking->total_amount, 2) }}</p>
                            <p class="text-xs text-gray-500">{{ $booking->created_at->diffForHumans() }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-xl font-bold text-gray-900">Pending Owner Requests</h2>
                <a href="{{ route('admin.owner-requests.index') }}" class="text-indigo-600 hover:text-indigo-800 text-sm">View All →</a>
            </div>
            <div class="space-y-3">
                @foreach($recentOwnerRequests as $request)
                    <div class="flex justify-between items-center border-b border-gray-200 pb-2">
                        <div>
                            <p class="text-sm font-semibold text-gray-900">{{ $request->user->name }}</p>
                            <p class="text-xs text-gray-500">{{ $request->business_name }}</p>
                        </div>
                        <div>
                            <a href="{{ route('admin.owner-requests.show', $request) }}" 
                               class="text-xs bg-indigo-600 text-white px-3 py-1 rounded-md hover:bg-indigo-700">
                                Review
                            </a>
                        </div>
                    </div>
                @endforeach
                @if($recentOwnerRequests->count() == 0)
                    <p class="text-gray-500 text-sm text-center py-4">No pending requests</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
