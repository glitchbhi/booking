@extends('layouts.app')

@section('title', 'Browse Sports Grounds')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Location Filter Only -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-8">
        <form action="{{ route('grounds.browse') }}" method="GET" class="flex gap-4">
            <div class="flex-1">
                <label class="block text-sm font-medium text-gray-700 mb-2">Location</label>
                <div class="relative">
                    <i class="fas fa-map-marker-alt absolute left-3 top-3 text-gray-400"></i>
                    <input type="text" name="location" value="{{ request('location') }}" 
                           placeholder="Search by location..." 
                           class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-md focus:ring-green-500 focus:border-green-500">
                </div>
            </div>
            <div class="flex items-end gap-2">
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
    @if($trendingGrounds->count() > 0 && !request()->hasAny(['search', 'sport_type', 'min_price', 'max_price', 'min_rating', 'location']))
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
    @if($bestGrounds->count() > 0 && !request()->hasAny(['search', 'sport_type', 'min_price', 'max_price', 'min_rating', 'location']))
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
            @if(request()->hasAny(['search', 'sport_type', 'min_price', 'max_price', 'min_rating', 'location']))
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
