@extends('layouts.app')

@section('title', 'Add New Ground')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="bg-white rounded-lg shadow-md p-6">
        <h1 class="text-2xl font-bold text-gray-900 mb-6">Add New Ground</h1>
        
        <form action="{{ route('owner.grounds.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <div class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Ground Name *</label>
                        <input type="text" name="name" value="{{ old('name') }}" required
                               class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500"
                               placeholder="e.g., Changlimithang Football Ground">
                        @error('name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Sport Type *</label>
                        <select name="sport_type_id" required
                                class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500">
                            <option value="">Select Sport</option>
                            @foreach($sportsTypes as $sport)
                                <option value="{{ $sport->id }}" {{ old('sport_type_id') == $sport->id ? 'selected' : '' }}>
                                    {{ $sport->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('sport_type_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Location/City *</label>
                        <input type="text" name="location" value="{{ old('location') }}" required
                               class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-green-500 focus:border-green-500"
                               placeholder="e.g., Thimphu">
                        @error('location')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Full Address</label>
                        <input type="text" name="address" value="{{ old('address') }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-green-500 focus:border-green-500"
                               placeholder="e.g., Near Clock Tower, Thimphu">
                        @error('address')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Contact Phone Number *</label>
                    <div class="relative">
                        <i class="fas fa-phone absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                        <input type="tel" name="phone" value="{{ old('phone') }}" required
                               class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-md focus:ring-green-500 focus:border-green-500"
                               placeholder="e.g., 17123456">
                    </div>
                    <p class="mt-1 text-xs text-gray-500"><i class="fas fa-info-circle mr-1"></i> This number will be visible to users for booking inquiries</p>
                    @error('phone')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Day Rate per Hour (BTN) *</label>
                        <input type="number" name="rate_per_hour" value="{{ old('rate_per_hour') }}" min="0" step="0.01" required
                               class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-green-500 focus:border-green-500"
                               placeholder="e.g., 500">
                        @error('rate_per_hour')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Night Rate per Hour (BTN)</label>
                        <input type="number" name="night_rate_per_hour" value="{{ old('night_rate_per_hour') }}" min="0" step="0.01"
                               class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-green-500 focus:border-green-500"
                               placeholder="e.g., 700 (Leave empty if no night rate)">
                        @error('night_rate_per_hour')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Rate Timing Section -->
                <div class="bg-gray-50 rounded-lg p-4 space-y-4">
                    <h3 class="font-semibold text-gray-800 flex items-center">
                        <i class="fas fa-clock text-green-500 mr-2"></i> Rate Timing
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="bg-white rounded-lg p-4 border border-gray-200">
                            <label class="block text-sm font-medium text-gray-700 mb-3">
                                <i class="fas fa-sun text-yellow-500 mr-1"></i> Day Rate Hours
                            </label>
                            <div class="flex items-center space-x-2">
                                <input type="time" name="day_rate_start" value="{{ old('day_rate_start', '06:00') }}"
                                       class="flex-1 px-3 py-2 border border-gray-300 rounded-md focus:ring-green-500 focus:border-green-500">
                                <span class="text-gray-500">to</span>
                                <input type="time" name="day_rate_end" value="{{ old('day_rate_end', '18:00') }}"
                                       class="flex-1 px-3 py-2 border border-gray-300 rounded-md focus:ring-green-500 focus:border-green-500">
                            </div>
                            @error('day_rate_start')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="bg-white rounded-lg p-4 border border-gray-200">
                            <label class="block text-sm font-medium text-gray-700 mb-3">
                                <i class="fas fa-moon text-indigo-500 mr-1"></i> Night Rate Hours
                            </label>
                            <div class="flex items-center space-x-2">
                                <input type="time" name="night_rate_start" value="{{ old('night_rate_start', '18:00') }}"
                                       class="flex-1 px-3 py-2 border border-gray-300 rounded-md focus:ring-green-500 focus:border-green-500">
                                <span class="text-gray-500">to</span>
                                <input type="time" name="night_rate_end" value="{{ old('night_rate_end', '22:00') }}"
                                       class="flex-1 px-3 py-2 border border-gray-300 rounded-md focus:ring-green-500 focus:border-green-500">
                            </div>
                            @error('night_rate_start')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    <p class="text-xs text-gray-500"><i class="fas fa-info-circle mr-1"></i> Day rate applies during day hours, night rate applies during night hours (if set).</p>
                </div>

                <!-- Capacity Section -->
                <div class="bg-gray-50 rounded-lg p-4 space-y-4">
                    <h3 class="font-semibold text-gray-800 flex items-center">
                        <i class="fas fa-users text-green-500 mr-2"></i> Ground Capacity
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Capacity Type *</label>
                            <select name="capacity" required
                                    class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-green-500 focus:border-green-500">
                                <option value="">Select Capacity</option>
                                <optgroup label="Football / Futsal">
                                    <option value="5-a-side" {{ old('capacity') == '5-a-side' ? 'selected' : '' }}>5-a-side</option>
                                    <option value="6-a-side" {{ old('capacity') == '6-a-side' ? 'selected' : '' }}>6-a-side</option>
                                    <option value="7-a-side" {{ old('capacity') == '7-a-side' ? 'selected' : '' }}>7-a-side</option>
                                    <option value="9-a-side" {{ old('capacity') == '9-a-side' ? 'selected' : '' }}>9-a-side</option>
                                    <option value="11-a-side" {{ old('capacity') == '11-a-side' ? 'selected' : '' }}>11-a-side (Full)</option>
                                </optgroup>
                                <optgroup label="Cricket">
                                    <option value="6-players" {{ old('capacity') == '6-players' ? 'selected' : '' }}>6 Players</option>
                                    <option value="8-players" {{ old('capacity') == '8-players' ? 'selected' : '' }}>8 Players</option>
                                    <option value="11-players" {{ old('capacity') == '11-players' ? 'selected' : '' }}>11 Players (Full)</option>
                                </optgroup>
                                <optgroup label="Badminton / Tennis">
                                    <option value="singles" {{ old('capacity') == 'singles' ? 'selected' : '' }}>Singles (2 players)</option>
                                    <option value="doubles" {{ old('capacity') == 'doubles' ? 'selected' : '' }}>Doubles (4 players)</option>
                                </optgroup>
                                <optgroup label="Basketball">
                                    <option value="3v3" {{ old('capacity') == '3v3' ? 'selected' : '' }}>3v3 (6 players)</option>
                                    <option value="5v5" {{ old('capacity') == '5v5' ? 'selected' : '' }}>5v5 (10 players)</option>
                                </optgroup>
                                <optgroup label="Other">
                                    <option value="2-10" {{ old('capacity') == '2-10' ? 'selected' : '' }}>2-10 players</option>
                                    <option value="10-20" {{ old('capacity') == '10-20' ? 'selected' : '' }}>10-20 players</option>
                                    <option value="20+" {{ old('capacity') == '20+' ? 'selected' : '' }}>20+ players</option>
                                    <option value="unlimited" {{ old('capacity') == 'unlimited' ? 'selected' : '' }}>Unlimited</option>
                                </optgroup>
                            </select>
                            @error('capacity')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Capacity Details (Optional)</label>
                            <input type="text" name="capacity_description" value="{{ old('capacity_description') }}"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-green-500 focus:border-green-500"
                                   placeholder="e.g., Can accommodate up to 22 players">
                            @error('capacity_description')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                    <textarea name="description" rows="4"
                              class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500"
                              placeholder="Describe your ground, amenities, facilities, etc.">{{ old('description') }}</textarea>
                    @error('description')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Image Upload Section -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Ground Photos</label>
                    <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md">
                        <div class="space-y-1 text-center">
                            <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                            <div class="flex text-sm text-gray-600">
                                <label for="images" class="relative cursor-pointer bg-white rounded-md font-medium text-indigo-600 hover:text-indigo-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-indigo-500">
                                    <span>Upload photos</span>
                                    <input id="images" name="images[]" type="file" class="sr-only" multiple accept="image/*">
                                </label>
                                <p class="pl-1">or drag and drop</p>
                            </div>
                            <p class="text-xs text-gray-500">PNG, JPG, GIF up to 2MB each (max 5 photos)</p>
                        </div>
                    </div>
                    <div id="image-preview" class="mt-4 grid grid-cols-5 gap-4"></div>
                    @error('images.*')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center">
                    <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}
                           class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                    <label for="is_active" class="ml-2 block text-sm text-gray-900">
                        Active (visible to users for booking)
                    </label>
                </div>
            </div>

            <div class="mt-6 flex space-x-4">
                <button type="submit" class="flex-1 bg-indigo-600 text-white py-3 rounded-md hover:bg-indigo-700 font-semibold">
                    <i class="fas fa-plus"></i> Add Ground
                </button>
                <a href="{{ route('owner.grounds.index') }}" 
                   class="flex-1 bg-gray-200 text-gray-700 py-3 rounded-md hover:bg-gray-300 text-center font-semibold">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>

<script>
document.getElementById('images').addEventListener('change', function(e) {
    const preview = document.getElementById('image-preview');
    preview.innerHTML = '';
    
    [...e.target.files].slice(0, 5).forEach(file => {
        const reader = new FileReader();
        reader.onload = function(e) {
            const div = document.createElement('div');
            div.className = 'relative';
            div.innerHTML = `
                <img src="${e.target.result}" class="h-24 w-full object-cover rounded-md">
            `;
            preview.appendChild(div);
        }
        reader.readAsDataURL(file);
    });
});
</script>
@endsection
