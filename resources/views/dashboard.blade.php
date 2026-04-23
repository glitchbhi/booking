<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Welcome Section -->
            <div class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-2xl shadow-lg p-8 mb-8 text-white">
                <h1 class="text-3xl font-bold mb-2">Welcome, {{ auth()->user()->name }}!</h1>
                <p class="text-blue-100">Manage your bookings and share your feedback about grounds and our platform</p>
            </div>

            <!-- Quick Stats Grid -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <!-- Upcoming Bookings -->
                <div class="bg-white overflow-hidden shadow-lg sm:rounded-lg p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-600 text-sm font-medium">Quick Actions</p>
                            <p class="text-3xl font-bold text-gray-900 mt-2">{{ \App\Models\Booking::where('user_id', auth()->id())->count() }}</p>
                            <p class="text-gray-500 text-xs mt-1">Total Bookings</p>
                        </div>
                        <i class="fas fa-calendar-check text-4xl text-blue-500 opacity-20"></i>
                    </div>
                </div>

                <!-- Ground Reviews -->
                <div class="bg-white overflow-hidden shadow-lg sm:rounded-lg p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-600 text-sm font-medium">Ground Reviews</p>
                            <p class="text-3xl font-bold text-gray-900 mt-2">{{ \App\Models\Review::where('user_id', auth()->id())->count() }}</p>
                            <p class="text-gray-500 text-xs mt-1">Reviews submitted</p>
                        </div>
                        <i class="fas fa-star text-4xl text-yellow-500 opacity-20"></i>
                    </div>
                </div>

                <!-- System Rating Status -->
                <div class="bg-white overflow-hidden shadow-lg sm:rounded-lg p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-600 text-sm font-medium">System Rating</p>
                            @if(auth()->user()->systemRating)
                                <p class="text-3xl font-bold text-gray-900 mt-2">{{ auth()->user()->systemRating->rating }}/5</p>
                                <p class="text-gray-500 text-xs mt-1">Already rated</p>
                            @else
                                <p class="text-3xl font-bold text-gray-900 mt-2">-</p>
                                <p class="text-gray-500 text-xs mt-1">Not yet rated</p>
                            @endif
                        </div>
                        <i class="fas fa-comments text-4xl text-green-500 opacity-20"></i>
                    </div>
                </div>
            </div>

            <!-- Ratings Section -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- System Rating Card -->
                <div class="bg-white overflow-hidden shadow-lg sm:rounded-lg">
                    <div class="bg-gradient-to-r from-green-500 to-green-600 px-6 py-4 text-white">
                        <h3 class="text-lg font-bold flex items-center">
                            <i class="fas fa-comments mr-2"></i> Rate Our Platform
                        </h3>
                        <p class="text-green-100 text-sm mt-1">Share your experience with Thunder Booking</p>
                    </div>
                    <div class="p-6">
                        @if(auth()->user()->hasVerifiedEmail())
                            @if(auth()->user()->systemRating)
                                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-4">
                                    <p class="text-blue-900 font-medium text-sm">Your Rating</p>
                                    <div class="flex items-center mt-2">
                                        <div class="flex text-yellow-400">
                                            @for($i = 1; $i <= 5; $i++)
                                                <i class="{{ $i <= auth()->user()->systemRating->rating ? 'fas' : 'far' }} fa-star"></i>
                                            @endfor
                                        </div>
                                        <span class="ml-2 text-gray-700 font-semibold">{{ auth()->user()->systemRating->rating }}/5</span>
                                    </div>
                                    @if(auth()->user()->systemRating->comment)
                                        <p class="text-gray-700 text-sm mt-2">{{ auth()->user()->systemRating->comment }}</p>
                                    @endif
                                </div>
                                <div class="space-y-2">
                                    <form action="{{ route('system-ratings.destroy', auth()->user()->systemRating) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="w-full px-4 py-2 bg-yellow-500 hover:bg-yellow-600 text-white rounded-lg font-medium transition flex items-center justify-center">
                                            <i class="fas fa-edit mr-2"></i> Update Rating
                                        </button>
                                    </form>
                                    <a href="{{ route('system-ratings.index') }}" class="block w-full px-4 py-2 bg-green-500 hover:bg-green-600 text-white rounded-lg font-medium transition text-center">
                                        <i class="fas fa-eye mr-2"></i> View All Ratings
                                    </a>
                                </div>
                            @else
                                <p class="text-gray-600 mb-4">You haven't rated our platform yet. Your feedback helps us improve!</p>
                                <a href="{{ route('system-ratings.index') }}" class="w-full px-4 py-2 bg-green-500 hover:bg-green-600 text-white rounded-lg font-medium transition flex items-center justify-center">
                                    <i class="fas fa-star mr-2"></i> Rate Now
                                </a>
                            @endif
                        @else
                            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                                <p class="text-yellow-900 text-sm">
                                    <i class="fas fa-lock mr-2"></i>
                                    Please verify your email address to submit ratings
                                </p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Ground Reviews Card -->
                <div class="bg-white overflow-hidden shadow-lg sm:rounded-lg">
                    <div class="bg-gradient-to-r from-orange-500 to-orange-600 px-6 py-4 text-white">
                        <h3 class="text-lg font-bold flex items-center">
                            <i class="fas fa-star mr-2"></i> Rate Grounds
                        </h3>
                        <p class="text-orange-100 text-sm mt-1">Share your experience with sports grounds</p>
                    </div>
                    <div class="p-6">
                        @if(auth()->user()->hasVerifiedEmail())
                            @php
                                $userReviews = \App\Models\Review::where('user_id', auth()->id())->count();
                                $userBookings = \App\Models\Booking::where('user_id', auth()->id())->count();
                            @endphp
                            <div class="mb-4">
                                <p class="text-gray-700 font-medium">Your Ground Feedback</p>
                                <p class="text-3xl font-bold text-gray-900 mt-1">{{ $userReviews }}</p>
                                <p class="text-gray-500 text-sm">Reviews submitted</p>
                            </div>
                            @if($userBookings > 0)
                                <div class="space-y-2">
                                    <a href="{{ route('grounds.browse') }}" class="w-full px-4 py-2 bg-orange-500 hover:bg-orange-600 text-white rounded-lg font-medium transition flex items-center justify-center">
                                        <i class="fas fa-search mr-2"></i> Browse & Rate Grounds
                                    </a>
                                    @if($userReviews > 0)
                                        <a href="{{ route('bookings.index') }}" class="w-full px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white rounded-lg font-medium transition flex items-center justify-center">
                                            <i class="fas fa-history mr-2"></i> View My Reviews
                                        </a>
                                    @endif
                                </div>
                            @else
                                <p class="text-gray-600 text-sm">Book a ground first to leave reviews!</p>
                                <a href="{{ route('grounds.browse') }}" class="block mt-4 px-4 py-2 bg-orange-500 hover:bg-orange-600 text-white rounded-lg font-medium transition text-center">
                                    <i class="fas fa-map-marker-alt mr-2"></i> Browse Grounds
                                </a>
                            @endif
                        @else
                            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                                <p class="text-yellow-900 text-sm">
                                    <i class="fas fa-lock mr-2"></i>
                                    Please verify your email address to submit reviews
                                </p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Recent Activity -->
            <div class="mt-8 bg-white overflow-hidden shadow-lg sm:rounded-lg">
                <div class="bg-gradient-to-r from-purple-500 to-purple-600 px-6 py-4 text-white">
                    <h3 class="text-lg font-bold flex items-center">
                        <i class="fas fa-chart-bar mr-2"></i> Platform Overview
                    </h3>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- System Rating Stats -->
                        <div>
                            <p class="text-gray-600 font-medium mb-3">Overall System Rating</p>
                            @php
                                $avgSystemRating = \App\Models\SystemRating::avg('rating') ?? 0;
                                $totalSystemRatings = \App\Models\SystemRating::count();
                            @endphp
                            <div class="flex items-center">
                                <div class="text-4xl font-bold text-yellow-500">{{ number_format($avgSystemRating, 1) }}</div>
                                <div class="ml-3">
                                    <div class="flex text-yellow-400">
                                        @for($i = 1; $i <= 5; $i++)
                                            @if($i <= floor($avgSystemRating))
                                                <i class="fas fa-star"></i>
                                            @elseif($i - 0.5 <= $avgSystemRating)
                                                <i class="fas fa-star-half-alt"></i>
                                            @else
                                                <i class="far fa-star"></i>
                                            @endif
                                        @endfor
                                    </div>
                                    <p class="text-gray-500 text-sm">{{ $totalSystemRatings }} {{ Str::plural('rating', $totalSystemRatings) }}</p>
                                </div>
                            </div>
                            <a href="{{ route('system-ratings.index') }}" class="text-green-600 hover:text-green-700 text-sm font-medium mt-3 flex items-center">
                                <i class="fas fa-arrow-right mr-1"></i> View All System Ratings
                            </a>
                        </div>

                        <!-- Platform Stats -->
                        <div>
                            <p class="text-gray-600 font-medium mb-3">Platform Statistics</p>
                            @php
                                $totalGrounds = \App\Models\Ground::count();
                                $totalUsers = \App\Models\User::count();
                                $totalBookings = \App\Models\Booking::count();
                            @endphp
                            <div class="space-y-2 text-sm">
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Total Grounds</span>
                                    <span class="font-semibold text-gray-900">{{ $totalGrounds }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Active Users</span>
                                    <span class="font-semibold text-gray-900">{{ $totalUsers }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Total Bookings</span>
                                    <span class="font-semibold text-gray-900">{{ $totalBookings }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Total Ground Reviews</span>
                                    <span class="font-semibold text-gray-900">{{ \App\Models\Review::count() }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
