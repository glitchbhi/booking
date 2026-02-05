@extends('layouts.app')

@section('title', 'All Bookings')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-900">All Bookings</h1>
        <a href="{{ route('owner.bookings.create') }}" class="bg-indigo-600 text-white px-6 py-3 rounded-md hover:bg-indigo-700">
            <i class="fas fa-plus"></i> Add Offline Booking
        </a>
    </div>
    
    <!-- Filter by Ground -->
    <div class="bg-white rounded-lg shadow-md p-4 mb-6">
        <form action="{{ route('owner.bookings.index') }}" method="GET" class="flex space-x-4">
            <select name="ground_id" class="flex-1 px-4 py-2 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500">
                <option value="">All Grounds</option>
                @foreach($ownerGrounds as $g)
                    <option value="{{ $g->id }}" {{ request('ground_id') == $g->id ? 'selected' : '' }}>
                        {{ $g->name }}
                    </option>
                @endforeach
            </select>
            <button type="submit" class="bg-indigo-600 text-white px-6 py-2 rounded-md hover:bg-indigo-700">
                Filter
            </button>
        </form>
    </div>

    @if($bookings->count() > 0)
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Booking #</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Ground</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">User</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date & Time</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Duration</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Amount</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Type</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($bookings as $booking)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">{{ $booking->booking_number }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">{{ $booking->ground->name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">{{ $booking->user->name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">{{ $booking->start_time->format('M d, Y h:i A') }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">{{ $booking->duration_hours }} hrs</td>
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
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 py-1 text-xs rounded-full {{ $booking->is_offline ? 'bg-orange-100 text-orange-800' : 'bg-green-100 text-green-800' }}">
                                    {{ $booking->is_offline ? 'Offline' : 'Online' }}
                                </span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="mt-6">
            {{ $bookings->links() }}
        </div>
    @else
        <div class="bg-white rounded-lg shadow-md p-8 text-center">
            <i class="fas fa-calendar-times text-gray-400 text-6xl mb-4"></i>
            <p class="text-gray-600 text-lg">No bookings found.</p>
        </div>
    @endif
</div>
@endsection
