@extends('layouts.app')

@section('title', 'Manage My Grounds')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-900">My Grounds</h1>
        <a href="{{ route('owner.grounds.create') }}" class="bg-indigo-600 text-white px-6 py-3 rounded-md hover:bg-indigo-700">
            <i class="fas fa-plus"></i> Add New Ground
        </a>
    </div>
    
    @if($grounds->count() > 0)
        @php
            $hasInactiveGround = $grounds->where('is_active', false)->count() > 0;
        @endphp
        
        @if($hasInactiveGround)
            <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-6">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <i class="fas fa-clock text-yellow-400"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-yellow-700">
                            <strong>Pending Approval:</strong> You have {{ $grounds->where('is_active', false)->count() }} ground(s) waiting for admin approval. 
                            Once approved, they will be visible to users for booking.
                        </p>
                    </div>
                </div>
            </div>
        @endif

        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @foreach($grounds as $ground)
                <div class="bg-white rounded-lg shadow-md overflow-hidden">
                    <div class="h-48 bg-gradient-to-r from-indigo-500 to-purple-600 flex items-center justify-center relative">
                        @if($ground->images && count($ground->images) > 0)
                            <img src="{{ asset('storage/' . $ground->images[0]) }}" alt="{{ $ground->name }}" class="w-full h-full object-cover">
                        @else
                            <i class="fas fa-futbol text-white text-6xl"></i>
                        @endif
                        @if($ground->images && count($ground->images) > 1)
                            <span class="absolute bottom-2 right-2 bg-black bg-opacity-50 text-white text-xs px-2 py-1 rounded">
                                +{{ count($ground->images) - 1 }} more
                            </span>
                        @endif
                    </div>
                    <div class="p-4">
                        <h3 class="text-xl font-bold text-gray-900">{{ $ground->name }}</h3>
                        <p class="text-sm text-gray-600 mt-1">{{ $ground->location }}</p>
                        <p class="text-sm text-indigo-600 font-semibold mt-2">{{ $ground->sportType->name }}</p>
                        
                        <div class="flex items-center justify-between mt-3 pb-3 border-b">
                            <div>
                                <span class="text-2xl font-bold text-gray-900">BTN {{ number_format($ground->rate_per_hour, 2) }}</span>
                                <span class="text-sm text-gray-600">/hour</span>
                            </div>
                            <div class="flex gap-2">
                                <span class="px-3 py-1 text-xs rounded-full {{ $ground->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $ground->is_active ? 'Active' : 'Inactive' }}
                                </span>
                                @if($ground->is_under_maintenance)
                                    <span class="px-3 py-1 text-xs rounded-full bg-yellow-100 text-yellow-800">
                                        <i class="fas fa-tools"></i> Maintenance
                                    </span>
                                @endif
                            </div>
                        </div>
                        
                        <div class="flex items-center justify-between mt-3">
                            <div>
                                <p class="text-sm text-gray-600">
                                    <i class="fas fa-star text-yellow-400"></i> {{ number_format($ground->average_rating, 1) }}
                                </p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">{{ $ground->total_bookings }} bookings</p>
                            </div>
                        </div>
                        
                        <div class="mt-4 flex space-x-2">
                            <a href="{{ route('owner.grounds.show', $ground) }}" class="flex-1 bg-gray-200 text-gray-700 text-center py-2 rounded-md hover:bg-gray-300 text-sm">
                                View
                            </a>
                            <a href="{{ route('owner.grounds.edit', $ground) }}" class="flex-1 bg-blue-600 text-white text-center py-2 rounded-md hover:bg-blue-700 text-sm">
                                Edit
                            </a>
                            <a href="{{ route('owner.grounds.availability', $ground) }}" class="flex-1 bg-green-600 text-white text-center py-2 rounded-md hover:bg-green-700 text-sm">
                                Schedule
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="bg-white rounded-lg shadow-md p-8 text-center">
            <i class="fas fa-store text-gray-400 text-6xl mb-4"></i>
            <p class="text-gray-600 text-lg mb-4">You haven't added any grounds yet.</p>
            <a href="{{ route('owner.grounds.create') }}" class="inline-block bg-indigo-600 text-white px-6 py-3 rounded-md hover:bg-indigo-700">
                <i class="fas fa-plus"></i> Add Your First Ground
            </a>
        </div>
    @endif
</div>
@endsection
