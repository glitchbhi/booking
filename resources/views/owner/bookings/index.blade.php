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
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Payment Proof</th>
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
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                @if($booking->payment_proof)
                                    <button onclick="showPaymentProof('{{ asset('storage/' . $booking->payment_proof) }}', '{{ $booking->booking_number }}')" 
                                            class="text-blue-600 hover:text-blue-800">
                                        <i class="fas fa-image"></i> View
                                    </button>
                                @else
                                    <span class="text-gray-400">No proof</span>
                                @endif
                            </td>
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

<!-- Payment Proof Modal -->
<div id="paymentProofModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full z-50" onclick="closePaymentProof(event)">
    <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-1/2 shadow-lg rounded-md bg-white" onclick="event.stopPropagation()">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-semibold text-gray-900">Payment Proof - <span id="modalBookingNumber"></span></h3>
            <button onclick="closePaymentProof()" class="text-gray-400 hover:text-gray-600">
                <i class="fas fa-times text-2xl"></i>
            </button>
        </div>
        <div class="mt-2">
            <img id="modalPaymentImage" src="" alt="Payment Proof" class="w-full h-auto rounded">
        </div>
        <div class="mt-4 flex justify-end gap-2">
            <a id="downloadButton" href="" download class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                <i class="fas fa-download"></i> Download
            </a>
            <button onclick="closePaymentProof()" class="bg-gray-300 text-gray-700 px-4 py-2 rounded hover:bg-gray-400">
                Close
            </button>
        </div>
    </div>
</div>

<script>
function showPaymentProof(imageUrl, bookingNumber) {
    document.getElementById('paymentProofModal').classList.remove('hidden');
    document.getElementById('modalPaymentImage').src = imageUrl;
    document.getElementById('modalBookingNumber').textContent = bookingNumber;
    document.getElementById('downloadButton').href = imageUrl;
    document.body.style.overflow = 'hidden';
}

function closePaymentProof(event) {
    if (!event || event.target.id === 'paymentProofModal' || event.type === 'click') {
        document.getElementById('paymentProofModal').classList.add('hidden');
        document.body.style.overflow = 'auto';
    }
}

// Close modal on Escape key
document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        closePaymentProof();
    }
});
</script>
@endsection
