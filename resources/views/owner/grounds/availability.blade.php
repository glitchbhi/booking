@extends('layouts.app')

@section('title', 'Manage Availability - ' . $ground->name)

@section('content')
<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="bg-white rounded-lg shadow-md p-6">
        <h1 class="text-2xl font-bold text-gray-900 mb-6">Manage Availability - {{ $ground->name }}</h1>
        
        <form action="{{ route('owner.grounds.availability.update', $ground) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="space-y-4">
                @foreach(['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'] as $day)
                    @php
                        $availability = $ground->availabilities->where('day_of_week', $day)->first();
                    @endphp
                    <div class="border border-gray-200 rounded-md p-4">
                        <div class="flex items-center justify-between mb-3">
                            <label class="text-lg font-semibold text-gray-900">{{ $day }}</label>
                            <input type="checkbox" name="days[{{ $day }}][enabled]" value="1" 
                                   {{ $availability ? 'checked' : '' }}
                                   class="h-5 w-5 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm text-gray-700 mb-1">Start Time</label>
                                <input type="time" name="days[{{ $day }}][start_time]" 
                                       value="{{ $availability ? \Carbon\Carbon::parse($availability->start_time)->format('H:i') : '06:00' }}"
                                       class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500">
                            </div>
                            <div>
                                <label class="block text-sm text-gray-700 mb-1">End Time</label>
                                <input type="time" name="days[{{ $day }}][end_time]" 
                                       value="{{ $availability ? \Carbon\Carbon::parse($availability->end_time)->format('H:i') : '23:00' }}"
                                       class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500">
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-6 flex space-x-4">
                <button type="submit" class="flex-1 bg-indigo-600 text-white py-3 rounded-md hover:bg-indigo-700 font-semibold">
                    <i class="fas fa-save"></i> Save Availability
                </button>
                <a href="{{ route('owner.grounds.index') }}" 
                   class="flex-1 bg-gray-200 text-gray-700 py-3 rounded-md hover:bg-gray-300 text-center font-semibold">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
