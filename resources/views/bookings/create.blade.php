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
                            <p class="text-gray-600"><strong>Day Rate:</strong> BTN {{ number_format($ground->rate_per_hour * 1.03, 0) }} per hour</p>
                            @if($ground->night_rate_per_hour)
                                <p class="text-gray-600"><strong>Night Rate:</strong> BTN {{ number_format($ground->night_rate_per_hour * 1.03, 0) }} per hour</p>
                            @endif
                            <p class="text-gray-600"><strong>Operating Hours:</strong> 
                                @php
                                    $openingTime = $ground->opening_time ? \Carbon\Carbon::parse($ground->opening_time)->format('H:i') : 'Not set';
                                    $closingTime = $ground->closing_time ? \Carbon\Carbon::parse($ground->closing_time)->format('H:i') : 'Not set';
                                @endphp
                                {{ $openingTime }} - {{ $closingTime }}
                            </p>
                        </div>
                    </div>

                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Booking Date</label>
                            <input type="date" name="booking_date" id="bookingDate" required
                                   value="{{ now()->format('Y-m-d') }}"
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
                                    <select name="start_time" id="startTime" required class="w-full px-3 sm:px-4 py-2 border border-gray-300 rounded-md focus:ring-green-500 focus:border-green-500 text-sm sm:text-base" onchange="updateEndTimeDropdown(); calculateTotal();">
                                        <option value="">Select time</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        End Time <span class="text-xs text-gray-500">(Min: 1 hour)</span>
                                    </label>
                                    <select name="end_time" id="endTime" required class="w-full px-3 sm:px-4 py-2 border border-gray-300 rounded-md focus:ring-green-500 focus:border-green-500 text-sm sm:text-base" onchange="updateStartTimeDropdown(); calculateTotal();">
                                        <option value="">Select time</option>
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
                                    <i class="fas fa-info-circle"></i> Full day booking is from {{ $ground->opening_time ? \Carbon\Carbon::parse($ground->opening_time)->format('g:i A') : '6:00 AM' }} to {{ $ground->closing_time ? \Carbon\Carbon::parse($ground->closing_time)->format('g:i A') : '11:00 PM' }} (entire operating hours)
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

                        <div class="bg-blue-50 rounded-md p-3 sm:p-4 mb-4 text-xs text-blue-800">
                            <strong>Ground Operating Hours (Set by Owner/Admin):</strong>
                            <br>
                            @php
                                $opHours = $ground->opening_time ? \Carbon\Carbon::parse($ground->opening_time)->format('H:i') : 'Not set';
                                $clHours = $ground->closing_time ? \Carbon\Carbon::parse($ground->closing_time)->format('H:i') : 'Not set';
                            @endphp
                            Opens: {{ $opHours }} | Closes: {{ $clHours }}
                        </div>

                        <div class="bg-green-50 rounded-md p-3 sm:p-4">
                            <h3 class="font-semibold text-gray-900 mb-2 text-sm sm:text-base">Booking Summary</h3>
                            <div class="space-y-1 text-gray-600 text-xs sm:text-sm">
                                <p><strong>Total Hours:</strong> <span id="totalHours">1</span></p>
                                <p>
                                    <strong>Rate per Hour:</strong> 
                                    <span class="font-semibold text-gray-900">BTN <span id="rateDisplay">{{ number_format($ground->rate_per_hour * 1.03, 0) }}</span></span>
                                    <span id="rateTypeIndicator" class="ml-2 inline-block px-2 py-0.5 rounded text-xs font-semibold bg-blue-100 text-blue-800">
                                        📅 Day Rate
                                    </span>
                                </p>
                                <p><strong>Subtotal:</strong> BTN <span id="subtotal">{{ number_format($ground->rate_per_hour * 1.03, 0) }}</span></p>
                                <p id="discountRow" style="display:none;" class="text-green-700"><strong>Full Day Discount (10%):</strong> - BTN <span id="discount">0</span></p>
                                <p><strong>Base Amount:</strong> BTN <span id="baseAmount">{{ number_format($ground->rate_per_hour * 1.03, 0) }}</span></p>
                                <p class="text-base sm:text-lg font-bold text-green-600 pt-2 border-t border-green-200 mt-2"><strong>Total Amount:</strong> BTN <span id="totalAmount">{{ number_format($ground->rate_per_hour * 1.03, 0) }}</span></p>
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
                    // Use opening_time and closing_time from ground - default to 6 AM - 10 PM if not set
                    $startHour = $ground->opening_time ? \Carbon\Carbon::parse($ground->opening_time)->hour : 6;
                    $startMinute = $ground->opening_time ? \Carbon\Carbon::parse($ground->opening_time)->minute : 0;
                    $endHour = $ground->closing_time ? \Carbon\Carbon::parse($ground->closing_time)->hour : 22;  // Changed default from 18 to 22 (10 PM)
                    $endMinute = $ground->closing_time ? \Carbon\Carbon::parse($ground->closing_time)->minute : 0;
                    $todayBookings = $ground->bookings()
                        ->whereIn('status', ['booked', 'ongoing', 'pending', 'payment_submitted', 'waiting_approval'])
                        ->whereDate('start_time', '<=', $today)
                        ->whereDate('end_time', '>=', $today)
                        ->get();
                @endphp
                
                <div class="space-y-1.5 max-h-96 overflow-y-auto">
                        @for($hour = $startHour; $hour <= $endHour; $hour++)
                            @foreach([0, 30] as $minute)
                                @php
                                    // Skip slots before opening time
                                    if ($hour === $startHour && $minute < $startMinute) {
                                        continue;
                                    }
                                    
                                    // Skip slots at or after closing time (day_rate_end)
                                    if ($hour > $endHour) {
                                        continue;
                                    }
                                    if ($hour === $endHour && $minute >= 0) {
                                        // Only allow :00 slot exactly at closing hour if there's no minute component
                                        if ($endMinute === 0 && $minute > 0) {
                                            continue;
                                        }
                                        // If closing hour has minute component, skip all slots after that
                                        if ($endMinute > 0 && $minute >= $endMinute) {
                                            continue;
                                        }
                                    }
                                    
                                    $slotStart = $today->copy()->setHour($hour)->setMinute($minute)->setSecond(0);
                                    $slotEnd = $slotStart->copy()->addMinutes(30);
                                
                                    // Skip if slot is in the past
                                    if ($slotEnd->isPast()) {
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
        const endTimeField = document.getElementById('endTime');
        
        if (!isHourly) {
            // Full day booking - use ground's actual opening and closing times
            // Build time strings from OPENING_HOUR/MINUTE and CLOSING_HOUR/MINUTE
            const startTimeStr = String(window.OPENING_HOUR || 6).padStart(2, '0') + ':' + String(window.OPENING_MINUTE || 0).padStart(2, '0');
            const endTimeStr = String(window.CLOSING_HOUR || 22).padStart(2, '0') + ':' + String(window.CLOSING_MINUTE || 0).padStart(2, '0');
            
            startTimeField.value = startTimeStr;
            endTimeField.value = endTimeStr;
            startTimeField.removeAttribute('required');
            endTimeField.removeAttribute('required');
        } else {
            // Hourly booking
            startTimeField.setAttribute('required', 'required');
            endTimeField.setAttribute('required', 'required');
            startTimeField.value = '';
            endTimeField.value = '';
        }
        calculateTotal();
    }

    // Update start time dropdown when date changes
    // Use owner/admin data for opening and closing times
    // CRITICAL FIX: Use opening_time and closing_time (actual operating hours), NOT day_rate_start/day_rate_end (pricing times)
    const groundOpeningTime = '{{ $ground->opening_time ? \Carbon\Carbon::parse($ground->opening_time)->format("H:i") : "06:00" }}';
    const groundClosingTime = '{{ $ground->closing_time ? \Carbon\Carbon::parse($ground->closing_time)->format("H:i") : "22:00" }}';
    
    // Pricing schedule times (for determining which rate applies)
    const groundDayRateStart = '{{ $ground->day_rate_start ? \Carbon\Carbon::parse($ground->day_rate_start)->format("H:i") : "06:00" }}';
    const groundDayRateEnd = '{{ $ground->day_rate_end ? \Carbon\Carbon::parse($ground->day_rate_end)->format("H:i") : "18:00" }}';
    const groundNightRateStart = '{{ $ground->night_rate_start ? \Carbon\Carbon::parse($ground->night_rate_start)->format("H:i") : "18:00" }}';
    const groundNightRateEnd = '{{ $ground->night_rate_end ? \Carbon\Carbon::parse($ground->night_rate_end)->format("H:i") : "22:00" }}';
    
    // Parse opening time and closing time (actual ground operating hours)
    // Declare these as globals (not const/let) so they're accessible to all functions
    const openingTimeArray = groundOpeningTime.split(':');
    window.OPENING_HOUR = parseInt(openingTimeArray[0], 10);
    window.OPENING_MINUTE = parseInt(openingTimeArray[1], 10) || 0;
    
    const closingTimeArray = groundClosingTime.split(':');
    window.CLOSING_HOUR = parseInt(closingTimeArray[0], 10);
    window.CLOSING_MINUTE = parseInt(closingTimeArray[1], 10) || 0;
    
    // Validate parsed values
    if (isNaN(window.OPENING_HOUR) || window.OPENING_HOUR < 0 || window.OPENING_HOUR > 23) {
        window.OPENING_HOUR = 6;
    }
    if (isNaN(window.OPENING_MINUTE) || window.OPENING_MINUTE < 0 || window.OPENING_MINUTE > 59) {
        window.OPENING_MINUTE = 0;
    }
    if (isNaN(window.CLOSING_HOUR) || window.CLOSING_HOUR < 0 || window.CLOSING_HOUR > 23) {
        window.CLOSING_HOUR = 22;  // Changed default from 18 to 22 (10 PM)
    }
    if (isNaN(window.CLOSING_MINUTE) || window.CLOSING_MINUTE < 0 || window.CLOSING_MINUTE > 59) {
        window.CLOSING_MINUTE = 0;
    }
    
    console.log('=== TIME CONFIG ===');
    console.log('Ground Name: {{ $ground->name }}');
    console.log('Operating Hours (when ground is open):');
    console.log('  Opening Time:', groundOpeningTime);
    console.log('  Closing Time:', groundClosingTime);
    console.log('Pricing Schedule (which rate applies when):');
    console.log('  Day Rate:', groundDayRateStart, '-', groundDayRateEnd);
    console.log('  Night Rate:', groundNightRateStart, '-', groundNightRateEnd);
    console.log('Parsed OPENING_HOUR:', window.OPENING_HOUR, 'OPENING_MINUTE:', window.OPENING_MINUTE);
    console.log('Parsed CLOSING_HOUR:', window.CLOSING_HOUR, 'CLOSING_MINUTE:', window.CLOSING_MINUTE);
    console.log('CRITICAL: Using ground operating hours, NOT pricing schedule times');
    
    // Ensure times are valid before proceeding
    if (isNaN(window.OPENING_HOUR) || isNaN(window.CLOSING_HOUR)) {
        console.error('ERROR: Could not parse ground opening/closing times!');
        console.error('OPENING_HOUR:', window.OPENING_HOUR, 'CLOSING_HOUR:', window.CLOSING_HOUR);
    }
    
    
    // Helper: format hh:mm into 12-hour label with only AM/PM (no Day/Night labels)
    function format12(timeSlot) {
        const [hh, mm] = timeSlot.split(':').map(Number);
        const ampm = hh >= 12 ? 'PM' : 'AM';
        const hour12 = ((hh + 11) % 12) + 1; // convert 0->12
        return hour12 + ':' + String(mm).padStart(2, '0') + ' ' + ampm;
    }
    
    // Helper: format end time without period labels (cleaner for end time dropdown)
    function formatEndTime(timeSlot) {
        const [hh, mm] = timeSlot.split(':').map(Number);
        const ampm = hh >= 12 ? 'PM' : 'AM';
        const hour12 = ((hh + 11) % 12) + 1;
        return hour12 + ':' + String(mm).padStart(2, '0') + ' ' + ampm;
    }
    
    function updateStartTimeDropdown() {
        const selectedDateStr = document.getElementById('bookingDate').value || '{{ now()->format("Y-m-d") }}';
        const endTimeSelected = document.getElementById('endTime').value;
        const now = new Date();

        // Parse selected date parts safely
        const parts = selectedDateStr.split('-').map(p => parseInt(p, 10));
        const selectedDateObj = (parts.length === 3) ? new Date(parts[0], parts[1] - 1, parts[2]) : new Date();

        const startTimeSelect = document.getElementById('startTime');
        if (!startTimeSelect) {
            console.error('ERROR: startTime select element not found!');
            return;
        }
        
        const currentValue = startTimeSelect.value;
        startTimeSelect.innerHTML = '<option value="">Select time</option>';

        // Helper: create slot Date object for comparisons
        function makeSlotDate(hour, minute) {
            const d = new Date(selectedDateObj.getTime());
            d.setHours(hour, minute, 0, 0);
            return d;
        }

        // Safety: Validate opening and closing hours
        const openingHour = (window.OPENING_HOUR && window.OPENING_HOUR >= 0 && window.OPENING_HOUR <= 23) ? window.OPENING_HOUR : 6;
        const closingHour = (window.CLOSING_HOUR && window.CLOSING_HOUR > 0 && window.CLOSING_HOUR <= 24) ? window.CLOSING_HOUR : 22;  // Default to 22 (10 PM) not 18
        let endTimeLimit = closingHour;
        
        if (endTimeSelected) {
            const endTimeParts = endTimeSelected.split(':');
            const endHour = parseInt(endTimeParts[0]);
            endTimeLimit = Math.min(endHour, closingHour);
        }

        console.log('updateStartTimeDropdown: openingHour=' + openingHour + ', endTimeLimit=' + endTimeLimit + ', closingHour=' + closingHour);

        // Safety: Add hard limit on iterations
        let slotsAdded = 0;
        const maxIterations = 40; // Max 40 slots (20 hours * 2 slots per hour)
        
        // Ensure opening hour is less than end time limit
        if (openingHour >= endTimeLimit) {
            console.warn('WARNING: Opening hour (' + openingHour + ') is >= end time limit (' + endTimeLimit + '). Using closing hour as limit.');
            endTimeLimit = closingHour;
        }
        
        for (let h = openingHour; h < endTimeLimit && slotsAdded < maxIterations; h++) {
            for (let m of ['00', '30']) {
                if (slotsAdded >= maxIterations) break;
                
                const timeSlot = String(h).padStart(2, '0') + ':' + m;

                // Skip past times
                const slotDate = makeSlotDate(h, parseInt(m));
                const isToday = selectedDateObj.toDateString() === now.toDateString();
                const slotCompare = new Date(slotDate.getTime());
                slotCompare.setMinutes(slotCompare.getMinutes() + 5);
                if (isToday && slotCompare <= now) {
                    continue;
                }

                const option = document.createElement('option');
                option.value = timeSlot;
                option.textContent = format12(timeSlot);
                startTimeSelect.appendChild(option);
                slotsAdded++;
            }
        }

        console.log('Start times generated:', slotsAdded, 'slots from', openingHour, 'to', endTimeLimit);

        // Restore previous value if still valid
        if (startTimeSelect.querySelector('option[value="' + currentValue + '"]')) {
            startTimeSelect.value = currentValue;
        }

        calculateTotal();
    }
    
    function updateEndTimeDropdown() {
        const selectedDateStr = document.getElementById('bookingDate').value || '{{ now()->format("Y-m-d") }}';
        const startTimeSelected = document.getElementById('startTime').value;
        const now = new Date();

        // Parse selected date parts safely
        const parts = selectedDateStr.split('-').map(p => parseInt(p, 10));
        const selectedDateObj = (parts.length === 3) ? new Date(parts[0], parts[1] - 1, parts[2]) : new Date();

        const endTimeSelect = document.getElementById('endTime');
        if (!endTimeSelect) {
            console.error('ERROR: endTime select element not found!');
            return;
        }
        
        const currentValue = endTimeSelect.value;
        endTimeSelect.innerHTML = '<option value="">Select time</option>';

        // Helper: create slot Date object for comparisons
        function makeSlotDate(hour, minute) {
            const d = new Date(selectedDateObj.getTime());
            d.setHours(hour, minute, 0, 0);
            return d;
        }

        // Safety: Validate closing hour
        const closingHour = (window.CLOSING_HOUR && window.CLOSING_HOUR > 0 && window.CLOSING_HOUR <= 24) ? window.CLOSING_HOUR : 22;  // Default to 22 (10 PM) not 18

        // Get start time limit - if start time is selected, generate options after it
        let startTimeLimit = window.OPENING_HOUR || 6; // Use opening hour as default
        if (startTimeSelected) {
            const startTimeParts = startTimeSelected.split(':');
            const startHour = parseInt(startTimeParts[0]);
            // End time must be at least 1 hour after start time
            startTimeLimit = startHour + 1;
        }

        console.log('updateEndTimeDropdown: startTimeLimit=' + startTimeLimit + ', closingHour=' + closingHour);

        // Safety: Add hard limit on iterations
        let slotsAdded = 0;
        const maxIterations = 40;
        
        for (let h = startTimeLimit; h <= closingHour && slotsAdded < maxIterations; h++) {
            for (let m of ['00', '30']) {
                if (slotsAdded >= maxIterations) break;
                
                // Don't add :30 at closing hour
                if (h === closingHour && m === '30') {
                    continue;
                }
                
                const timeSlot = String(h).padStart(2, '0') + ':' + m;

                // Skip past times
                const slotDate = makeSlotDate(h, parseInt(m));
                const isToday = selectedDateObj.toDateString() === now.toDateString();
                if (isToday && slotDate <= now) {
                    continue;
                }

                const option = document.createElement('option');
                option.value = timeSlot;
                option.textContent = formatEndTime(timeSlot);
                endTimeSelect.appendChild(option);
                slotsAdded++;
            }
        }

        console.log('End times generated:', slotsAdded, 'slots from', startTimeLimit, 'to', closingHour);

        // Restore previous value if still valid
        if (endTimeSelect.querySelector('option[value="' + currentValue + '"]')) {
            endTimeSelect.value = currentValue;
        }

        calculateTotal();
    }
    
    // Initialize booking form - handle both DOMContentLoaded and already-loaded DOM
    function initializeBookingForm() {
        try {
            console.log('=== INITIALIZING BOOKING FORM ===');
            
            // Set today's date as default
            const today = '{{ now()->format("Y-m-d") }}';
            const bookingDateInput = document.getElementById('bookingDate');
            
            if (bookingDateInput) {
                bookingDateInput.value = today;
                console.log('✓ Booking date set to:', today);
            } else {
                console.error('✗ bookingDate input element not found!');
            }
            
            // Populate start and end times on initial load
            console.log('Initializing time dropdowns...');
            try {
                updateStartTimeDropdown();
                console.log('✓ Start time dropdown initialized');
            } catch (e) {
                console.error('✗ Error updating start times:', e.message);
            }
            
            try {
                updateEndTimeDropdown();
                console.log('✓ End time dropdown initialized');
            } catch (e) {
                console.error('✗ Error updating end times:', e.message);
            }
            
            // Listen for date changes
            if (bookingDateInput) {
                bookingDateInput.addEventListener('change', function() {
                    console.log('Date changed to:', this.value);
                    updateStartTimeDropdown();
                    updateEndTimeDropdown();
                });
            }
            
            console.log('=== BOOKING FORM INITIALIZED ===');
        } catch (error) {
            console.error('Critical error initializing booking form:', error);
        }
    }
    
    // Check if document is already loaded
    if (document.readyState === 'loading') {
        // Document is still loading, wait for DOMContentLoaded
        document.addEventListener('DOMContentLoaded', initializeBookingForm);
    } else {
        // Document is already loaded, initialize immediately
        console.log('Document already loaded, initializing immediately...');
        initializeBookingForm();
    }
    
    // Add a fallback timeout in case initialization doesn't happen
    setTimeout(function() {
        const bookingDateInput = document.getElementById('bookingDate');
        if (bookingDateInput && !bookingDateInput.value) {
            console.log('Fallback: Setting date value...');
            const today = '{{ now()->format("Y-m-d") }}';
            bookingDateInput.value = today;
        }
        
        const startTimeSelect = document.getElementById('startTime');
        if (startTimeSelect && startTimeSelect.options.length <= 1) {
            console.log('Fallback: Re-initializing dropdowns...');
            try {
                updateStartTimeDropdown();
                updateEndTimeDropdown();
            } catch (e) {
                console.error('Fallback initialization error:', e);
            }
        }
    }, 500);

    function calculateTotal() {
        try {
            // Get DOM elements safely
            const fulldayCheckbox = document.getElementById('fulldayBooking');
            if (!fulldayCheckbox) {
                console.warn('Warning: fulldayBooking checkbox not found');
                return;
            }
            
            const isFullDay = fulldayCheckbox.checked;
            const dayRate = {{ $ground->rate_per_hour * 1.03 }};
            const nightRate = {{ ($ground->night_rate_per_hour ?? $ground->rate_per_hour) * 1.03 }};
            
            let totalHours, subtotal, discount = 0, baseAmount, currentRate;
            
            if (isFullDay) {
                totalHours = 17;
                currentRate = dayRate;
                subtotal = totalHours * dayRate;
                // Apply 10% discount for full-day bookings
                discount = subtotal * 0.10;
                baseAmount = subtotal - discount;
                // Show discount row
                const discountRow = document.getElementById('discountRow');
                if (discountRow) {
                    discountRow.style.display = 'block';
                }
            } else {
                // Calculate duration from start and end times
                const startTime = document.getElementById('startTime').value;
                const endTime = document.getElementById('endTime').value;
                
                if (startTime && endTime) {
                    const [startH, startM] = startTime.split(':').map(Number);
                    const [endH, endM] = endTime.split(':').map(Number);
                    
                    const startMinutes = startH * 60 + startM;
                    const endMinutes = endH * 60 + endM;
                    const durationMinutes = endMinutes - startMinutes;
                    
                    // Ensure minimum 1 hour booking
                    if (durationMinutes < 60) {
                        totalHours = 1;
                    } else {
                        totalHours = durationMinutes / 60;
                    }
                    
                    // Determine which rate applies based on pricing schedule
                    // Parse pricing schedule times
                    const dayRateStartH = parseInt(groundDayRateStart.split(':')[0]);
                    const dayRateEndH = parseInt(groundDayRateEnd.split(':')[0]);
                    const nightRateStartH = parseInt(groundNightRateStart.split(':')[0]);
                    const nightRateEndH = parseInt(groundNightRateEnd.split(':')[0]);
                    
                    // Determine rate based on start time
                    // If start time falls within day rate hours, use day rate
                    // If start time falls within night rate hours, use night rate
                    if (startH >= dayRateStartH && startH < dayRateEndH) {
                        // Within day rate hours
                        currentRate = dayRate;
                    } else if (startH >= nightRateStartH || startH < nightRateEndH) {
                        // Within night rate hours (handles midnight crossing)
                        currentRate = nightRate;
                    } else {
                        // Default to day rate if time doesn't fall in either period
                        currentRate = dayRate;
                    }

                    console.log('Rate Calculation: Start time', startH + ':' + startM, '-> Using rate:', currentRate === dayRate ? 'Day Rate' : 'Night Rate');
                    subtotal = totalHours * currentRate;
                } else {
                    totalHours = 1;
                    currentRate = dayRate;
                    subtotal = totalHours * dayRate;
                }

                baseAmount = subtotal;
                // Hide discount row for hourly bookings
                const discountRow = document.getElementById('discountRow');
                if (discountRow) {
                    discountRow.style.display = 'none';
                }
            }
            
            // The base amount already includes 3% admin commission (added in database)
            const platformFee = 0;
            const totalAmount = baseAmount;
            
            // Update DOM elements safely
            const totalHoursEl = document.getElementById('totalHours');
            const rateDisplayEl = document.getElementById('rateDisplay');
            const rateTypeEl = document.getElementById('rateTypeIndicator');
            const subtotalEl = document.getElementById('subtotal');
            const discountEl = document.getElementById('discount');
            const baseAmountEl = document.getElementById('baseAmount');
            const totalAmountEl = document.getElementById('totalAmount');
            
            if (totalHoursEl) totalHoursEl.textContent = totalHours.toFixed(1);
            if (rateDisplayEl) rateDisplayEl.textContent = currentRate.toFixed(0);
            
            // Update rate type indicator (Day Rate or Night Rate)
            if (rateTypeEl) {
                const isNightRate = currentRate === nightRate && nightRate !== dayRate;
                if (isNightRate) {
                    rateTypeEl.innerHTML = '🌙 Night Rate';
                    rateTypeEl.className = 'ml-2 inline-block px-2 py-0.5 rounded text-xs font-semibold bg-purple-100 text-purple-800';
                } else {
                    rateTypeEl.innerHTML = '📅 Day Rate';
                    rateTypeEl.className = 'ml-2 inline-block px-2 py-0.5 rounded text-xs font-semibold bg-blue-100 text-blue-800';
                }
            }
            
            if (subtotalEl) subtotalEl.textContent = subtotal.toFixed(0);
            if (discountEl) discountEl.textContent = discount.toFixed(0);
            if (baseAmountEl) baseAmountEl.textContent = baseAmount.toFixed(0);
            if (totalAmountEl) totalAmountEl.textContent = totalAmount.toFixed(0);
        } catch (error) {
            console.error('Error in calculateTotal:', error);
        }
    }
    
    // Set up form submission to combine date and time
    document.getElementById('bookingForm').addEventListener('submit', function(e) {
        const bookingDate = document.getElementById('bookingDate').value;
        const isFullDay = document.getElementById('fulldayBooking').checked;
        
        let startDateTime, endDateTime, durationHours;
        
        if (isFullDay) {
            startDateTime = bookingDate + ' 06:00:00';
            endDateTime = bookingDate + ' 23:00:00';
            durationHours = 17;
        } else {
            const startTime = document.getElementById('startTime').value;
            const endTime = document.getElementById('endTime').value;
            
            if (!startTime) {
                e.preventDefault();
                alert('Please select a start time');
                return false;
            }
            
            if (!endTime) {
                e.preventDefault();
                alert('Please select an end time');
                return false;
            }
            
            startDateTime = bookingDate + ' ' + startTime + ':00';
            endDateTime = bookingDate + ' ' + endTime + ':00';
            
            // Calculate duration
            const [startH, startM] = startTime.split(':').map(Number);
            const [endH, endM] = endTime.split(':').map(Number);
            const startMinutes = startH * 60 + startM;
            const endMinutes = endH * 60 + endM;
            const durationMinutes = endMinutes - startMinutes;
            durationHours = durationMinutes / 60;
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

    document.getElementById('endTime').addEventListener('change', function(e) {
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
