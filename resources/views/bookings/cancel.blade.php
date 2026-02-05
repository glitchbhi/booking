@extends('layouts.app')

@section('title', 'Cancel Booking')

@section('content')
<div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="text-center mb-6">
            <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-exclamation-triangle text-red-600 text-2xl"></i>
            </div>
            <h1 class="text-2xl font-bold text-gray-900">Cancel Booking</h1>
            <p class="text-gray-600 mt-2">Are you sure you want to cancel this booking?</p>
        </div>

        <!-- Booking Details -->
        <div class="bg-gray-50 rounded-lg p-4 mb-6">
            <h3 class="font-semibold text-gray-900 mb-3">Booking Details</h3>
            <div class="space-y-2 text-sm">
                <div class="flex justify-between">
                    <span class="text-gray-600">Booking #:</span>
                    <span class="font-medium text-gray-900">{{ $booking->booking_number }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">Ground:</span>
                    <span class="font-medium text-gray-900">{{ $booking->ground->name }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">Date:</span>
                    <span class="font-medium text-gray-900">{{ $booking->start_time->format('M d, Y') }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">Time:</span>
                    <span class="font-medium text-gray-900">{{ $booking->start_time->format('h:i A') }} - {{ $booking->end_time->format('h:i A') }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">Amount:</span>
                    <span class="font-medium text-gray-900">{{ number_format($booking->total_amount, 2) }}</span>
                </div>
            </div>
        </div>

        <!-- Refund Info -->
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
            <div class="flex items-start">
                <i class="fas fa-info-circle text-blue-500 mt-1 mr-3"></i>
                <div>
                    <h4 class="font-semibold text-blue-900">Refund Policy</h4>
                    <p class="text-sm text-blue-800 mt-1">
                        If you cancel this booking, <strong>{{ number_format($booking->total_amount, 2) }}</strong> will be refunded to your wallet balance.
                    </p>
                </div>
            </div>
        </div>

        <!-- Cancel Form -->
        <form action="{{ route('bookings.destroy', $booking) }}" method="POST">
            @csrf
            
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">Reason for Cancellation (Optional)</label>
                <textarea name="reason" rows="3" 
                          class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-red-500 focus:border-red-500"
                          placeholder="Please let us know why you're cancelling..."></textarea>
            </div>

            <div class="flex space-x-4">
                <button type="submit" class="flex-1 bg-red-600 text-white py-3 rounded-md hover:bg-red-700 font-semibold">
                    <i class="fas fa-times-circle mr-2"></i> Yes, Cancel Booking
                </button>
                <a href="{{ route('bookings.show', $booking) }}" 
                   class="flex-1 bg-gray-200 text-gray-700 py-3 rounded-md hover:bg-gray-300 text-center font-semibold">
                    <i class="fas fa-arrow-left mr-2"></i> Go Back
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
