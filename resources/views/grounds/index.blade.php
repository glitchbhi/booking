@extends('layouts.app')

@section('title', 'Browse Grounds')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-900">Browse Sports Grounds</h1>
        <a href="{{ route('grounds.browse') }}" class="text-green-600 hover:text-green-700 font-medium">
            <i class="fas fa-filter"></i> Advanced Filters
        </a>
    </div>
    
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
            <p class="text-gray-600 text-lg">No grounds available at the moment.</p>
            <a href="{{ route('welcome') }}" class="mt-4 inline-block text-green-600 hover:text-green-700">
                <i class="fas fa-arrow-left"></i> Back to Home
            </a>
        </div>
    @endif
</div>
@endsection
