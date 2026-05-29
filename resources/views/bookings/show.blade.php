@extends('layouts.app')

@section('title', 'Booking Details')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex justify-between items-start mb-6">
            <h1 class="text-2xl font-bold text-gray-900">Booking Details</h1>
            @if($booking->status === 'pending' && $booking->expires_at && now()->gt($booking->expires_at))
                <span class="px-4 py-2 bg-red-100 text-red-800 rounded-full font-semibold">
                    <i class="fas fa-clock mr-1"></i> EXPIRED
                </span>
            @elseif($booking->status === 'pending')
                <span class="px-4 py-2 bg-yellow-100 text-yellow-800 rounded-full font-semibold">
                    <i class="fas fa-clock mr-1"></i> Pending Payment
                    @if($booking->expires_at)
                        <span class="text-xs ml-2" id="countdown-timer"></span>
                    @endif
                </span>
            @elseif($booking->status === 'payment_submitted')
                <span class="px-4 py-2 bg-purple-100 text-purple-800 rounded-full font-semibold">
                    <i class="fas fa-paper-plane mr-1"></i> Payment Submitted
                </span>
            @elseif($booking->status === 'waiting_approval')
                <span class="px-4 py-2 bg-indigo-100 text-indigo-800 rounded-full font-semibold">
                    <i class="fas fa-hourglass-half mr-1"></i> Waiting for Approval
                </span>
            @elseif($booking->status === 'booked')
                <span class="px-4 py-2 bg-green-100 text-green-800 rounded-full font-semibold">
                    <i class="fas fa-check-circle mr-1"></i> Confirmed
                </span>
            @elseif($booking->status === 'ongoing')
                <span class="px-4 py-2 bg-blue-100 text-blue-800 rounded-full font-semibold">Ongoing</span>
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
                    <span class="font-semibold">BTN {{ number_format($booking->ground->rate_per_hour * 1.03, 2) }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">Duration:</span>
                    <span class="font-semibold">{{ $booking->duration_hours }} hours</span>
                </div>
                <div class="flex justify-between text-lg font-bold">
                    <span>Total Amount:</span>
                    <span class="text-indigo-600">BTN {{ number_format($booking->total_amount * 1.03, 2) }}</span>
                </div>
                @if($booking->status === 'cancelled' && $booking->refund_amount > 0)
                    <div class="flex justify-between text-green-600">
                        <span>Refund Amount:</span>
                        <span class="font-bold">BTN {{ number_format($booking->refund_amount, 2) }}</span>
                    </div>
                @endif
            </div>
        </div>

        @if($booking->status === 'pending' && $booking->ground->account_number)
            <div class="mt-6 border-t pt-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Account Details to Transfer Payment</h2>
                <div class="bg-green-50 border border-green-200 rounded-lg p-4 space-y-4">
                    @if($booking->ground->bank_name)
                        <div class="flex items-center p-3 bg-white rounded border border-green-100">
                            <div>
                                <p class="text-xs text-gray-500 uppercase">Bank Name</p>
                                <p class="text-lg font-semibold text-gray-900">{{ $booking->ground->bank_name }}</p>
                            </div>
                        </div>
                    @endif
                    
                    <div class="flex items-center justify-between p-3 bg-white rounded border border-green-100">
                        <div>
                            <p class="text-xs text-gray-500 uppercase">Account Number</p>
                            <p class="text-lg font-semibold text-gray-900 font-mono">{{ $booking->ground->account_number }}</p>
                        </div>
                        <button type="button" onclick="copyToClipboard('{{ $booking->ground->account_number }}', this)"
                                class="px-3 py-2 bg-green-600 text-white rounded hover:bg-green-700 text-sm flex items-center gap-1 transition-all"
                                title="Copy account number">
                            <i class="fas fa-copy"></i> Copy
                        </button>
                    </div>
                    
                    <div class="bg-blue-100 border border-blue-300 rounded p-3">
                        <p class="text-sm text-blue-800">
                            <i class="fas fa-info-circle mr-2"></i>
                            <strong>Instructions:</strong> Transfer BTN {{ number_format($booking->total_amount, 2) }} to the above account, then upload the payment screenshot.
                        </p>
                    </div>
                </div>
            </div>
        @elseif($booking->status === 'pending')
            <div class="mt-6 border-t pt-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Account Details to Transfer Payment</h2>
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                    <p class="text-sm text-blue-800">
                        <i class="fas fa-info-circle mr-2"></i>
                        <strong>Note:</strong> The ground owner's bank account details will appear here soon. Please check back or refresh this page.
                    </p>
                </div>
            </div>
        @endif

        <div class="mt-6 border-t pt-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Payment Proof</h2>

            @if($booking->payment_proof)
                <div class="bg-gray-50 rounded-md p-4">
                    <div class="flex items-center justify-between mb-4">
                        <span class="text-gray-600 text-sm">
                            <i class="fas fa-check-circle text-green-600"></i> Payment screenshot uploaded
                        </span>
                        <a href="{{ asset('storage/' . $booking->payment_proof) }}"
                           target="_blank"
                           class="text-blue-600 hover:text-blue-800 text-sm">
                            <i class="fas fa-eye"></i> View Full Size
                        </a>
                    </div>
                    <img src="{{ asset('storage/' . $booking->payment_proof) }}"
                         alt="Payment Proof"
                         class="max-w-full h-auto rounded border cursor-pointer hover:opacity-75"
                         onclick="window.open(this.src, '_blank')">
                    <p class="text-xs text-gray-500 mt-2 text-center">Click image to view full size</p>
                </div>
            @elseif($booking->status === 'pending' && $booking->expires_at && now()->gt($booking->expires_at))
                <div class="bg-red-50 border border-red-200 rounded-md p-4">
                    <div class="text-center">
                        <div class="mb-4">
                            <i class="fas fa-clock text-red-600 text-4xl mb-2"></i>
                            <h3 class="text-lg font-semibold text-red-800 mb-2">Booking Expired</h3>
                            <p class="text-red-700 mb-4">
                                This booking has expired because payment proof was not uploaded within the 10-minute window.
                            </p>
                        </div>
                        
                        <div class="bg-red-100 border border-red-300 rounded-md p-3 mb-4">
                            <p class="text-sm text-red-800">
                                <i class="fas fa-info-circle mr-1"></i>
                                <strong>What happened?</strong> The time limit for uploading payment proof has passed. The slot is now available for booking again.
                            </p>
                        </div>
                        
                        <div class="space-y-3">
                            <a href="{{ route('grounds.show', $booking->ground) }}" 
                               class="w-full bg-green-600 text-white py-3 rounded-md hover:bg-green-700 font-semibold text-center block">
                                <i class="fas fa-redo mr-2"></i> Book This Slot Again
                            </a>
                            <p class="text-xs text-red-600">
                                <i class="fas fa-lightbulb mr-1"></i>
                                Tip: Be ready to upload your payment proof within 10 minutes when you book again.
                            </p>
                        </div>
                    </div>
                </div>
            @elseif($booking->status === 'pending')
                <div class="bg-yellow-50 border border-yellow-200 rounded-md p-4">
                    <form action="{{ route('bookings.upload-payment', $booking) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Upload Payment Screenshot *
                            </label>
                            <div class="flex items-center justify-center w-full">
                                <label class="flex flex-col items-center justify-center w-full h-32 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 hover:bg-gray-100">
                                    <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                        <svg class="w-8 h-8 mb-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                                        </svg>
                                        <p class="mb-2 text-sm text-gray-500">
                                            <span class="font-semibold">Click to upload</span> or drag and drop
                                        </p>
                                        <p class="text-xs text-gray-500">PNG, JPG, GIF up to 5MB</p>
                                    </div>
                                    <input id="payment_proof" name="payment_proof" type="file" class="hidden" accept="image/*" required />
                                </label>
                            </div>
                            <div id="payment-preview" class="mt-4"></div>
                            @error('payment_proof')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div class="bg-orange-100 border border-orange-300 rounded-md p-3 mb-4">
                            <p class="text-sm text-orange-800">
                                <i class="fas fa-exclamation-triangle mr-1"></i>
                                <strong>Time Remaining:</strong> You have <span id="upload-timer" class="font-bold"></span> to upload your payment proof.
                            </p>
                        </div>
                        
                        <button type="submit" class="w-full bg-green-600 text-white py-2 rounded-md hover:bg-green-700 font-semibold">
                            <i class="fas fa-upload mr-2"></i> Upload Payment Proof
                        </button>
                    </form>
                </div>
            @else
                <div class="bg-gray-50 border border-gray-200 rounded-md p-4 text-sm text-gray-600">
                    <i class="fas fa-info-circle"></i> No payment screenshot uploaded yet.
                </div>
            @endif
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

        <div class="mt-6 flex flex-wrap gap-2">
            <a href="{{ route('bookings.index') }}" class="bg-gray-200 text-gray-700 px-6 py-2 rounded-md hover:bg-gray-300">
                <i class="fas fa-arrow-left"></i> Back to Bookings
            </a>
            
            <button onclick="location.reload()" class="bg-blue-500 text-white px-6 py-2 rounded-md hover:bg-blue-600">
                <i class="fas fa-sync-alt"></i> Refresh Status
            </button>
            
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

<script>
// Countdown Timer
@if($booking->status === 'pending' && $booking->expires_at)
const expiresAt = new Date('{{ $booking->expires_at->toISOString() }}').getTime();
const countdownTimer = document.getElementById('countdown-timer');
const uploadTimer = document.getElementById('upload-timer');

function updateCountdown() {
    const now = new Date().getTime();
    const distance = expiresAt - now;
    
    if (distance < 0) {
        if (countdownTimer) countdownTimer.innerHTML = 'EXPIRED';
        if (uploadTimer) uploadTimer.innerHTML = 'EXPIRED';
        // Stop the countdown and show expired message
        clearInterval(countdownInterval);
        return;
    }
    
    const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
    const seconds = Math.floor((distance % (1000 * 60)) / 1000);
    
    const timeString = minutes + "m " + seconds + "s";
    if (countdownTimer) countdownTimer.innerHTML = '(' + timeString + ')';
    if (uploadTimer) uploadTimer.innerHTML = timeString;
}

// Update countdown every second
const countdownInterval = setInterval(updateCountdown, 1000);
updateCountdown(); // Initial call
@endif

// File Upload Preview
document.getElementById('payment_proof')?.addEventListener('change', function(e) {
    const file = e.target.files[0];
    const preview = document.getElementById('payment-preview');
    
    if (file) {
        // Check file size (5MB limit)
        if (file.size > 5 * 1024 * 1024) {
            alert('File size must be less than 5MB');
            e.target.value = '';
            return;
        }
        
        // Check file type
        if (!file.type.match('image.*')) {
            alert('Please select an image file');
            e.target.value = '';
            return;
        }
        
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.innerHTML = `
                <div class="relative inline-block">
                    <img src="${e.target.result}" alt="Payment Preview" class="max-w-xs h-auto rounded border">
                    <button type="button" onclick="clearFile()" class="absolute top-2 right-2 bg-red-500 text-white rounded-full p-1 hover:bg-red-600">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <p class="text-sm text-gray-600 mt-2">File: ${file.name}</p>
            `;
        };
        reader.readAsDataURL(file);
    }
});

function clearFile() {
    document.getElementById('payment_proof').value = '';
    document.getElementById('payment-preview').innerHTML = '';
}

// Copy to Clipboard Function
function copyToClipboard(text, button) {
    navigator.clipboard.writeText(text).then(() => {
        const originalHTML = button.innerHTML;
        button.innerHTML = '<i class="fas fa-check"></i> Copied!';
        button.classList.remove('bg-green-600', 'hover:bg-green-700');
        button.classList.add('bg-green-700');
        
        setTimeout(() => {
            button.innerHTML = originalHTML;
            button.classList.add('bg-green-600', 'hover:bg-green-700');
            button.classList.remove('bg-green-700');
        }, 2000);
    }).catch(err => {
        alert('Failed to copy. Please try again.');
    });
}
</script>
@endsection
