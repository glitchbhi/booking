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
    
    <!-- Filter by Ground and Status -->
    <div class="bg-white rounded-lg shadow-md p-4 mb-6">
        <form action="{{ route('owner.bookings.index') }}" method="GET" class="flex flex-wrap gap-4">
            <select name="ground_id" class="flex-1 min-w-[200px] px-4 py-2 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500">
                <option value="">All Grounds</option>
                @foreach($ownerGrounds as $g)
                    <option value="{{ $g->id }}" {{ request('ground_id') == $g->id ? 'selected' : '' }}>
                        {{ $g->name }}
                    </option>
                @endforeach
            </select>
            
            <select name="status" class="flex-1 min-w-[200px] px-4 py-2 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500">
                <option value="">All Statuses</option>
                <option value="payment_submitted" {{ request('status') == 'payment_submitted' ? 'selected' : '' }}>
                    Payment Submitted
                </option>
                <option value="waiting_approval" {{ request('status') == 'waiting_approval' ? 'selected' : '' }}>
                    Waiting Approval
                </option>
                <option value="booked" {{ request('status') == 'booked' ? 'selected' : '' }}>
                    Confirmed
                </option>
                <option value="ongoing" {{ request('status') == 'ongoing' ? 'selected' : '' }}>
                    Ongoing
                </option>
                <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>
                    Completed
                </option>
                <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>
                    Cancelled
                </option>
            </select>
            
            <button type="submit" class="bg-indigo-600 text-white px-6 py-2 rounded-md hover:bg-indigo-700">
                Filter
            </button>
        </form>
    </div>

    @if($bookings->count() > 0)
        <div class="bg-white rounded-lg shadow-md overflow-x-auto">
            <table class="min-w-[800px] w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase">Booking #</th>
                        <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase">Ground</th>
                        <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase">User</th>
                        <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date & Time</th>
                        <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase">Duration</th>
                        <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase">Amount</th>
                        <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase">Payment</th>
                        <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                        <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase">Type</th>
                        <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($bookings as $booking)
                        <tr>
                            <td class="px-3 py-3 whitespace-nowrap text-sm font-medium">{{ $booking->booking_number }}</td>
                            <td class="px-3 py-3 whitespace-nowrap text-sm">{{ $booking->ground->name }}</td>
                            <td class="px-3 py-3 whitespace-nowrap text-sm">{{ $booking->user->name }}</td>
                            <td class="px-3 py-3 whitespace-nowrap text-sm">{{ $booking->start_time->format('M d, Y h:i A') }}</td>
                            <td class="px-3 py-3 whitespace-nowrap text-sm">{{ $booking->duration_hours }} hrs</td>
                            <td class="px-3 py-3 whitespace-nowrap text-sm font-semibold">BTN {{ number_format($booking->total_amount, 2) }}</td>
                            <td class="px-3 py-3 whitespace-nowrap text-sm">
                                @if($booking->payment_proof)
                                    <button onclick="showPaymentProof('{{ asset('storage/' . $booking->payment_proof) }}', '{{ $booking->booking_number }}')" 
                                            class="text-blue-600 hover:text-blue-800 text-xs">
                                        <i class="fas fa-image"></i> View
                                    </button>
                                @else
                                    <span class="text-gray-400 text-xs">No proof</span>
                                @endif
                            </td>
                            <td class="px-3 py-3 whitespace-nowrap">
                                @if($booking->status === 'payment_submitted')
                                    <span class="px-2 py-1 text-xs rounded-full bg-purple-100 text-purple-800">
                                        <i class="fas fa-paper-plane mr-1"></i> Payment Submitted
                                    </span>
                                @elseif($booking->status === 'waiting_approval')
                                    <span class="px-2 py-1 text-xs rounded-full bg-indigo-100 text-indigo-800">
                                        <i class="fas fa-hourglass-half mr-1"></i> Waiting Approval
                                    </span>
                                @elseif($booking->status === 'booked')
                                    <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800">
                                        <i class="fas fa-check-circle mr-1"></i> Confirmed
                                    </span>
                                @elseif($booking->status === 'ongoing')
                                    <span class="px-2 py-1 text-xs rounded-full bg-blue-100 text-blue-800">Ongoing</span>
                                @elseif($booking->status === 'completed')
                                    <span class="px-2 py-1 text-xs rounded-full bg-gray-100 text-gray-800">Completed</span>
                                @elseif($booking->status === 'cancelled')
                                    <span class="px-2 py-1 text-xs rounded-full bg-red-100 text-red-800">Cancelled</span>
                                @elseif($booking->status === 'expired')
                                    <span class="px-2 py-1 text-xs rounded-full bg-red-100 text-red-800">Expired</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 py-1 text-xs rounded-full {{ $booking->is_offline ? 'bg-orange-100 text-orange-800' : 'bg-green-100 text-green-800' }}">
                                    {{ $booking->is_offline ? 'Offline' : 'Online' }}
                                </span>
                            </td>
                            <td class="px-3 py-3 whitespace-nowrap">
                                @if($booking->status === 'waiting_approval')
                                    <div class="flex flex-col sm:flex-row gap-1 sm:gap-2">
                                        <button type="button" 
                                                onclick="showConfirmation('Are you sure you want to approve this booking?', () => document.getElementById('approveForm{{ $booking->id }}').submit())"
                                                class="bg-green-600 text-white px-2 py-1 sm:px-3 sm:py-1 rounded text-xs hover:bg-green-700 w-full sm:w-auto">
                                            <i class="fas fa-check mr-1"></i> Approve
                                        </button>
                                        <form id="approveForm{{ $booking->id }}" action="{{ route('owner.bookings.approve', $booking) }}" method="POST" class="hidden">
                                            @csrf
                                        </form>
                                        <button type="button" 
                                                onclick="showConfirmation('Are you sure you want to reject this booking?', () => document.getElementById('rejectForm{{ $booking->id }}').submit())"
                                                class="bg-red-600 text-white px-2 py-1 sm:px-3 sm:py-1 rounded text-xs hover:bg-red-700 w-full sm:w-auto">
                                            <i class="fas fa-times mr-1"></i> Reject
                                        </button>
                                        <form id="rejectForm{{ $booking->id }}" action="{{ route('owner.bookings.reject', $booking) }}" method="POST" class="hidden">
                                            @csrf
                                        </form>
                                    </div>
                                @else
                                    <span class="text-gray-400 text-xs">No actions</span>
                                @endif
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

<!-- Confirmation Modal -->
<div id="confirmationModal" class="fixed inset-0 bg-black bg-opacity-50 hidden flex items-center justify-center z-50" onclick="closeConfirmation(event)">
    <div class="bg-white rounded-lg shadow-xl p-6 max-w-md w-full mx-4" onclick="event.stopPropagation()">
        <p id="confirmationMessage" class="text-gray-700 text-center mb-6 text-base"></p>
        <div class="flex gap-3 justify-center">
            <button onclick="closeConfirmation()" class="px-6 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 font-medium">
                Cancel
            </button>
            <button id="confirmButton" onclick="confirmAction()" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-medium">
                OK
            </button>
        </div>
    </div>
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

let confirmationCallback = null;

function showConfirmation(message, callback) {
    confirmationCallback = callback;
    document.getElementById('confirmationMessage').textContent = message;
    document.getElementById('confirmationModal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closeConfirmation(event) {
    if (!event || event.target.id === 'confirmationModal' || event.type === 'click') {
        document.getElementById('confirmationModal').classList.add('hidden');
        document.body.style.overflow = 'auto';
        confirmationCallback = null;
    }
}

function confirmAction() {
    if (confirmationCallback) {
        confirmationCallback();
    }
    closeConfirmation();
}

// Close confirmation modal on Escape key
document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape' && !document.getElementById('confirmationModal').classList.contains('hidden')) {
        closeConfirmation();
    }
});
</script>
@endsection
