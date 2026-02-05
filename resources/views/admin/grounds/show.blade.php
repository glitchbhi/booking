@extends('layouts.app')

@section('title', 'Ground Details - ' . $ground->name)

@section('content')
<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="bg-white rounded-lg shadow-md p-6">
        <h1 class="text-2xl font-bold text-gray-900 mb-6">{{ $ground->name }}</h1>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div>
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Ground Information</h2>
                <div class="space-y-2">
                    <p class="text-gray-600"><strong>Owner:</strong> {{ $ground->owner->name }} ({{ $ground->owner->email }})</p>
                    <p class="text-gray-600"><strong>Sport:</strong> {{ $ground->sportType->name }}</p>
                    <p class="text-gray-600"><strong>Location:</strong> {{ $ground->location }}</p>
                    <p class="text-gray-600"><strong>Rate:</strong> {{ number_format($ground->rate_per_hour, 2) }} per hour</p>
                    <p class="text-gray-600"><strong>Status:</strong> 
                        <span class="px-2 py-1 text-xs rounded-full {{ $ground->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ $ground->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </p>
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
                    <p class="text-gray-600"><strong>Created:</strong> {{ $ground->created_at->format('M d, Y') }}</p>
                </div>
            </div>
        </div>

        <div class="flex space-x-4">
            <form action="{{ route('admin.grounds.toggle', $ground) }}" method="POST">
                @csrf
                <button type="submit" class="{{ $ground->is_active ? 'bg-red-600 hover:bg-red-700' : 'bg-green-600 hover:bg-green-700' }} text-white px-6 py-2 rounded-md">
                    <i class="fas {{ $ground->is_active ? 'fa-ban' : 'fa-check' }}"></i> {{ $ground->is_active ? 'Deactivate' : 'Activate' }} Ground
                </button>
            </form>
            <a href="{{ route('admin.grounds.index') }}" class="bg-gray-200 text-gray-700 px-6 py-2 rounded-md hover:bg-gray-300">
                <i class="fas fa-arrow-left"></i> Back
            </a>
        </div>
    </div>
</div>
@endsection
