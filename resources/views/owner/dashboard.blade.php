@extends('layouts.app')

@section('title', 'Owner Dashboard')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-900">Owner Dashboard</h1>
    </div>

    <!-- Quick Actions -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-8">
        <h2 class="text-xl font-bold text-gray-900 mb-4">Quick Actions</h2>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <a href="{{ route('owner.grounds.index') }}" class="flex flex-col items-center p-4 bg-indigo-50 rounded-lg hover:bg-indigo-100 transition">
                <i class="fas fa-store text-indigo-600 text-3xl mb-2"></i>
                <span class="text-sm font-medium text-gray-700">My Grounds</span>
            </a>
            <a href="{{ route('owner.grounds.create') }}" class="flex flex-col items-center p-4 bg-green-50 rounded-lg hover:bg-green-100 transition">
                <i class="fas fa-plus-circle text-green-600 text-3xl mb-2"></i>
                <span class="text-sm font-medium text-gray-700">Add Ground</span>
            </a>
            <a href="{{ route('owner.bookings.index') }}" class="flex flex-col items-center p-4 bg-blue-50 rounded-lg hover:bg-blue-100 transition">
                <i class="fas fa-calendar-alt text-blue-600 text-3xl mb-2"></i>
                <span class="text-sm font-medium text-gray-700">Bookings</span>
            </a>
            <a href="{{ route('owner.bookings.create') }}" class="flex flex-col items-center p-4 bg-purple-50 rounded-lg hover:bg-purple-100 transition">
                <i class="fas fa-calendar-plus text-purple-600 text-3xl mb-2"></i>
                <span class="text-sm font-medium text-gray-700">Offline Booking</span>
            </a>
        </div>
    </div>
    
    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Total Revenue</p>
                    <p class="text-2xl font-bold text-gray-900">BTN {{ number_format($ownerRevenue, 2) }}</p>
                </div>
                <i class="fas fa-rupee-sign text-green-500 text-3xl"></i>
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
                    <p class="text-sm text-gray-600">My Grounds</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $totalGrounds }}</p>
                </div>
                <i class="fas fa-store text-indigo-500 text-3xl"></i>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Online Bookings</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $onlineBookings }}</p>
                </div>
                <i class="fas fa-globe text-purple-500 text-3xl"></i>
            </div>
        </div>
    </div>

    <!-- Revenue Chart -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-bold text-gray-900 mb-4">Daily Revenue (Last 7 Days)</h2>
            <div class="space-y-3">
                @foreach($dailyRevenue as $day)
                    <div class="flex justify-between items-center">
                        <span class="text-gray-600">{{ $day->date }}</span>
                        <div class="flex items-center">
                            <div class="w-48 h-6 bg-gray-200 rounded-full overflow-hidden mr-3">
                                <div class="h-full bg-indigo-600" style="width: {{ $day->total > 0 ? min(($day->total / $dailyRevenue->max('total')) * 100, 100) : 0 }}%"></div>
                            </div>
                            <span class="font-semibold text-gray-900 w-24 text-right">BTN {{ number_format($day->total, 2) }}</span>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-bold text-gray-900 mb-4">Peak Hours</h2>
            <div class="space-y-3">
                @foreach($peakHours->take(8) as $peak)
                    <div class="flex justify-between items-center">
                        <span class="text-gray-600">{{ $peak->hour }}:00 - {{ $peak->hour + 1 }}:00</span>
                        <div class="flex items-center">
                            <div class="w-32 h-6 bg-gray-200 rounded-full overflow-hidden mr-3">
                                <div class="h-full bg-green-600" style="width: {{ ($peak->bookings / $peakHours->max('bookings')) * 100 }}%"></div>
                            </div>
                            <span class="font-semibold text-gray-900">{{ $peak->bookings }} bookings</span>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Recent Bookings -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-bold text-gray-900">Recent Bookings</h2>
            <a href="{{ route('owner.bookings.index') }}" class="text-indigo-600 hover:text-indigo-800">View All →</a>
        </div>
        
        @if($recentBookings->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Booking #</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Ground</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">User</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Amount</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($recentBookings as $booking)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">{{ $booking->booking_number }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">{{ $booking->ground->name }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">{{ $booking->user->name }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">{{ $booking->start_time->format('M d, Y') }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold">BTN {{ number_format($booking->total_amount, 2) }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 py-1 text-xs rounded-full
                                        {{ $booking->status === 'booked' ? 'bg-blue-100 text-blue-800' : '' }}
                                        {{ $booking->status === 'ongoing' ? 'bg-green-100 text-green-800' : '' }}
                                        {{ $booking->status === 'completed' ? 'bg-gray-100 text-gray-800' : '' }}
                                        {{ $booking->status === 'cancelled' ? 'bg-red-100 text-red-800' : '' }}">
                                        {{ ucfirst($booking->status) }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <p class="text-gray-600 text-center py-8">No bookings yet.</p>
        @endif
    </div>
</div>
@endsection
