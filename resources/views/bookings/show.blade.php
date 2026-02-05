@extends('layouts.app')

@section('title', 'Booking Details')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex justify-between items-start mb-6">
            <h1 class="text-2xl font-bold text-gray-900">Booking Details</h1>
            @if($booking->status === 'booked')
                <span class="px-4 py-2 bg-blue-100 text-blue-800 rounded-full font-semibold">Booked</span>
            @elseif($booking->status === 'ongoing')
                <span class="px-4 py-2 bg-green-100 text-green-800 rounded-full font-semibold">Ongoing</span>
            @elseif($booking->status === 'completed')
                <span class="px-4 py-2 bg-gray-100 text-gray-800 rounded-full font-semibold">Completed</span>
            @elseif($booking->status === 'cancelled')
                <span class="px-4 py-2 bg-red-100 text-red-800 rounded-full font-semibold">Cancelled</span>
            @endif
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Ground Information</h2>
                <div class="space-y-2">
                    <p class="text-gray-600"><strong>Ground:</strong> {{ $booking->ground->name }}</p>
                    <p class="text-gray-600"><strong>Location:</strong> {{ $booking->ground->location }}</p>
                    <p class="text-gray-600"><strong>Sport:</strong> {{ $booking->ground->sportType->name }}</p>
                    <p class="text-gray-600"><strong>Owner:</strong> {{ $booking->ground->owner->name }}</p>
                </div>
            </div>

            <div>
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Booking Information</h2>
                <div class="space-y-2">
                    <p class="text-gray-600"><strong>Booking #:</strong> {{ $booking->booking_number }}</p>
                    <p class="text-gray-600"><strong>Start Time:</strong> {{ $booking->start_time->format('M d, Y h:i A') }}</p>
                    <p class="text-gray-600"><strong>End Time:</strong> {{ $booking->end_time->format('M d, Y h:i A') }}</p>
                    <p class="text-gray-600"><strong>Duration:</strong> {{ $booking->duration_hours }} hours</p>
                    <p class="text-gray-600"><strong>Booked On:</strong> {{ $booking->created_at->format('M d, Y h:i A') }}</p>
                </div>
            </div>
        </div>

        @if($booking->notes)
            <div class="mt-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-2">Notes</h2>
                <p class="text-gray-600 bg-gray-50 rounded-md p-4">{{ $booking->notes }}</p>
            </div>
        @endif

        <div class="mt-6 border-t pt-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Payment Details</h2>
            <div class="space-y-2">
                <div class="flex justify-between">
                    <span class="text-gray-600">Rate per Hour:</span>
                    <span class="font-semibold">BTN {{ number_format($booking->ground->rate_per_hour, 2) }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">Duration:</span>
                    <span class="font-semibold">{{ $booking->duration_hours }} hours</span>
                </div>
                <div class="flex justify-between text-lg font-bold">
                    <span>Total Amount:</span>
                    <span class="text-indigo-600">BTN {{ number_format($booking->total_amount, 2) }}</span>
                </div>
                @if($booking->status === 'cancelled' && $booking->refund_amount > 0)
                    <div class="flex justify-between text-green-600">
                        <span>Refund Amount:</span>
                        <span class="font-bold">BTN {{ number_format($booking->refund_amount, 2) }}</span>
                    </div>
                @endif
            </div>
        </div>

        @if($booking->review)
            <div class="mt-6 border-t pt-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Your Review</h2>
                <div class="bg-gray-50 rounded-md p-4">
                    <div class="flex items-center mb-2">
                        @for($i = 1; $i <= 5; $i++)
                            <i class="fas fa-star {{ $i <= $booking->review->rating ? 'text-yellow-400' : 'text-gray-300' }}"></i>
                        @endfor
                        <span class="ml-2 text-gray-600">{{ $booking->review->rating }}/5</span>
                    </div>
                    @if($booking->review->comment)
                        <p class="text-gray-600">{{ $booking->review->comment }}</p>
                    @endif
                </div>
            </div>
        @endif

        <div class="mt-6 flex space-x-4">
            <a href="{{ route('bookings.index') }}" class="bg-gray-200 text-gray-700 px-6 py-2 rounded-md hover:bg-gray-300">
                <i class="fas fa-arrow-left"></i> Back to Bookings
            </a>
            
            @if($booking->canBeCancelled())
                <form action="{{ route('bookings.cancel', $booking) }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" 
                            onclick="return confirm('Are you sure you want to cancel this booking? This action cannot be undone.')"
                            class="bg-red-600 text-white px-6 py-2 rounded-md hover:bg-red-700">
                        <i class="fas fa-times"></i> Cancel Booking
                    </button>
                </form>
            @endif
            
            @if($booking->status === 'completed' && !$booking->review)
                <a href="{{ route('reviews.create', $booking) }}" 
                   class="bg-yellow-500 text-white px-6 py-2 rounded-md hover:bg-yellow-600">
                    <i class="fas fa-star"></i> Write Review
                </a>
            @endif
        </div>
    </div>
</div>
@endsection
