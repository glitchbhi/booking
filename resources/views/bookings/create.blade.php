@extends('layouts.app')

@section('title', 'Book ' . $ground->name)

@section('content')
<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-4 sm:py-6">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 lg:gap-6">
        <!-- Main Booking Form -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-lg shadow-md p-4 sm:p-6">
                <h1 class="text-xl sm:text-2xl font-bold text-gray-900 mb-4 sm:mb-6">Book {{ $ground->name }}</h1>
                
                <form action="{{ route('bookings.store', $ground) }}" method="POST" id="bookingForm">
                    @csrf
                    
                    <div class="mb-4 sm:mb-6">
                        <h2 class="text-base sm:text-lg font-semibold text-gray-900 mb-2">Ground Details</h2>
                        <div class="bg-gray-50 rounded-md p-3 sm:p-4 space-y-1 text-sm sm:text-base">
                            <p class="text-gray-600"><strong>Location:</strong> {{ $ground->location }}</p>
                            <p class="text-gray-600"><strong>Sport:</strong> {{ $ground->sportType->name }}</p>
                            <p class="text-gray-600"><strong>Day Rate:</strong> BTN {{ number_format($ground->rate_per_hour, 0) }} per hour</p>
                            @if($ground->night_rate_per_hour)
                                <p class="text-gray-600"><strong>Night Rate:</strong> BTN {{ number_format($ground->night_rate_per_hour, 0) }} per hour</p>
                            @endif
                        </div>
                    </div>

                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Booking Date</label>
                            <input type="date" name="booking_date" id="bookingDate" required
                                   min="{{ now()->format('Y-m-d') }}"
                                   max="{{ now()->addDays(30)->format('Y-m-d') }}"
                                   class="w-full px-3 sm:px-4 py-2 border border-gray-300 rounded-md focus:ring-green-500 focus:border-green-500 text-sm sm:text-base">
                            @error('booking_date')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-xs text-gray-500">Bookings available for next 30 days only</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Booking Type</label>
                            <div class="flex gap-4 mb-4">
                                <label class="flex items-center cursor-pointer">
                                    <input type="radio" name="booking_type" value="hourly" id="hourlyBooking" checked onchange="toggleBookingType()" class="mr-2">
                                    <span class="text-sm sm:text-base">Hourly Booking</span>
                                </label>
                                <label class="flex items-center cursor-pointer">
                                    <input type="radio" name="booking_type" value="fullday" id="fulldayBooking" onchange="toggleBookingType()" class="mr-2">
                                    <span class="text-sm sm:text-base">Full Day (Tournament/Event)</span>
                                </label>
                            </div>
                        </div>

                        <div id="hourlyBookingSection">
                            <div class="grid grid-cols-2 gap-3 sm:gap-4 mb-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Start Time</label>
                                    <select name="start_time" id="startTime" required class="w-full px-3 sm:px-4 py-2 border border-gray-300 rounded-md focus:ring-green-500 focus:border-green-500 text-sm sm:text-base">
                                        <option value="">Select time</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        Duration (Hours) <span class="text-xs text-gray-500">(Min: 1 hour)</span>
                                    </label>
                                    <select name="duration" id="duration" required class="w-full px-3 sm:px-4 py-2 border border-gray-300 rounded-md focus:ring-green-500 focus:border-green-500 text-sm sm:text-base" onchange="calculateTotal()">
                                        <option value="1">1 Hour</option>
                                        <option value="1.5">1.5 Hours</option>
                                        <option value="2">2 Hours</option>
                                        <option value="2.5">2.5 Hours</option>
                                        <option value="3">3 Hours</option>
                                        <option value="4">4 Hours</option>
                                        <option value="5">5 Hours</option>
                                        <option value="6">6 Hours</option>
                                    </select>
                                    <p class="mt-1 text-xs text-gray-500">
                                        <i class="fas fa-info-circle"></i> Minimum booking is 1 hour
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div id="fulldayBookingSection" style="display:none;">
                            <div class="bg-blue-50 border border-blue-200 rounded-md p-3 sm:p-4 mb-4">
                                <p class="text-sm text-blue-800">
                                    <i class="fas fa-info-circle"></i> Full day booking is from 6:00 AM to 11:00 PM (17 hours)
                                </p>
                                <p class="text-sm text-green-700 font-semibold mt-2">
                                    <i class="fas fa-percentage"></i> Get 10% discount on full-day bookings!
                                </p>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Notes (Optional)</label>
                            <textarea name="notes" rows="3"
                                      class="w-full px-3 sm:px-4 py-2 border border-gray-300 rounded-md focus:ring-green-500 focus:border-green-500 text-sm sm:text-base"
                                      placeholder="Any special requirements or notes..."></textarea>
                        </div>

                        <div class="bg-green-50 rounded-md p-3 sm:p-4">
                            <h3 class="font-semibold text-gray-900 mb-2 text-sm sm:text-base">Booking Summary</h3>
                            <div class="space-y-1 text-gray-600 text-xs sm:text-sm">
                                <p><strong>Total Hours:</strong> <span id="totalHours">1</span></p>
                                <p><strong>Rate per Hour:</strong> BTN <span id="rateDisplay">{{ number_format($ground->rate_per_hour, 0) }}</span></p>
                                <p><strong>Subtotal:</strong> BTN <span id="subtotal">{{ number_format($ground->rate_per_hour, 0) }}</span></p>
                                <p id="discountRow" style="display:none;" class="text-green-700"><strong>Full Day Discount (10%):</strong> - BTN <span id="discount">0</span></p>
                                <p><strong>Base Amount:</strong> BTN <span id="baseAmount">{{ number_format($ground->rate_per_hour, 0) }}</span></p>
                                <p class="text-base sm:text-lg font-bold text-green-600 pt-2 border-t border-green-200 mt-2"><strong>Total Amount:</strong> BTN <span id="totalAmount">{{ number_format($ground->rate_per_hour, 0) }}</span></p>
                            </div>
                        </div>

                        <div class="bg-yellow-50 border border-yellow-200 rounded-md p-3 sm:p-4">
                            <p class="text-xs sm:text-sm text-yellow-800">
                                <i class="fas fa-info-circle"></i> <strong>Cancellation Policy:</strong> 
                                Cancel at least 4 hours before booking time for 98% refund. 
                                Cancellations within 4 hours will not be refunded and will count as a strike.
                            </p>
                        </div>
                    </div>

                    <div class="mt-4 sm:mt-6 flex flex-col sm:flex-row gap-3 sm:gap-4">
                        <button type="submit" 
                                class="flex-1 bg-green-600 text-white py-3 rounded-md hover:bg-green-700 font-semibold text-sm sm:text-base">
                            <i class="fas fa-check"></i> Confirm Booking
                        </button>
                        <a href="{{ route('grounds.show', $ground) }}" 
                           class="flex-1 bg-gray-200 text-gray-700 py-3 rounded-md hover:bg-gray-300 text-center font-semibold text-sm sm:text-base">
                            <i class="fas fa-times"></i> Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Availability Sidebar -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-lg shadow-md p-4 sticky top-4">
                <div class="flex items-center justify-between mb-3">
                    <h3 class="font-bold text-gray-900 flex items-center text-sm sm:text-base">
                        <i class="fas fa-calendar-check text-green-500 mr-2"></i> Today's Availability
                    </h3>
                    <button onclick="refreshAvailability()" class="text-xs bg-gray-100 hover:bg-gray-200 px-2 py-1 rounded text-gray-600">
                        <i class="fas fa-sync-alt mr-1"></i> Refresh
                    </button>
                </div>
                
                @php
                    $today = \Carbon\Carbon::today();
                    $now = \Carbon\Carbon::now();
                    $startHour = 6;
                    // Get end hour from ground's night_rate_end, default to 23 (11 PM)
                    $endHour = $ground->night_rate_end ? \Carbon\Carbon::parse($ground->night_rate_end)->hour : 23;
                    // If end time is midnight or early morning (0-5), extend to next day logic
                    if ($endHour < 6) {
                        $endHour = 24; // Allow until midnight
                    }
                    $todayBookings = $ground->bookings()
                        ->whereIn('status', ['booked', 'ongoing', 'pending', 'payment_submitted', 'waiting_approval'])
                        ->whereDate('start_time', '<=', $today)
                        ->whereDate('end_time', '>=', $today)
                        ->get();
                @endphp
                
                <div class="space-y-1.5 max-h-96 overflow-y-auto">
                        @for($hour = $startHour; $hour < $endHour; $hour++)
                            @foreach([0, 30] as $minute)
                                @php
                                    $slotStart = $today->copy()->setHour($hour)->setMinute($minute)->setSecond(0);
                                    $slotEnd = $slotStart->copy()->addMinutes(30);
                                
                                    // Skip if slot is in the past
                                    if ($slotEnd->isPast()) {
                                        continue;
                                    }
                                
                                    // Skip if slot ends after end hour
                                    if ($slotEnd->hour > $endHour) {
                                        continue;
                                    }
                                
                                    $isBooked = $todayBookings->contains(function($booking) use ($slotStart, $slotEnd) {
                                        // Only check for confirmed bookings (not pending ones)
                                        if (!in_array($booking->status, ['booked', 'ongoing'])) {
                                            return false;
                                        }
                                        $bookingStart = \Carbon\Carbon::parse($booking->start_time);
                                        $bookingEnd = \Carbon\Carbon::parse($booking->end_time);
                                        return ($bookingStart < $slotEnd && $bookingEnd > $slotStart);
                                    });
                                
                                    // Check if slot is under booking (pending status) - exclude expired bookings
                                    $isUnderBooking = $todayBookings->contains(function($booking) use ($slotStart, $slotEnd) {
                                        $bookingStart = \Carbon\Carbon::parse($booking->start_time);
                                        $bookingEnd = \Carbon\Carbon::parse($booking->end_time);
                                    
                                        // Skip expired bookings
                                        if ($booking->status === 'pending' && $booking->expires_at && \Carbon\Carbon::now()->gt($booking->expires_at)) {
                                            return false;
                                        }
                                    
                                        return (in_array($booking->status, ['pending', 'payment_submitted', 'waiting_approval']) && 
                                                $bookingStart <= $slotStart && $bookingEnd >= $slotStart);
                                    });
                                @endphp
                            
                            <div class="flex items-center justify-between px-3 py-2 rounded-lg text-xs sm:text-sm
                                {{ $isBooked ? 'bg-red-50 text-red-700' : ($isUnderBooking ? 'bg-yellow-50 text-yellow-700' : 'bg-green-50 text-green-700') }}">
                                <span class="font-medium">{{ $slotStart->format('g:i A') }} - {{ $slotEnd->format('g:i A') }}</span>
                                <span class="flex items-center">
                                    @if($isBooked)
                                        <i class="fas fa-times-circle text-xs mr-1"></i> Booked
                                    @elseif($isUnderBooking)
                                        @php
                                            // Find the booking for this slot to show remaining time
                                            $slotBooking = null;
                                            foreach($todayBookings as $booking) {
                                                $bookingStart = \Carbon\Carbon::parse($booking->start_time);
                                                $bookingEnd = \Carbon\Carbon::parse($booking->end_time);
                                                
                                                if (in_array($booking->status, ['pending', 'payment_submitted', 'waiting_approval']) && 
                                                    $bookingStart <= $slotStart && $bookingEnd >= $slotStart) {
                                                    
                                                    // Skip expired bookings
                                                    if ($booking->status === 'pending' && $booking->expires_at && \Carbon\Carbon::now()->gt($booking->expires_at)) {
                                                        continue;
                                                    }
                                                    
                                                    $slotBooking = $booking;
                                                    break;
                                                }
                                            }
                                            
                                            $timeRemaining = '';
                                            if ($slotBooking && $slotBooking->status === 'pending' && $slotBooking->expires_at) {
                                                $remaining = \Carbon\Carbon::now()->diffInSeconds(\Carbon\Carbon::parse($slotBooking->expires_at));
                                                if ($remaining > 0) {
                                                    $minutes = floor($remaining / 60);
                                                    $seconds = $remaining % 60;
                                                    $timeRemaining = ' (' . $minutes . 'm ' . $seconds . 's)';
                                                }
                                            }
                                        @endphp
                                        <i class="fas fa-clock text-xs mr-1"></i> Under Booking{{ $timeRemaining }}
                                    @else
                                        <i class="fas fa-check-circle text-xs mr-1"></i> Free
                                    @endif
                                </span>
                            </div>
                        @endforeach
                    @endfor
                </div>
                
                <div class="mt-4 pt-3 border-t border-gray-100 space-y-2 text-xs">
                    <div class="flex items-center">
                        <div class="w-3 h-3 bg-green-50 border border-green-300 rounded mr-2"></div>
                        <span class="text-gray-600">Available</span>
                    </div>
                    <div class="flex items-center">
                        <div class="w-3 h-3 bg-yellow-50 border border-yellow-300 rounded mr-2"></div>
                        <span class="text-gray-600">Under Booking</span>
                    </div>
                    <div class="flex items-center">
                        <div class="w-3 h-3 bg-red-50 border border-red-300 rounded mr-2"></div>
                        <span class="text-gray-600">Booked</span>
                    </div>
                </div>
                
                <p class="text-xs text-gray-500 mt-3">
                    <i class="fas fa-info-circle"></i> 30-minute time slots
                </p>
            </div>
        </div>
    </div>
</div>

<script>
    function toggleBookingType() {
        const isHourly = document.getElementById('hourlyBooking').checked;
        document.getElementById('hourlyBookingSection').style.display = isHourly ? 'block' : 'none';
        document.getElementById('fulldayBookingSection').style.display = isHourly ? 'none' : 'block';
        
        const startTimeField = document.getElementById('startTime');
        const durationField = document.getElementById('duration');
        
        if (!isHourly) {
            // Full day booking
            durationField.value = '17'; // Full day = 17 hours
            startTimeField.value = '06:00'; // Set default start time to 6 AM
            startTimeField.removeAttribute('required');
            durationField.removeAttribute('required');
        } else {
            // Hourly booking
            startTimeField.setAttribute('required', 'required');
            durationField.setAttribute('required', 'required');
        }
        calculateTotal();
    }

    // Update start time dropdown when date changes
    const groundNightRateEnd = '{{ $ground->night_rate_end ?? "23:00" }}';
    const groundNightRateStart = '{{ $ground->night_rate_start ?? "18:00" }}';
    const groundDayRateEnd = '{{ $ground->day_rate_end ?? "18:00" }}';
    
    function updateStartTimeDropdown() {
        const selectedDateStr = document.getElementById('bookingDate').value || '{{ now()->format("Y-m-d") }}';
        const now = new Date();

        // Parse selected date parts safely (avoid browser-dependent Date parsing)
        const parts = selectedDateStr.split('-').map(p => parseInt(p, 10));
        const selectedDateObj = (parts.length === 3) ? new Date(parts[0], parts[1] - 1, parts[2]) : new Date();

        const startHour = 6;
        const endHourStr = groundNightRateEnd.split(':')[0];
        let endHour = parseInt(endHourStr);
        
        // Ensure endHour is valid - if it's 0 (midnight), treat as 24
        if (isNaN(endHour) || endHour <= 0) {
            endHour = 24; // Midnight = 24:00
        } else if (endHour > 24) {
            endHour = 24; // Cap at midnight
        }

        const startTimeSelect = document.getElementById('startTime');
        const currentValue = startTimeSelect.value;
        startTimeSelect.innerHTML = '<option value="">Select time</option>';

        // Helper: format hh:mm into 12-hour label
        function format12(timeSlot) {
            const [hh, mm] = timeSlot.split(':').map(Number);
            const ampm = hh >= 12 ? 'PM' : 'AM';
            const hour12 = ((hh + 11) % 12) + 1; // convert 0->12
            return hour12 + ':' + String(mm).padStart(2, '0') + ' ' + ampm;
        }

        // Helper: create slot Date object for comparisons
        function makeSlotDate(hour, minute) {
            const d = new Date(selectedDateObj.getTime());
            d.setHours(hour, minute, 0, 0);
            return d;
        }

        // Only show times for the selected day (6 AM to closing time, never beyond 23:30)
        const maxHour = Math.min(endHour, 24);

        // For all dates, generate time slots from startHour to maxHour (same-day slots only)
        for (let h = startHour; h < maxHour; h++) {
            for (let m of ['00', '30']) {
                const timeSlot = String(h).padStart(2, '0') + ':' + m;

                // Skip past times only when the slot belongs to the same calendar day and selectedDate is today
                const slotDate = makeSlotDate(h, parseInt(m, 10));
                const isToday = selectedDateObj.toDateString() === now.toDateString();
                const slotCompare = new Date(slotDate.getTime());
                slotCompare.setMinutes(slotCompare.getMinutes() + 5); // 5 minute buffer
                if (isToday && slotCompare <= now) {
                    continue;
                }

                const option = document.createElement('option');
                option.value = timeSlot;
                option.textContent = format12(timeSlot);
                startTimeSelect.appendChild(option);
            }
        }

        // Don't add early-morning slots (next day) - keep times limited to selected booking date only
        // This prevents confusion and ensures all bookings are for the same day

        // Restore previous value if still valid
        if (startTimeSelect.querySelector('option[value="' + currentValue + '"]')) {
            startTimeSelect.value = currentValue;
        }

        calculateTotal();
    }
    
    document.getElementById('bookingDate').value = '{{ now()->format("Y-m-d") }}';
    
    // Populate start times on initial load
    updateStartTimeDropdown();
    
    // Listen for date changes
    document.getElementById('bookingDate').addEventListener('change', updateStartTimeDropdown);

    function calculateTotal() {
        const isFullDay = document.getElementById('fulldayBooking').checked;
        const dayRate = {{ $ground->rate_per_hour }};
        const nightRate = {{ $ground->night_rate_per_hour ?? $ground->rate_per_hour }};
        
        let totalHours, subtotal, discount = 0, baseAmount, currentRate;
        
        if (isFullDay) {
            totalHours = 17;
            currentRate = dayRate;
            subtotal = totalHours * dayRate;
            // Apply 10% discount for full-day bookings
            discount = subtotal * 0.10;
            baseAmount = subtotal - discount;
            // Show discount row
            document.getElementById('discountRow').style.display = 'block';
        } else {
            totalHours = parseFloat(document.getElementById('duration').value) || 1;
            // Calculate based on start time for day/night rate
            const startTime = document.getElementById('startTime').value;
            if (startTime) {
                const hour = parseInt(startTime.split(':')[0]);
                // Day rate: 6 AM - 6 PM (6-17), Night rate: 6 PM onwards (18-23)
                currentRate = hour >= 18 ? nightRate : dayRate;
                subtotal = totalHours * currentRate;
            } else {
                currentRate = dayRate;
                subtotal = totalHours * dayRate;
            }
            baseAmount = subtotal;
            // Hide discount row for hourly bookings
            document.getElementById('discountRow').style.display = 'none';
        }
        
        // The base amount already includes 3% admin commission (added in database)
        const platformFee = 0;
        const totalAmount = baseAmount;
        
        document.getElementById('totalHours').textContent = totalHours.toFixed(1);
        document.getElementById('rateDisplay').textContent = currentRate.toFixed(0);
        document.getElementById('subtotal').textContent = subtotal.toFixed(0);
        document.getElementById('discount').textContent = discount.toFixed(0);
        document.getElementById('baseAmount').textContent = baseAmount.toFixed(0);
        document.getElementById('totalAmount').textContent = totalAmount.toFixed(0);
    }
    
    // Set up form submission to combine date and time
    document.getElementById('bookingForm').addEventListener('submit', function(e) {
        const bookingDate = document.getElementById('bookingDate').value;
        const isFullDay = document.getElementById('fulldayBooking').checked;
        
        let startDateTime, durationHours;
        
        if (isFullDay) {
            startDateTime = bookingDate + ' 06:00:00';
            durationHours = 17;
        } else {
            const startTime = document.getElementById('startTime').value;
            if (!startTime) {
                e.preventDefault();
                alert('Please select a start time');
                return false;
            }
            startDateTime = bookingDate + ' ' + startTime + ':00';
            durationHours = parseFloat(document.getElementById('duration').value);
        }
        
        // Create hidden inputs for the actual form submission
        const hiddenStartTime = document.createElement('input');
        hiddenStartTime.type = 'hidden';
        hiddenStartTime.name = 'start_time';
        hiddenStartTime.value = startDateTime;
        this.appendChild(hiddenStartTime);
        
        const hiddenDuration = document.createElement('input');
        hiddenDuration.type = 'hidden';
        hiddenDuration.name = 'duration_hours';
        hiddenDuration.value = durationHours;
        this.appendChild(hiddenDuration);
        
        const hiddenDurationUnit = document.createElement('input');
        hiddenDurationUnit.type = 'hidden';
        hiddenDurationUnit.name = 'duration_unit';
        hiddenDurationUnit.value = 'hours';
        this.appendChild(hiddenDurationUnit);
    });
    
    calculateTotal();
    
    // Prevent any form submission on select changes
    document.getElementById('startTime').addEventListener('change', function(e) {
        e.preventDefault();
        e.stopPropagation();
        calculateTotal();
    });

    document.getElementById('duration').addEventListener('change', function(e) {
        e.preventDefault();
        e.stopPropagation();
        calculateTotal();
    });

    // Manual refresh function for availability
    function refreshAvailability() {
        location.reload();
    }

    
</script>
@endsection
