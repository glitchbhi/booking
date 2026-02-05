@extends('layouts.app')

@section('title', 'Home - Browse Sports Grounds')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Search and Filter Section -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-8">
        <form action="{{ route('home') }}" method="GET" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div class="md:col-span-2">
                    <input type="text" name="search" value="{{ request('search') }}" 
                           placeholder="Search by name or location..." 
                           class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500">
                </div>
                <div>
                    <select name="sport_type" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="">All Sports</option>
                        @foreach($sportsTypes as $sport)
                            <option value="{{ $sport->id }}" {{ request('sport_type') == $sport->id ? 'selected' : '' }}>
                                {{ $sport->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <select name="sort" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>Latest</option>
                        <option value="price_low" {{ request('sort') == 'price_low' ? 'selected' : '' }}>Price: Low to High</option>
                        <option value="price_high" {{ request('sort') == 'price_high' ? 'selected' : '' }}>Price: High to Low</option>
                        <option value="rating" {{ request('sort') == 'rating' ? 'selected' : '' }}>Highest Rated</option>
                    </select>
                </div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <input type="number" name="min_price" value="{{ request('min_price') }}" 
                           placeholder="Min Price" 
                           class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500">
                </div>
                <div>
                    <input type="number" name="max_price" value="{{ request('max_price') }}" 
                           placeholder="Max Price" 
                           class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500">
                </div>
                <div>
                    <input type="number" name="min_rating" value="{{ request('min_rating') }}" 
                           placeholder="Min Rating" min="1" max="5" step="0.5"
                           class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500">
                </div>
            </div>
            <div class="flex space-x-2">
                <button type="submit" class="bg-indigo-600 text-white px-6 py-2 rounded-md hover:bg-indigo-700">
                    <i class="fas fa-search"></i> Search
                </button>
                <a href="{{ route('home') }}" class="bg-gray-200 text-gray-700 px-6 py-2 rounded-md hover:bg-gray-300">
                    <i class="fas fa-redo"></i> Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Trending Grounds -->
    @if($trendingGrounds->count() > 0)
        <div class="mb-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-4">
                <i class="fas fa-fire text-orange-500"></i> Trending Grounds
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                @foreach($trendingGrounds as $ground)
                    <x-ground-card :ground="$ground" />
                @endforeach
            </div>
        </div>
    @endif

    <!-- Best Rated Grounds -->
    @if($bestGrounds->count() > 0)
        <div class="mb-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-4">
                <i class="fas fa-trophy text-yellow-500"></i> Best Rated Grounds
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                @foreach($bestGrounds as $ground)
                    <x-ground-card :ground="$ground" />
                @endforeach
            </div>
        </div>
    @endif

    <!-- All Grounds -->
    <div>
        <h2 class="text-2xl font-bold text-gray-900 mb-4">All Grounds</h2>
        @if($grounds->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                @foreach($grounds as $ground)
                    <x-ground-card :ground="$ground" />
                @endforeach
            </div>
            <div class="mt-6">
                {{ $grounds->links() }}
            </div>
        @else
            <div class="bg-white rounded-lg shadow-md p-8 text-center">
                <i class="fas fa-search text-gray-400 text-6xl mb-4"></i>
                <p class="text-gray-600 text-lg">No grounds found matching your criteria.</p>
            </div>
        @endif
    </div>
</div>
@endsection
