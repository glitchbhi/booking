@extends('layouts.app')

@section('title', 'My Bookings')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <h1 class="text-3xl font-bold text-gray-900 mb-6">My Bookings</h1>
    
    @if($bookings->count() > 0)
        <div class="space-y-4">
            @foreach($bookings as $booking)
                <div class="bg-white rounded-lg shadow-md p-6">
                    <div class="flex justify-between items-start">
                        <div class="flex-1">
                            <h3 class="text-xl font-bold text-gray-900">{{ $booking->ground->name }}</h3>
                            <p class="text-gray-600 mt-1">
                                <i class="fas fa-map-marker-alt"></i> {{ $booking->ground->location }}
                            </p>
                            <p class="text-sm text-gray-600 mt-2">
                                <i class="fas fa-calendar"></i> {{ $booking->start_time->format('M d, Y h:i A') }} - {{ $booking->end_time->format('h:i A') }}
                            </p>
                            <p class="text-sm text-gray-600">
                                <i class="fas fa-clock"></i> Duration: {{ $booking->duration_hours }} hours
                            </p>
                            <p class="text-sm text-gray-600">
                                <i class="fas fa-receipt"></i> Booking #{{ $booking->booking_number }}
                            </p>
                        </div>
                        <div class="text-right">
                            <div class="text-2xl font-bold text-gray-900">BTN {{ number_format($booking->total_amount, 2) }}</div>
                            @if($booking->status === 'booked')
                                <span class="inline-block px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-sm font-semibold mt-2">Booked</span>
                            @elseif($booking->status === 'ongoing')
                                <span class="inline-block px-3 py-1 bg-green-100 text-green-800 rounded-full text-sm font-semibold mt-2">Ongoing</span>
                            @elseif($booking->status === 'completed')
                                <span class="inline-block px-3 py-1 bg-gray-100 text-gray-800 rounded-full text-sm font-semibold mt-2">Completed</span>
                            @elseif($booking->status === 'cancelled')
                                <span class="inline-block px-3 py-1 bg-red-100 text-red-800 rounded-full text-sm font-semibold mt-2">Cancelled</span>
                            @endif
                        </div>
                    </div>
                    
                    <div class="mt-4 flex space-x-2">
                        <a href="{{ route('bookings.show', $booking) }}" 
                           class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700">
                            View Details
                        </a>
                        
                        @if($booking->canBeCancelled())
                            <form action="{{ route('bookings.cancel', $booking) }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" 
                                        onclick="return confirm('Are you sure you want to cancel this booking?')"
                                        class="bg-red-600 text-white px-4 py-2 rounded-md hover:bg-red-700">
                                    Cancel Booking
                                </button>
                            </form>
                        @endif
                        
                        @if($booking->status === 'completed' && !$booking->review)
                            <a href="{{ route('reviews.create', $booking) }}" 
                               class="bg-yellow-500 text-white px-4 py-2 rounded-md hover:bg-yellow-600">
                                <i class="fas fa-star"></i> Write Review
                            </a>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
        <div class="mt-6">
            {{ $bookings->links() }}
        </div>
    @else
        <div class="bg-white rounded-lg shadow-md p-8 text-center">
            <i class="fas fa-calendar-times text-gray-400 text-6xl mb-4"></i>
            <p class="text-gray-600 text-lg mb-4">You don't have any bookings yet.</p>
            <a href="{{ route('grounds.browse') }}" class="inline-block bg-green-600 text-white px-6 py-3 rounded-md hover:bg-green-700">
                Browse Grounds
            </a>
        </div>
    @endif
</div>
@endsection
