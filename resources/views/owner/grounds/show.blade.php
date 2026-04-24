@extends('layouts.app')

@section('title', $ground->name . ' - Details')

@section('content')
<div class="max-w-7xl mx-auto px-3 sm:px-4 md:px-6 lg:px-8">
    <div class="bg-white rounded-lg sm:rounded-xl shadow-md p-3 sm:p-4 md:p-6">
        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-3 sm:gap-0 mb-4 sm:mb-6">
            <h1 class="text-xl sm:text-2xl font-bold text-gray-900">{{ $ground->name }}</h1>
            <div class="flex gap-1 sm:gap-2 flex-wrap">
                <span class="px-2 sm:px-3 py-1 text-xs sm:text-sm rounded-full {{ $ground->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                    {{ $ground->is_active ? 'Active' : 'Inactive' }}
                </span>
                @if($ground->is_under_maintenance)
                    <span class="px-2 sm:px-3 py-1 text-xs sm:text-sm rounded-full bg-yellow-100 text-yellow-800">
                        <i class="fas fa-tools text-xs sm:text-sm"></i> <span class="hidden sm:inline">Under Maintenance</span><span class="sm:hidden">Maintenance</span>
                    </span>
                @endif
            </div>
        </div>
        
        <!-- Image Gallery -->
        @if($ground->images && count($ground->images) > 0)
        <div class="mb-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Photos</h2>
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-2 sm:gap-3 md:gap-4">
                @foreach($ground->images as $image)
                    <div class="relative">
                        <img src="{{ asset('storage/' . $image) }}" alt="{{ $ground->name }}" 
                             class="h-24 sm:h-28 md:h-32 w-full object-cover rounded-lg cursor-pointer hover:opacity-75"
                             onclick="window.open('{{ asset('storage/' . $image) }}', '_blank')">
                    </div>
                @endforeach
            </div>
        </div>
        @endif
        
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-6 mb-4 sm:mb-6">
            <div>
                <h2 class="text-base sm:text-lg font-semibold text-gray-900 mb-3 sm:mb-4">Ground Details</h2>
                <div class="space-y-1 sm:space-y-2">
                    <p class="text-xs sm:text-sm text-gray-600"><strong>Sport:</strong> {{ $ground->sportType->name }}</p>
                    <p class="text-xs sm:text-sm text-gray-600"><strong>Location:</strong> {{ $ground->location }}</p>
                    @if($ground->address)
                        <p class="text-xs sm:text-sm text-gray-600"><strong>Address:</strong> {{ $ground->address }}</p>
                    @endif
                    <p class="text-xs sm:text-sm text-gray-600"><strong>Day Rate:</strong> BTN {{ number_format($ground->rate_per_hour, 2) }} per hour</p>
                    @if($ground->night_rate_per_hour)
                        <p class="text-xs sm:text-sm text-gray-600"><strong>Night Rate:</strong> BTN {{ number_format($ground->night_rate_per_hour, 2) }} per hour</p>
                    @endif
                    @if($ground->description)
                        <p class="text-xs sm:text-sm text-gray-600"><strong>Description:</strong> {{ $ground->description }}</p>
                    @endif
                </div>
            </div>

            <div>
                <h2 class="text-base sm:text-lg font-semibold text-gray-900 mb-3 sm:mb-4">Statistics</h2>
                <div class="space-y-1 sm:space-y-2">
                    <p class="text-xs sm:text-sm text-gray-600"><strong>Total Bookings:</strong> {{ $ground->total_bookings }}</p>
                    <p class="text-xs sm:text-sm text-gray-600"><strong>Average Rating:</strong> 
                        <i class="fas fa-star text-yellow-400 text-xs sm:text-sm"></i> {{ number_format($ground->average_rating, 1) }}/5
                    </p>
                    <p class="text-xs sm:text-sm text-gray-600"><strong>Total Reviews:</strong> {{ $ground->total_reviews }}</p>
                </div>
            </div>
        </div>

        <div class="flex flex-wrap gap-2 sm:gap-4">
            <a href="{{ route('owner.grounds.edit', $ground) }}" class="bg-blue-600 hover:bg-blue-700 text-white px-3 sm:px-4 md:px-6 py-2 rounded-md text-xs sm:text-sm">
                <i class="fas fa-edit text-xs sm:text-sm"></i> <span class="hidden sm:inline">Edit</span><span class="sm:hidden">Edit</span>
            </a>
            <a href="{{ route('owner.grounds.availability', $ground) }}" class="bg-green-600 hover:bg-green-700 text-white px-3 sm:px-4 md:px-6 py-2 rounded-md text-xs sm:text-sm">
                <i class="fas fa-calendar text-xs sm:text-sm"></i> <span class="hidden sm:inline">Manage Availability</span><span class="sm:hidden">Availability</span>
            </a>
            @if(!$ground->is_under_maintenance)
                <form action="{{ route('owner.grounds.toggle-maintenance', $ground) }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" class="bg-yellow-600 hover:bg-yellow-700 text-white px-3 sm:px-4 md:px-6 py-2 rounded-md text-xs sm:text-sm">
                        <i class="fas fa-tools text-xs sm:text-sm"></i> <span class="hidden sm:inline">Mark Under Maintenance</span><span class="sm:hidden">Maintenance</span>
                    </button>
                </form>
            @endif
            <a href="{{ route('owner.grounds.index') }}" class="bg-gray-200 text-gray-700 px-3 sm:px-4 md:px-6 py-2 rounded-md hover:bg-gray-300 text-xs sm:text-sm">
                <i class="fas fa-arrow-left text-xs sm:text-sm"></i> Back
            </a>
        </div>
    </div>
</div>
@endsection
