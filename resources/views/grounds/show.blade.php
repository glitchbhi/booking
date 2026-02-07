@extends('layouts.app')

@section('title', $ground->name)

@section('content')
@php
    $groundImages = $ground->images && is_array($ground->images) && count($ground->images) > 0 
        ? array_slice($ground->images, 0, 4) 
        : [];
    $hasMultipleImages = count($groundImages) > 1;
    $hasImages = count($groundImages) > 0;
@endphp

<div class="max-w-full 2xl:max-w-[1920px] 3xl:max-w-[2560px] mx-auto px-4 sm:px-6 lg:px-8 xl:px-12 2xl:px-16 py-6">
    <!-- Breadcrumb -->
    <nav class="mb-6">
        <ol class="flex items-center space-x-2 text-sm">
            <li><a href="{{ route('welcome') }}" class="text-gray-500 hover:text-green-600"><i class="fas fa-home"></i></a></li>
            <li class="text-gray-400">/</li>
            <li><a href="{{ route('grounds.browse') }}" class="text-gray-500 hover:text-green-600">Grounds</a></li>
            <li class="text-gray-400">/</li>
            <li class="text-green-600 font-medium">{{ $ground->name }}</li>
        </ol>
    </nav>

    <div class="grid grid-cols-1 xl:grid-cols-3 gap-8">
        <!-- Main Content -->
        <div class="xl:col-span-2 space-y-6">
            <!-- Image Gallery / Carousel -->
            <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
                @if($hasMultipleImages)
                    <!-- Full Gallery Carousel for Multiple Images -->
                    <div x-data="{ 
                        currentSlide: 0, 
                        totalSlides: {{ count($groundImages) }},
                        fullscreen: false,
                        nextSlide() { this.currentSlide = (this.currentSlide + 1) % this.totalSlides; },
                        prevSlide() { this.currentSlide = (this.currentSlide - 1 + this.totalSlides) % this.totalSlides; }
                    }" class="relative">
                        <!-- Main Image -->
                        <div class="relative h-80 md:h-96 overflow-hidden">
                            @foreach($groundImages as $index => $image)
                                <div 
                                    x-show="currentSlide === {{ $index }}"
                                    x-transition:enter="transition ease-out duration-300"
                                    x-transition:enter-start="opacity-0"
                                    x-transition:enter-end="opacity-100"
                                    x-transition:leave="transition ease-in duration-200"
                                    x-transition:leave-start="opacity-100"
                                    x-transition:leave-end="opacity-0"
                                    class="absolute inset-0"
                                >
                                    <img 
                                        src="{{ asset('storage/' . $image) }}" 
                                        alt="{{ $ground->name }} - Image {{ $index + 1 }}" 
                                        class="w-full h-full object-cover cursor-pointer"
                                        @click="fullscreen = true"
                                    >
                                </div>
                            @endforeach
                            
                            <!-- Navigation Arrows -->
                            <button @click="prevSlide()" class="absolute left-4 top-1/2 -translate-y-1/2 bg-white/80 hover:bg-white text-gray-800 w-10 h-10 rounded-full flex items-center justify-center shadow-lg transition">
                                <i class="fas fa-chevron-left"></i>
                            </button>
                            <button @click="nextSlide()" class="absolute right-4 top-1/2 -translate-y-1/2 bg-white/80 hover:bg-white text-gray-800 w-10 h-10 rounded-full flex items-center justify-center shadow-lg transition">
                                <i class="fas fa-chevron-right"></i>
                            </button>
                            
                            <!-- Counter -->
                            <div class="absolute bottom-4 right-4 bg-black/60 text-white px-3 py-1 rounded-full text-sm">
                                <span x-text="(currentSlide + 1)"></span> / {{ count($groundImages) }}
                            </div>
                        </div>
                        
                        <!-- Thumbnail Strip -->
                        <div class="flex gap-2 p-4 bg-gray-50 overflow-x-auto">
                            @foreach($groundImages as $index => $image)
                                <button 
                                    @click="currentSlide = {{ $index }}"
                                    :class="currentSlide === {{ $index }} ? 'ring-2 ring-green-500 ring-offset-2' : 'opacity-60 hover:opacity-100'"
                                    class="flex-shrink-0 w-20 h-16 rounded-lg overflow-hidden transition"
                                >
                                    <img src="{{ asset('storage/' . $image) }}" alt="Thumbnail {{ $index + 1 }}" class="w-full h-full object-cover">
                                </button>
                            @endforeach
                        </div>
                        
                        <!-- Fullscreen Modal -->
                        <div 
                            x-show="fullscreen" 
                            x-cloak
                            @keydown.escape.window="fullscreen = false"
                            class="fixed inset-0 z-50 bg-black flex items-center justify-center"
                        >
                            <button @click="fullscreen = false" class="absolute top-4 right-4 text-white text-2xl hover:text-gray-300">
                                <i class="fas fa-times"></i>
                            </button>
                            <button @click="prevSlide()" class="absolute left-4 text-white text-3xl hover:text-gray-300">
                                <i class="fas fa-chevron-left"></i>
                            </button>
                            <button @click="nextSlide()" class="absolute right-4 text-white text-3xl hover:text-gray-300">
                                <i class="fas fa-chevron-right"></i>
                            </button>
                            @foreach($groundImages as $index => $image)
                                <img 
                                    x-show="currentSlide === {{ $index }}"
                                    src="{{ asset('storage/' . $image) }}" 
                                    alt="{{ $ground->name }}"
                                    class="max-w-full max-h-full object-contain"
                                >
                            @endforeach
                            <div class="absolute bottom-4 left-1/2 -translate-x-1/2 text-white">
                                <span x-text="(currentSlide + 1)"></span> / {{ count($groundImages) }}
                            </div>
                        </div>
                    </div>
                @elseif(count($groundImages) === 1)
                    <div class="h-80 md:h-96">
                        <img src="{{ asset('storage/' . $groundImages[0]) }}" alt="{{ $ground->name }}" class="w-full h-full object-cover">
                    </div>
                @else
                    <div class="h-80 md:h-96 bg-gradient-to-br from-gray-100 to-gray-200 flex flex-col items-center justify-center">
                        <i class="fas fa-image text-gray-400 text-6xl mb-4"></i>
                        <span class="text-gray-500 text-xl font-medium">No images available</span>
                    </div>
                @endif
            </div>

            <!-- Ground Info Card -->
            <div class="bg-white rounded-2xl shadow-lg p-6">
                <div class="flex flex-wrap gap-2 mb-4">
                    <span class="inline-flex items-center px-3 py-1 bg-green-100 text-green-700 text-sm font-medium rounded-full">
                        <i class="fas fa-running mr-1.5"></i> {{ $ground->sportType->name }}
                    </span>
                    @if($ground->capacity)
                        <span class="inline-flex items-center px-3 py-1 bg-blue-100 text-blue-700 text-sm font-medium rounded-full">
                            <i class="fas fa-users mr-1.5"></i> {{ $ground->capacity }}
                        </span>
                    @endif
                    @if($ground->night_rate_per_hour)
                        <span class="inline-flex items-center px-3 py-1 bg-indigo-100 text-indigo-700 text-sm font-medium rounded-full">
                            <i class="fas fa-moon mr-1.5"></i> Night Booking Available
                        </span>
                    @endif
                </div>

                <h1 class="text-2xl md:text-3xl font-bold text-gray-900 mb-2">{{ $ground->name }}</h1>
                
                <div class="flex items-center text-gray-600 mb-4">
                    <i class="fas fa-map-marker-alt text-green-500 mr-2"></i>
                    <span>{{ $ground->location }}</span>
                    @if($ground->address)
                        <span class="mx-2">•</span>
                        <span>{{ $ground->address }}</span>
                    @endif
                </div>

                <!-- Stats Row -->
                <div class="grid grid-cols-3 gap-4 py-4 border-y border-gray-100">
                    <div class="text-center">
                        <div class="flex items-center justify-center text-yellow-400 mb-1">
                            <i class="fas fa-star text-xl"></i>
                            <span class="ml-1 text-2xl font-bold text-gray-900">{{ number_format($ground->average_rating, 1) }}</span>
                        </div>
                        <p class="text-sm text-gray-500">{{ $ground->total_reviews }} reviews</p>
                        @auth
                            @if(auth()->user()->hasVerifiedEmail())
                                <a href="#reviews-section" class="inline-block mt-2 text-xs text-green-600 hover:text-green-700 font-medium">
                                    <i class="fas fa-eye mr-1"></i> View Ratings
                                </a>
                            @else
                                <span class="inline-block mt-2 text-xs text-yellow-600">
                                    <i class="fas fa-lock mr-1"></i> Verify email to view
                                </span>
                            @endif
                        @else
                            <a href="{{ route('login') }}" class="inline-block mt-2 text-xs text-gray-600 hover:text-gray-700 font-medium">
                                <i class="fas fa-sign-in-alt mr-1"></i> Login to view
                            </a>
                        @endauth
                    </div>
                    <div class="text-center border-x border-gray-100">
                        <div class="text-2xl font-bold text-gray-900 mb-1">{{ $ground->total_bookings }}</div>
                        <p class="text-sm text-gray-500">Bookings</p>
                    </div>
                    <div class="text-center">
                        <div class="text-2xl font-bold text-green-600 mb-1">Active</div>
                        <p class="text-sm text-gray-500">Status</p>
                    </div>
                </div>

                @if($ground->description)
                    <div class="mt-6">
                        <h2 class="text-lg font-bold text-gray-900 mb-2">About this Ground</h2>
                        <p class="text-gray-600 leading-relaxed">{{ $ground->description }}</p>
                    </div>
                @endif

                @if($ground->capacity_description)
                    <div class="mt-4 p-4 bg-blue-50 rounded-xl">
                        <h3 class="font-semibold text-blue-900 mb-1"><i class="fas fa-info-circle mr-1"></i> Capacity Info</h3>
                        <p class="text-blue-700 text-sm">{{ $ground->capacity_description }}</p>
                    </div>
                @endif
            </div>

            <!-- Pricing Card -->
            <div class="bg-white rounded-2xl shadow-lg p-6">
                <h2 class="text-lg font-bold text-gray-900 mb-4 flex items-center">
                    <i class="fas fa-tag text-green-500 mr-2"></i> Pricing
                </h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 2xl:grid-cols-2 gap-4 2xl:gap-6">
                    <!-- Day Rate -->
                    <div class="bg-gradient-to-br from-yellow-50 to-orange-50 rounded-xl p-4 border border-yellow-200">
                        <div class="flex items-center mb-2">
                            <div class="w-10 h-10 bg-yellow-400 rounded-full flex items-center justify-center mr-3">
                                <i class="fas fa-sun text-white"></i>
                            </div>
                            <div>
                                <h3 class="font-semibold text-gray-900">Day Rate</h3>
                                <p class="text-xs text-gray-500">
                                    {{ $ground->day_rate_start ? \Carbon\Carbon::parse($ground->day_rate_start)->format('g:i A') : '6:00 AM' }} - 
                                    {{ $ground->day_rate_end ? \Carbon\Carbon::parse($ground->day_rate_end)->format('g:i A') : '6:00 PM' }}
                                </p>
                            </div>
                        </div>
                        <div class="text-2xl font-bold text-gray-900">
                            BTN {{ number_format($ground->rate_per_hour, 0) }}
                            <span class="text-sm font-normal text-gray-500">/ hour</span>
                        </div>
                    </div>
                    
                    <!-- Night Rate -->
                    @if($ground->night_rate_per_hour)
                        <div class="bg-gradient-to-br from-indigo-50 to-purple-50 rounded-xl p-4 border border-indigo-200">
                            <div class="flex items-center mb-2">
                                <div class="w-10 h-10 bg-indigo-500 rounded-full flex items-center justify-center mr-3">
                                    <i class="fas fa-moon text-white"></i>
                                </div>
                                <div>
                                    <h3 class="font-semibold text-gray-900">Night Rate</h3>
                                    <p class="text-xs text-gray-500">
                                        {{ $ground->night_rate_start ? \Carbon\Carbon::parse($ground->night_rate_start)->format('g:i A') : '6:00 PM' }} - 
                                        {{ $ground->night_rate_end ? \Carbon\Carbon::parse($ground->night_rate_end)->format('g:i A') : '10:00 PM' }}
                                    </p>
                                </div>
                            </div>
                            <div class="text-2xl font-bold text-gray-900">
                                BTN {{ number_format($ground->night_rate_per_hour, 0) }}
                                <span class="text-sm font-normal text-gray-500">/ hour</span>
                            </div>
                        </div>
                    @else
                        <div class="bg-gray-50 rounded-xl p-4 border border-gray-200 flex items-center justify-center">
                            <div class="text-center text-gray-400">
                                <i class="fas fa-moon text-3xl mb-2"></i>
                                <p class="text-sm">Night booking not available</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Availability Schedule (30-minute slots with date selection) -->
            <div class="bg-white rounded-2xl shadow-lg p-4 sm:p-6" x-data="{ selectedDate: '{{ \Carbon\Carbon::today()->format('Y-m-d') }}' }">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-4">
                    <h2 class="text-base sm:text-lg font-bold text-gray-900 mb-3 sm:mb-0 flex items-center">
                        <i class="fas fa-clock text-green-500 mr-2"></i> Availability Schedule
                    </h2>
                    <div class="flex items-center space-x-2">
                        <label class="text-sm text-gray-600 font-medium">Select Date:</label>
                        <input 
                            type="date" 
                            x-model="selectedDate"
                            min="{{ \Carbon\Carbon::today()->format('Y-m-d') }}"
                            max="{{ \Carbon\Carbon::today()->addDays(30)->format('Y-m-d') }}"
                            class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent text-sm"
                        >
                    </div>
                </div>
                
                @php
                    $today = \Carbon\Carbon::today();
                    $now = \Carbon\Carbon::now();
                    $startHour = 6; // 6 AM
                    $endHour = 23; // 11 PM
                    
                    // Get bookings for next 30 days with user information
                    $upcomingBookings = $ground->bookings()
                        ->with('user')
                        ->whereIn('status', ['booked', 'ongoing'])
                        ->where('start_time', '>=', $today)
                        ->where('start_time', '<=', $today->copy()->addDays(30))
                        ->get()
                        ->groupBy(function($booking) {
                            return \Carbon\Carbon::parse($booking->start_time)->format('Y-m-d');
                        });
                @endphp
                
                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 xl:grid-cols-8 2xl:grid-cols-10 gap-2">
                    @for($day = 0; $day < 30; $day++)
                        @php
                            $checkDate = $today->copy()->addDays($day);
                            $dateKey = $checkDate->format('Y-m-d');
                            $dateBookings = $upcomingBookings->get($dateKey, collect([]));
                        @endphp
                        
                        <template x-if="selectedDate === '{{ $dateKey }}'">
                            <div class="col-span-full">
                                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 xl:grid-cols-8 2xl:grid-cols-10 gap-2">
                                    @for($hour = $startHour; $hour < $endHour; $hour++)
                                        @foreach([0, 30] as $minute)
                                            @php
                                                $slotStart = $checkDate->copy()->setHour($hour)->setMinute($minute)->setSecond(0);
                                                $slotEnd = $slotStart->copy()->addMinutes(30);
                                                
                                                // Skip slots that start before current time + 5 minutes
                                                if ($slotStart->lessThan(now()->addMinutes(5))) {
                                                    continue;
                                                }
                                                
                                                $bookedBy = null;
                                                $isBooked = $dateBookings->contains(function($booking) use ($slotStart, $slotEnd, &$bookedBy) {
                                                    $bookingStart = \Carbon\Carbon::parse($booking->start_time);
                                                    $bookingEnd = \Carbon\Carbon::parse($booking->end_time);
                                                    $overlaps = ($bookingStart < $slotEnd && $bookingEnd > $slotStart);
                                                    if ($overlaps) {
                                                        $bookedBy = $booking->user->name;
                                                    }
                                                    return $overlaps;
                                                });
                                            @endphp
                                            
                                            <div class="relative group">
                                                <div class="
                                                    px-2 py-2 sm:px-3 sm:py-3 rounded-lg text-center border-2 transition-all
                                                    {{ $isBooked ? 'bg-red-50 border-red-400 shadow-sm' : 'bg-green-50 border-green-400 hover:border-green-500 hover:shadow-md' }}
                                                ">
                                                    <div class="text-xs sm:text-sm font-bold {{ $isBooked ? 'text-red-700' : 'text-green-700' }}">
                                                        {{ $slotStart->format('g:i A') }}
                                                    </div>
                                                    <div class="text-xs mt-1 font-semibold {{ $isBooked ? 'text-red-600' : 'text-green-600' }}">
                                                        @if($isBooked)
                                                            <i class="fas fa-times-circle"></i> Booked
                                                        @else
                                                            <i class="fas fa-check-circle"></i> Free
                                                        @endif
                                                    </div>
                                                    @if($isBooked && $bookedBy)
                                                        <div class="text-xs mt-1 text-red-500 truncate">
                                                            {{ $bookedBy }}
                                                        </div>
                                                    @endif
                                                </div>
                                                
                                                <!-- Tooltip -->
                                                <div class="absolute bottom-full left-1/2 -translate-x-1/2 mb-2 hidden group-hover:block z-10 whitespace-nowrap">
                                                    <div class="bg-gray-900 text-white text-xs rounded-lg px-3 py-2 shadow-lg">
                                                        {{ $slotStart->format('g:i A') }} - {{ $slotEnd->format('g:i A') }}
                                                        <div class="text-xs font-semibold {{ $isBooked ? 'text-red-400' : 'text-green-400' }}">
                                                            {{ $isBooked ? '❌ Booked' : '✅ Available' }}
                                                        </div>
                                                        @if($isBooked && $bookedBy)
                                                            <div class="text-xs text-gray-300 mt-1">
                                                                <i class="fas fa-user mr-1"></i>Booked by: {{ $bookedBy }}
                                                            </div>
                                                        @endif
                                                        <div class="absolute top-full left-1/2 -translate-x-1/2 border-4 border-transparent border-t-gray-900"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    @endfor
                                </div>
                            </div>
                        </template>
                    @endfor
                </div>
                
                <div class="flex flex-wrap gap-3 sm:gap-4 mt-4 pt-4 border-t border-gray-100 text-xs sm:text-sm">
                    <div class="flex items-center">
                        <div class="w-4 h-4 bg-green-50 border-2 border-green-400 rounded mr-2"></div>
                        <span class="text-gray-600"><i class="fas fa-check-circle text-green-600 mr-1"></i>Available</span>
                    </div>
                    <div class="flex items-center">
                        <div class="w-4 h-4 bg-red-50 border-2 border-red-400 rounded mr-2"></div>
                        <span class="text-gray-600"><i class="fas fa-times-circle text-red-600 mr-1"></i>Booked</span>
                    </div>
                </div>
                
                <p class="text-xs text-gray-500 mt-3">
                    <i class="fas fa-info-circle"></i> Slots are shown in 30-minute intervals. Select a date above to view availability.
                </p>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="lg:col-span-1 space-y-6">
            <!-- Book Now Card -->
            <div class="bg-white rounded-2xl shadow-lg p-6">
                <div class="text-center mb-4">
                    <div class="text-sm text-gray-500 mb-1">Starting from</div>
                    <div class="text-3xl font-bold text-gray-900">BTN {{ number_format($ground->rate_per_hour, 0) }}</div>
                    <div class="text-sm text-gray-500">per hour</div>
                </div>
                
                @auth
                    @if(auth()->user()->canBook())
                        <a href="{{ route('bookings.create', $ground) }}" 
                           class="block w-full bg-green-600 hover:bg-green-700 text-white text-center py-4 rounded-xl font-semibold text-lg transition shadow-lg shadow-green-200">
                            <i class="fas fa-calendar-check mr-2"></i> Book Now
                        </a>
                    @else
                        <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl text-center">
                            <i class="fas fa-exclamation-triangle mr-1"></i> Account suspended
                        </div>
                    @endif
                @else
                    <a href="{{ route('login') }}" 
                       class="block w-full bg-green-600 hover:bg-green-700 text-white text-center py-4 rounded-xl font-semibold text-lg transition shadow-lg shadow-green-200">
                        <i class="fas fa-sign-in-alt mr-2"></i> Login to Book
                    </a>
                @endauth
            </div>

            <!-- Contact Info Card -->
            <div class="bg-white rounded-2xl shadow-lg p-6">
                <h3 class="font-bold text-gray-900 mb-4 flex items-center">
                    <i class="fas fa-address-book text-green-500 mr-2"></i> Contact Information
                </h3>
                <div class="flex items-center mb-4">
                    <div class="w-14 h-14 bg-green-100 rounded-full flex items-center justify-center mr-4">
                        <span class="text-green-600 font-bold text-xl">{{ substr($ground->owner->name, 0, 1) }}</span>
                    </div>
                    <div>
                        <div class="font-semibold text-gray-900 text-lg">{{ $ground->owner->name }}</div>
                        <div class="text-sm text-gray-500">Ground Owner</div>
                    </div>
                </div>
                
                <!-- Contact Info -->
                <div class="space-y-3 border-t border-gray-100 pt-4">
                    @if($ground->phone)
                        <div class="flex items-center text-sm">
                            <i class="fas fa-phone text-green-500 w-5"></i>
                            <span class="text-gray-700 font-medium ml-2">{{ $ground->phone }}</span>
                        </div>
                        <a href="tel:{{ $ground->phone }}" 
                           class="flex items-center justify-center w-full bg-green-600 hover:bg-green-700 text-white py-3 rounded-xl font-medium transition">
                            <i class="fas fa-phone-alt mr-2"></i> Call Now
                        </a>
                    @else
                        <div class="flex items-center text-sm">
                            <i class="fas fa-envelope text-green-500 w-5"></i>
                            <span class="text-gray-600 ml-2">{{ $ground->owner->email }}</span>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Quick Facts -->
            <div class="bg-white rounded-2xl shadow-lg p-6">
                <h3 class="font-bold text-gray-900 mb-4 flex items-center">
                    <i class="fas fa-clipboard-list text-green-500 mr-2"></i> Quick Facts
                </h3>
                <ul class="space-y-3">
                    <li class="flex items-center text-sm">
                        <i class="fas fa-check-circle text-green-500 mr-3"></i>
                        <span class="text-gray-600">{{ $ground->sportType->name }} facility</span>
                    </li>
                    @if($ground->capacity)
                        <li class="flex items-center text-sm">
                            <i class="fas fa-check-circle text-green-500 mr-3"></i>
                            <span class="text-gray-600">{{ $ground->capacity }} capacity</span>
                        </li>
                    @endif
                    @if($ground->night_rate_per_hour)
                        <li class="flex items-center text-sm">
                            <i class="fas fa-check-circle text-green-500 mr-3"></i>
                            <span class="text-gray-600">Night lighting available</span>
                        </li>
                    @endif
                    <li class="flex items-center text-sm">
                        <i class="fas fa-check-circle text-green-500 mr-3"></i>
                        <span class="text-gray-600">{{ $ground->total_bookings }}+ successful bookings</span>
                    </li>
                    <li class="flex items-center text-sm">
                        <i class="fas fa-check-circle text-green-500 mr-3"></i>
                        <span class="text-gray-600">Instant confirmation</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Reviews Section -->
    <div class="mt-8" id="reviews-section">
        <div class="bg-white rounded-2xl shadow-lg p-6">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-xl font-bold text-gray-900 flex items-center">
                    <i class="fas fa-star text-yellow-400 mr-2"></i> 
                    Reviews & Ratings
                </h2>
                <div class="flex items-center">
                    <div class="text-3xl font-bold text-gray-900 mr-2">{{ number_format($ground->average_rating, 1) }}</div>
                    <div>
                        <div class="flex text-yellow-400">
                            @for($i = 1; $i <= 5; $i++)
                                @if($i <= floor($ground->average_rating))
                                    <i class="fas fa-star"></i>
                                @elseif($i - 0.5 <= $ground->average_rating)
                                    <i class="fas fa-star-half-alt"></i>
                                @else
                                    <i class="far fa-star"></i>
                                @endif
                            @endfor
                        </div>
                        <div class="text-xs text-gray-500 mt-1">{{ $ground->total_reviews }} reviews</div>
                    </div>
                </div>
            </div>

            @guest
                <div class="bg-gray-50 border border-gray-200 rounded-xl p-6 mb-6 text-center">
                    <i class="fas fa-lock text-gray-400 text-4xl mb-3"></i>
                    <p class="text-gray-600 mb-4">
                        Please login to view ratings and reviews
                    </p>
                    <a href="{{ route('login') }}" class="inline-block px-6 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg font-medium transition">
                        <i class="fas fa-sign-in-alt mr-2"></i> Login
                    </a>
                </div>
            @else
                @if(!auth()->user()->hasVerifiedEmail())
                    <div class="bg-yellow-50 border border-yellow-200 rounded-xl p-6 mb-6 text-center">
                        <i class="fas fa-envelope text-yellow-600 text-4xl mb-3"></i>
                        <p class="text-yellow-800 font-medium mb-2">Email Verification Required</p>
                        <p class="text-yellow-700 text-sm mb-4">
                            Please verify your email address to view and submit ratings
                        </p>
                        <form method="POST" action="{{ route('verification.send') }}" class="inline-block">
                            @csrf
                            <button type="submit" class="px-6 py-2 bg-yellow-600 hover:bg-yellow-700 text-white rounded-lg font-medium transition">
                                <i class="fas fa-paper-plane mr-2"></i> Resend Verification Email
                            </button>
                        </form>
                    </div>
                @else
                    @php
                        // Check if user has already reviewed this ground
                        $existingReview = auth()->user()->reviews()
                            ->where('ground_id', $ground->id)
                            ->first();
                    @endphp

                    @if($existingReview)
                    <div class="bg-blue-50 border border-blue-200 rounded-xl p-4 mb-6">
                        <h3 class="font-semibold text-blue-900 mb-3 flex items-center">
                            <i class="fas fa-info-circle mr-2"></i> Your Review
                        </h3>
                        <div class="flex items-center justify-between">
                            <div>
                                <div class="flex text-yellow-400 mb-2">
                                    @for($i = 1; $i <= 5; $i++)
                                        <i class="{{ $i <= $existingReview->rating ? 'fas' : 'far' }} fa-star"></i>
                                    @endfor
                                    <span class="ml-2 text-gray-600 text-sm">{{ $existingReview->rating }} / 5</span>
                                </div>
                                @if($existingReview->comment)
                                    <p class="text-gray-700 text-sm">{{ $existingReview->comment }}</p>
                                @endif
                                <p class="text-xs text-gray-500 mt-2">Submitted {{ $existingReview->created_at->diffForHumans() }}</p>
                            </div>
                            <form action="{{ route('reviews.destroy', $existingReview) }}" method="POST" 
                                  onsubmit="return confirm('Are you sure you want to delete your review? You can submit a new one after deleting.')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg font-medium transition flex items-center">
                                    <i class="fas fa-trash mr-2"></i> Delete & Re-rate
                                </button>
                            </form>
                        </div>
                    </div>
                @else
                    <div class="bg-green-50 border border-green-200 rounded-xl p-4 mb-6">
                        <h3 class="font-semibold text-green-900 mb-3 flex items-center">
                            <i class="fas fa-pen mr-2"></i> Rate This Ground
                        </h3>
                        
                        <form action="{{ route('reviews.store', $ground) }}" method="POST" 
                              x-data="{ rating: 0, hoveredRating: 0 }">
                            @csrf
                            
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Your Rating</label>
                                <div class="flex items-center space-x-2">
                                    @for($i = 1; $i <= 5; $i++)
                                        <button 
                                            type="button"
                                            @click="rating = {{ $i }}"
                                            @mouseenter="hoveredRating = {{ $i }}"
                                            @mouseleave="hoveredRating = 0"
                                            class="text-3xl focus:outline-none transition-colors"
                                        >
                                            <i :class="(hoveredRating >= {{ $i }} || (hoveredRating === 0 && rating >= {{ $i }})) ? 'fas fa-star text-yellow-400' : 'far fa-star text-gray-300'"></i>
                                        </button>
                                    @endfor
                                    <span x-show="rating > 0" class="ml-3 text-sm text-gray-600">
                                        <span x-text="rating"></span> / 5
                                    </span>
                                </div>
                                <input type="hidden" name="rating" :value="rating" required>
                            </div>

                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Your Review (Optional)</label>
                                <textarea 
                                    name="comment" 
                                    rows="3" 
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                                    placeholder="Share your experience with this ground..."
                                ></textarea>
                            </div>

                            <button 
                                type="submit"
                                :disabled="rating === 0"
                                :class="rating === 0 ? 'bg-gray-300 cursor-not-allowed' : 'bg-green-600 hover:bg-green-700'"
                                class="px-6 py-2 text-white rounded-lg font-medium transition"
                            >
                                <i class="fas fa-paper-plane mr-2"></i> Submit Review
                            </button>
                        </form>
                    </div>
                @endif

                <!-- Display Reviews -->
                @php
                    $reviews = $ground->reviews()->with('user')->latest()->paginate(5);
                @endphp

                @if($reviews->count() > 0)
                <div class="space-y-4">
                    @foreach($reviews as $review)
                        <div class="border-b border-gray-100 last:border-0 pb-4 last:pb-0">
                            <div class="flex items-start justify-between mb-2">
                                <div class="flex items-center">
                                    <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center mr-3">
                                        <i class="fas fa-user text-green-600"></i>
                                    </div>
                                    <div>
                                        <div class="font-semibold text-gray-900">{{ $review->user->name }}</div>
                                        <div class="text-xs text-gray-500">{{ $review->created_at->diffForHumans() }}</div>
                                    </div>
                                </div>
                                <div class="flex text-yellow-400">
                                    @for($i = 1; $i <= 5; $i++)
                                        <i class="{{ $i <= $review->rating ? 'fas' : 'far' }} fa-star text-sm"></i>
                                    @endfor
                                </div>
                            </div>
                            @if($review->comment)
                                <p class="text-gray-600 text-sm ml-13">{{ $review->comment }}</p>
                            @endif
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                @if($reviews->hasPages())
                    <div class="mt-6 pt-4 border-t border-gray-100">
                        {{ $reviews->links() }}
                    </div>
                @endif
            @else
                <div class="text-center py-8 text-gray-400">
                    <i class="fas fa-comment-slash text-4xl mb-3"></i>
                    <p>No reviews yet. Be the first to review this ground!</p>
                </div>
            @endif
            @endif
            @endguest
        </div>
    </div>
</div>
@endsection
