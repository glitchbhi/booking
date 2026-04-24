@extends('layouts.app')

@section('title', 'Ground Details - ' . $ground->name)

@section('content')
<div class="max-w-6xl mx-auto px-3 sm:px-4 md:px-6 lg:px-8">
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
        
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-6 mb-4 sm:mb-6">
            <div>
                <h2 class="text-base sm:text-lg font-semibold text-gray-900 mb-3 sm:mb-4">Ground Information</h2>
                <div class="space-y-1 sm:space-y-2">
                    <p class="text-xs sm:text-sm text-gray-600"><strong>Owner:</strong> {{ $ground->owner->name }} ({{ $ground->owner->email }})</p>
                    <p class="text-xs sm:text-sm text-gray-600"><strong>Sport:</strong> {{ $ground->sportType->name }}</p>
                    <p class="text-xs sm:text-sm text-gray-600"><strong>Location:</strong> {{ $ground->location }}</p>
                    <p class="text-xs sm:text-sm text-gray-600"><strong>Rate:</strong> BTN {{ number_format($ground->rate_per_hour, 2) }} per hour</p>
                    <p class="text-xs sm:text-sm text-gray-600"><strong>Status:</strong> 
                        <span class="px-2 py-1 text-xs rounded-full {{ $ground->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ $ground->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </p>
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
                    <p class="text-xs sm:text-sm text-gray-600"><strong>Created:</strong> {{ $ground->created_at->format('M d, Y') }}</p>
                </div>
            </div>
        </div>

        <div class="flex flex-wrap gap-2 sm:gap-4">
            <form action="{{ route('admin.grounds.toggle', $ground) }}" method="POST" class="inline">
                @csrf
                <button type="submit" class="{{ $ground->is_active ? 'bg-red-600 hover:bg-red-700' : 'bg-green-600 hover:bg-green-700' }} text-white px-3 sm:px-4 md:px-6 py-2 rounded-md text-xs sm:text-sm">
                    <i class="fas {{ $ground->is_active ? 'fa-ban' : 'fa-check' }} text-xs sm:text-sm"></i> 
                    <span class="hidden sm:inline">{{ $ground->is_active ? 'Deactivate' : 'Activate' }} Ground</span>
                    <span class="sm:hidden">{{ $ground->is_active ? 'Deactivate' : 'Activate' }}</span>
                </button>
            </form>
            <button onclick="openMaintenanceModal({{ $ground->id }})" 
                    class="{{ $ground->is_under_maintenance ? 'bg-green-600 hover:bg-green-700' : 'bg-yellow-600 hover:bg-yellow-700' }} text-white px-3 sm:px-4 md:px-6 py-2 rounded-md text-xs sm:text-sm">
                <i class="fas {{ $ground->is_under_maintenance ? 'fa-clock' : 'fa-tools' }} text-xs sm:text-sm"></i> 
                <span class="hidden sm:inline">{{ $ground->is_under_maintenance ? 'Update Maintenance' : 'Schedule Maintenance' }}</span>
                <span class="sm:hidden">{{ $ground->is_under_maintenance ? 'Update' : 'Schedule' }}</span>
            </button>
            <a href="{{ route('admin.grounds.index') }}" class="bg-gray-200 text-gray-700 px-3 sm:px-4 md:px-6 py-2 rounded-md hover:bg-gray-300 text-xs sm:text-sm">
                <i class="fas fa-arrow-left text-xs sm:text-sm"></i> Back
            </a>
        </div>

        <!-- Maintenance Information Section -->
        @if($ground->is_under_maintenance || $ground->maintenance_start_date)
            <div class="mt-4 sm:mt-6 p-3 sm:p-4 {{ $ground->is_under_maintenance ? 'bg-yellow-50 border-yellow-200' : 'bg-gray-50 border-gray-200' }} border rounded-lg">
                <h3 class="text-base sm:text-lg font-medium text-gray-900 mb-3">
                    <i class="fas fa-tools {{ $ground->is_under_maintenance ? 'text-yellow-600' : 'text-gray-600' }} mr-2 text-xs sm:text-sm"></i>
                    Maintenance Information
                </h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 sm:gap-4">
                    <div>
                        <p class="text-xs sm:text-sm text-gray-600">
                            <strong>Status:</strong>
                            <span class="ml-2 px-2 py-1 text-xs rounded-full {{ $ground->is_under_maintenance ? 'bg-yellow-100 text-yellow-800' : 'bg-green-100 text-green-800' }}">
                                {{ $ground->is_under_maintenance ? 'Currently Under Maintenance' : 'Scheduled' }}
                            </span>
                        </p>
                        @if($ground->maintenance_start_date)
                            <p class="text-xs sm:text-sm text-gray-600 mt-2">
                                <strong>Start:</strong> {{ $ground->maintenance_start_date->format('M d, Y h:i A') }}
                            </p>
                        @endif
                        @if($ground->maintenance_end_date)
                            <p class="text-xs sm:text-sm text-gray-600 mt-1">
                                <strong>End:</strong> {{ $ground->maintenance_end_date->format('M d, Y h:i A') }}
                            </p>
                            @if($ground->is_under_maintenance)
                                <p class="text-xs sm:text-sm text-gray-600 mt-1">
                                    <strong>Remaining:</strong> {{ $ground->getMaintenanceRemainingTime() }}
                                </p>
                            @endif
                        @endif
                    </div>
                    @if($ground->maintenance_reason)
                        <div>
                            <p class="text-xs sm:text-sm text-gray-600">
                                <strong>Reason:</strong><br>
                                <span class="text-gray-700 text-xs sm:text-sm">{{ $ground->maintenance_reason }}</span>
                            </p>
                        </div>
                    @endif
                </div>
            </div>
        @endif
    </div>

    <!-- Include Maintenance Modal -->
    @include('components.maintenance-modal', ['ground' => $ground])
    </div>
</div>
@endsection
