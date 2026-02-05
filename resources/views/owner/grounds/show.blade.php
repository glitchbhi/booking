@extends('layouts.app')

@section('title', $ground->name . ' - Details')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-900">{{ $ground->name }}</h1>
            <span class="px-3 py-1 text-sm rounded-full {{ $ground->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                {{ $ground->is_active ? 'Active' : 'Inactive' }}
            </span>
        </div>
        
        <!-- Image Gallery -->
        @if($ground->images && count($ground->images) > 0)
        <div class="mb-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Photos</h2>
            <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
                @foreach($ground->images as $image)
                    <div class="relative">
                        <img src="{{ asset('storage/' . $image) }}" alt="{{ $ground->name }}" 
                             class="h-32 w-full object-cover rounded-lg cursor-pointer hover:opacity-75"
                             onclick="window.open('{{ asset('storage/' . $image) }}', '_blank')">
                    </div>
                @endforeach
            </div>
        </div>
        @endif
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div>
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Ground Details</h2>
                <div class="space-y-2">
                    <p class="text-gray-600"><strong>Sport:</strong> {{ $ground->sportType->name }}</p>
                    <p class="text-gray-600"><strong>Location:</strong> {{ $ground->location }}</p>
                    @if($ground->address)
                        <p class="text-gray-600"><strong>Address:</strong> {{ $ground->address }}</p>
                    @endif
                    <p class="text-gray-600"><strong>Day Rate:</strong> BTN {{ number_format($ground->rate_per_hour, 2) }} per hour</p>
                    @if($ground->night_rate_per_hour)
                        <p class="text-gray-600"><strong>Night Rate:</strong> BTN {{ number_format($ground->night_rate_per_hour, 2) }} per hour</p>
                    @endif
                    @if($ground->description)
                        <p class="text-gray-600"><strong>Description:</strong> {{ $ground->description }}</p>
                    @endif
                </div>
            </div>

            <div>
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Statistics</h2>
                <div class="space-y-2">
                    <p class="text-gray-600"><strong>Total Bookings:</strong> {{ $ground->total_bookings }}</p>
                    <p class="text-gray-600"><strong>Average Rating:</strong> 
                        <i class="fas fa-star text-yellow-400"></i> {{ number_format($ground->average_rating, 1) }}/5
                    </p>
                    <p class="text-gray-600"><strong>Total Reviews:</strong> {{ $ground->total_reviews }}</p>
                </div>
            </div>
        </div>

        <div class="flex space-x-4">
            <a href="{{ route('owner.grounds.edit', $ground) }}" class="bg-blue-600 text-white px-6 py-2 rounded-md hover:bg-blue-700">
                <i class="fas fa-edit"></i> Edit
            </a>
            <a href="{{ route('owner.grounds.availability', $ground) }}" class="bg-green-600 text-white px-6 py-2 rounded-md hover:bg-green-700">
                <i class="fas fa-calendar"></i> Manage Availability
            </a>
            <a href="{{ route('owner.grounds.index') }}" class="bg-gray-200 text-gray-700 px-6 py-2 rounded-md hover:bg-gray-300">
                <i class="fas fa-arrow-left"></i> Back
            </a>
        </div>
    </div>
</div>
@endsection
