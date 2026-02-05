@extends('layouts.app')

@section('title', 'Edit Sport Type')

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="bg-white rounded-lg shadow-md p-6">
        <h1 class="text-2xl font-bold text-gray-900 mb-6">Edit Sport Type</h1>
        
        <form action="{{ route('admin.sports-types.update', $sportsType) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Sport Name *</label>
                    <input type="text" name="name" value="{{ old('name', $sportsType->name) }}" required
                           class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500">
                    @error('name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center">
                    <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', $sportsType->is_active) ? 'checked' : '' }}
                           class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                    <label for="is_active" class="ml-2 block text-sm text-gray-900">
                        Active (visible to owners)
                    </label>
                </div>

                <div class="bg-gray-50 rounded-md p-4">
                    <p class="text-sm text-gray-600">
                        <i class="fas fa-info-circle"></i> This sport type is currently used by <strong>{{ $sportsType->grounds->count() }}</strong> ground(s).
                    </p>
                </div>
            </div>

            <div class="mt-6 flex space-x-4">
                <button type="submit" class="flex-1 bg-indigo-600 text-white py-3 rounded-md hover:bg-indigo-700 font-semibold">
                    <i class="fas fa-save"></i> Update Sport Type
                </button>
                <a href="{{ route('admin.sports-types.index') }}" 
                   class="flex-1 bg-gray-200 text-gray-700 py-3 rounded-md hover:bg-gray-300 text-center font-semibold">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
