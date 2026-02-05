@extends('layouts.app')

@section('title', 'Browse Sports Grounds')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Search and Filter Section -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-8">
        <form action="{{ route('grounds.browse') }}" method="GET" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
                <div class="md:col-span-2">
                    <input type="text" name="search" value="{{ request('search') }}" 
                           placeholder="Search by name or location..." 
                           class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-green-500 focus:border-green-500">
                </div>
                <div>
                    <select name="sport_type" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-green-500 focus:border-green-500">
                        <option value="">All Sports</option>
                        @foreach($sportsTypes as $sport)
                            <option value="{{ $sport->id }}" {{ request('sport_type') == $sport->id ? 'selected' : '' }}>
                                {{ $sport->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <select name="capacity" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-green-500 focus:border-green-500">
                        <option value="">All Capacities</option>
                        <optgroup label="Football / Futsal">
                            <option value="5-a-side" {{ request('capacity') == '5-a-side' ? 'selected' : '' }}>5-a-side</option>
                            <option value="6-a-side" {{ request('capacity') == '6-a-side' ? 'selected' : '' }}>6-a-side</option>
                            <option value="7-a-side" {{ request('capacity') == '7-a-side' ? 'selected' : '' }}>7-a-side</option>
                            <option value="9-a-side" {{ request('capacity') == '9-a-side' ? 'selected' : '' }}>9-a-side</option>
                            <option value="11-a-side" {{ request('capacity') == '11-a-side' ? 'selected' : '' }}>11-a-side</option>
                        </optgroup>
                        <optgroup label="Cricket">
                            <option value="6-players" {{ request('capacity') == '6-players' ? 'selected' : '' }}>6 Players</option>
                            <option value="8-players" {{ request('capacity') == '8-players' ? 'selected' : '' }}>8 Players</option>
                            <option value="11-players" {{ request('capacity') == '11-players' ? 'selected' : '' }}>11 Players</option>
                        </optgroup>
                        <optgroup label="Badminton / Tennis">
                            <option value="singles" {{ request('capacity') == 'singles' ? 'selected' : '' }}>Singles</option>
                            <option value="doubles" {{ request('capacity') == 'doubles' ? 'selected' : '' }}>Doubles</option>
                        </optgroup>
                        <optgroup label="Basketball">
                            <option value="3v3" {{ request('capacity') == '3v3' ? 'selected' : '' }}>3v3</option>
                            <option value="5v5" {{ request('capacity') == '5v5' ? 'selected' : '' }}>5v5</option>
                        </optgroup>
                    </select>
                </div>
                <div>
                    <select name="sort" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-green-500 focus:border-green-500">
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
                           class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-green-500 focus:border-green-500">
                </div>
                <div>
                    <input type="number" name="max_price" value="{{ request('max_price') }}" 
                           placeholder="Max Price" 
                           class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-green-500 focus:border-green-500">
                </div>
                <div>
                    <input type="number" name="min_rating" value="{{ request('min_rating') }}" 
                           placeholder="Min Rating" min="1" max="5" step="0.5"
                           class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-green-500 focus:border-green-500">
                </div>
            </div>
            <div class="flex space-x-2">
                <button type="submit" class="bg-green-600 text-white px-6 py-2 rounded-md hover:bg-green-700">
                    <i class="fas fa-search"></i> Search
                </button>
                <a href="{{ route('grounds.browse') }}" class="bg-gray-200 text-gray-700 px-6 py-2 rounded-md hover:bg-gray-300">
                    <i class="fas fa-redo"></i> Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Trending Grounds -->
    @if($trendingGrounds->count() > 0 && !request()->hasAny(['search', 'sport_type', 'min_price', 'max_price', 'min_rating']))
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
    @if($bestGrounds->count() > 0 && !request()->hasAny(['search', 'sport_type', 'min_price', 'max_price', 'min_rating']))
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
        <h2 class="text-2xl font-bold text-gray-900 mb-4">
            @if(request()->hasAny(['search', 'sport_type', 'min_price', 'max_price', 'min_rating']))
                Search Results
            @else
                All Grounds
            @endif
        </h2>
        @if($grounds->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                @foreach($grounds as $ground)
                    <x-ground-card :ground="$ground" />
                @endforeach
            </div>
            <div class="mt-6">
                {{ $grounds->appends(request()->query())->links() }}
            </div>
        @else
            <div class="bg-white rounded-lg shadow-md p-8 text-center">
                <i class="fas fa-search text-gray-400 text-6xl mb-4"></i>
                <p class="text-gray-600 text-lg">No grounds found matching your criteria.</p>
                <a href="{{ route('grounds.browse') }}" class="mt-4 inline-block text-green-600 hover:text-green-700">
                    <i class="fas fa-arrow-left"></i> View All Grounds
                </a>
            </div>
        @endif
    </div>
</div>
@endsection
