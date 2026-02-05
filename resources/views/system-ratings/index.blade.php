@extends('layouts.app')

@section('title', 'System Ratings - Thunder Booking')

@section('content')
<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-2">Thunder Booking System Ratings</h1>
        <p class="text-gray-600">See what our users think about our booking platform</p>
    </div>

    <!-- Overall Rating Card -->
    <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-2xl shadow-lg p-8 mb-8 text-white">
        <div class="flex flex-col md:flex-row items-center justify-between">
            <div class="text-center md:text-left mb-6 md:mb-0">
                <h2 class="text-2xl font-semibold mb-2">Overall System Rating</h2>
                <div class="flex items-center justify-center md:justify-start">
                    <div class="text-6xl font-bold mr-4">{{ number_format($averageRating, 1) }}</div>
                    <div>
                        <div class="flex text-yellow-300 text-2xl mb-2">
                            @for($i = 1; $i <= 5; $i++)
                                @if($i <= floor($averageRating))
                                    <i class="fas fa-star"></i>
                                @elseif($i - 0.5 <= $averageRating)
                                    <i class="fas fa-star-half-alt"></i>
                                @else
                                    <i class="far fa-star"></i>
                                @endif
                            @endfor
                        </div>
                        <p class="text-green-100">Based on {{ $totalRatings }} {{ Str::plural('rating', $totalRatings) }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-6">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-6">
            {{ session('error') }}
        </div>
    @endif

    <!-- User's Rating Section -->
    @if($userRating)
        <div class="bg-blue-50 border border-blue-200 rounded-xl p-6 mb-8">
            <h3 class="font-semibold text-blue-900 mb-3 flex items-center">
                <i class="fas fa-info-circle mr-2"></i> Your System Rating
            </h3>
            <div class="flex items-center justify-between">
                <div>
                    <div class="flex text-yellow-400 mb-2">
                        @for($i = 1; $i <= 5; $i++)
                            <i class="{{ $i <= $userRating->rating ? 'fas' : 'far' }} fa-star text-xl"></i>
                        @endfor
                        <span class="ml-2 text-gray-600 text-sm">{{ $userRating->rating }} / 5</span>
                    </div>
                    @if($userRating->comment)
                        <p class="text-gray-700 text-sm mb-2">{{ $userRating->comment }}</p>
                    @endif
                    <p class="text-xs text-gray-500">Submitted {{ $userRating->created_at->diffForHumans() }}</p>
                </div>
                <form action="{{ route('system-ratings.destroy', $userRating) }}" method="POST" 
                      onsubmit="return confirm('Are you sure you want to delete your rating?')">
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
        <div class="bg-white rounded-2xl shadow-lg p-6 mb-8">
            <h3 class="font-semibold text-gray-900 mb-4 flex items-center text-lg">
                <i class="fas fa-pen mr-2 text-green-600"></i> Rate Our System
            </h3>
            
            <form action="{{ route('system-ratings.store') }}" method="POST" 
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
                                class="text-4xl focus:outline-none transition-colors"
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
                    <label class="block text-sm font-medium text-gray-700 mb-2">Your Feedback (Optional)</label>
                    <textarea 
                        name="comment" 
                        rows="4" 
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                        placeholder="Share your experience with Thunder Booking system..."
                    ></textarea>
                </div>

                <button 
                    type="submit"
                    :disabled="rating === 0"
                    :class="rating === 0 ? 'bg-gray-300 cursor-not-allowed' : 'bg-green-600 hover:bg-green-700'"
                    class="px-6 py-3 text-white rounded-lg font-medium transition"
                >
                    <i class="fas fa-paper-plane mr-2"></i> Submit Rating
                </button>
            </form>
        </div>
    @endif

    <!-- All Ratings List -->
    <div class="bg-white rounded-2xl shadow-lg p-6">
        <h3 class="text-xl font-bold text-gray-900 mb-6 flex items-center">
            <i class="fas fa-comments mr-2 text-green-600"></i> User Feedback
        </h3>

        @if($ratings->count() > 0)
            <div class="space-y-6">
                @foreach($ratings as $rating)
                    <div class="border-b border-gray-100 last:border-0 pb-6 last:pb-0">
                        <div class="flex items-start justify-between mb-3">
                            <div class="flex items-center">
                                <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center mr-3">
                                    <i class="fas fa-user text-green-600 text-lg"></i>
                                </div>
                                <div>
                                    <div class="font-semibold text-gray-900">{{ $rating->user->name }}</div>
                                    <div class="text-xs text-gray-500">{{ $rating->created_at->diffForHumans() }}</div>
                                </div>
                            </div>
                            <div class="flex text-yellow-400">
                                @for($i = 1; $i <= 5; $i++)
                                    <i class="{{ $i <= $rating->rating ? 'fas' : 'far' }} fa-star"></i>
                                @endfor
                            </div>
                        </div>
                        @if($rating->comment)
                            <p class="text-gray-600 ml-15">{{ $rating->comment }}</p>
                        @endif
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            @if($ratings->hasPages())
                <div class="mt-6 pt-4 border-t border-gray-100">
                    {{ $ratings->links() }}
                </div>
            @endif
        @else
            <div class="text-center py-12">
                <i class="fas fa-star text-gray-300 text-6xl mb-4"></i>
                <p class="text-gray-500 text-lg">No ratings yet. Be the first to rate our system!</p>
            </div>
        @endif
    </div>
</div>
@endsection
