@extends('layouts.app')

@section('title', 'Write Review')

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="bg-white rounded-lg shadow-md p-6">
        <h1 class="text-2xl font-bold text-gray-900 mb-6">Write a Review</h1>
        
        <div class="bg-gray-50 rounded-md p-4 mb-6">
            <h3 class="font-semibold text-gray-900">{{ $booking->ground->name }}</h3>
            <p class="text-sm text-gray-600">{{ $booking->ground->location }}</p>
            <p class="text-sm text-gray-600">Booking Date: {{ $booking->start_time->format('M d, Y') }}</p>
        </div>

        <form action="{{ route('reviews.store', $booking) }}" method="POST">
            @csrf
            
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">Rating *</label>
                <div class="flex space-x-2" id="ratingStars">
                    @for($i = 1; $i <= 5; $i++)
                        <button type="button" onclick="setRating({{ $i }})" 
                                class="text-4xl text-gray-300 hover:text-yellow-400 focus:outline-none star-btn"
                                data-rating="{{ $i }}">
                            <i class="fas fa-star"></i>
                        </button>
                    @endfor
                </div>
                <input type="hidden" name="rating" id="ratingInput" required>
                @error('rating')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">Your Review (Optional)</label>
                <textarea name="comment" rows="5"
                          class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500"
                          placeholder="Share your experience with this ground...">{{ old('comment') }}</textarea>
                @error('comment')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex space-x-4">
                <button type="submit" class="flex-1 bg-indigo-600 text-white py-3 rounded-md hover:bg-indigo-700 font-semibold">
                    <i class="fas fa-paper-plane"></i> Submit Review
                </button>
                <a href="{{ route('bookings.show', $booking) }}" 
                   class="flex-1 bg-gray-200 text-gray-700 py-3 rounded-md hover:bg-gray-300 text-center font-semibold">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>

<script>
    function setRating(rating) {
        document.getElementById('ratingInput').value = rating;
        const stars = document.querySelectorAll('.star-btn');
        stars.forEach((star, index) => {
            if (index < rating) {
                star.classList.remove('text-gray-300');
                star.classList.add('text-yellow-400');
            } else {
                star.classList.remove('text-yellow-400');
                star.classList.add('text-gray-300');
            }
        });
    }
</script>
@endsection
