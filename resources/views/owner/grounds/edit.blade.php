@extends('layouts.app')

@section('title', 'Edit ' . $ground->name)

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="bg-white rounded-lg shadow-md p-6">
        <h1 class="text-2xl font-bold text-gray-900 mb-6">Edit Ground</h1>
        
        <form action="{{ route('owner.grounds.update', $ground) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            
            <div class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Ground Name *</label>
                        <input type="text" name="name" value="{{ old('name', $ground->name) }}" required
                               class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500">
                        @error('name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Sport Type *</label>
                        <select name="sport_type_id" required
                                class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500">
                            @foreach($sportsTypes as $sport)
                                <option value="{{ $sport->id }}" {{ old('sport_type_id', $ground->sport_type_id) == $sport->id ? 'selected' : '' }}>
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
                        <input type="text" name="location" value="{{ old('location', $ground->location) }}" required
                               class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-green-500 focus:border-green-500">
                        @error('location')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Full Address</label>
                        <input type="text" name="address" value="{{ old('address', $ground->address) }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-green-500 focus:border-green-500">
                        @error('address')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Contact Phone Number *</label>
                    <div class="relative">
                        <i class="fas fa-phone absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                        <input type="tel" name="phone" value="{{ old('phone', $ground->phone) }}" required
                               class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-md focus:ring-green-500 focus:border-green-500"
                               placeholder="e.g., 17123456">
                    </div>
                    <p class="mt-1 text-xs text-gray-500"><i class="fas fa-info-circle mr-1"></i> This number will be visible to users for booking inquiries. Update if ownership changes.</p>
                    @error('phone')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Day Rate per Hour (BTN) *</label>
                        <input type="number" name="rate_per_hour" value="{{ old('rate_per_hour', $ground->rate_per_hour) }}" min="0" step="0.01" required
                               class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-green-500 focus:border-green-500">
                        @error('rate_per_hour')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Night Rate per Hour (BTN)</label>
                        <input type="number" name="night_rate_per_hour" value="{{ old('night_rate_per_hour', $ground->night_rate_per_hour) }}" min="0" step="0.01"
                               class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-green-500 focus:border-green-500">
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
                                <input type="time" name="day_rate_start" value="{{ old('day_rate_start', $ground->day_rate_start ? \Carbon\Carbon::parse($ground->day_rate_start)->format('H:i') : '06:00') }}"
                                       class="flex-1 px-3 py-2 border border-gray-300 rounded-md focus:ring-green-500 focus:border-green-500">
                                <span class="text-gray-500">to</span>
                                <input type="time" name="day_rate_end" value="{{ old('day_rate_end', $ground->day_rate_end ? \Carbon\Carbon::parse($ground->day_rate_end)->format('H:i') : '18:00') }}"
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
                                <input type="time" name="night_rate_start" value="{{ old('night_rate_start', $ground->night_rate_start ? \Carbon\Carbon::parse($ground->night_rate_start)->format('H:i') : '18:00') }}"
                                       class="flex-1 px-3 py-2 border border-gray-300 rounded-md focus:ring-green-500 focus:border-green-500">
                                <span class="text-gray-500">to</span>
                                <input type="time" name="night_rate_end" value="{{ old('night_rate_end', $ground->night_rate_end ? \Carbon\Carbon::parse($ground->night_rate_end)->format('H:i') : '22:00') }}"
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
                                    <option value="5-a-side" {{ old('capacity', $ground->capacity) == '5-a-side' ? 'selected' : '' }}>5-a-side</option>
                                    <option value="6-a-side" {{ old('capacity', $ground->capacity) == '6-a-side' ? 'selected' : '' }}>6-a-side</option>
                                    <option value="7-a-side" {{ old('capacity', $ground->capacity) == '7-a-side' ? 'selected' : '' }}>7-a-side</option>
                                    <option value="9-a-side" {{ old('capacity', $ground->capacity) == '9-a-side' ? 'selected' : '' }}>9-a-side</option>
                                    <option value="11-a-side" {{ old('capacity', $ground->capacity) == '11-a-side' ? 'selected' : '' }}>11-a-side (Full)</option>
                                </optgroup>
                                <optgroup label="Cricket">
                                    <option value="6-players" {{ old('capacity', $ground->capacity) == '6-players' ? 'selected' : '' }}>6 Players</option>
                                    <option value="8-players" {{ old('capacity', $ground->capacity) == '8-players' ? 'selected' : '' }}>8 Players</option>
                                    <option value="11-players" {{ old('capacity', $ground->capacity) == '11-players' ? 'selected' : '' }}>11 Players (Full)</option>
                                </optgroup>
                                <optgroup label="Badminton / Tennis">
                                    <option value="singles" {{ old('capacity', $ground->capacity) == 'singles' ? 'selected' : '' }}>Singles (2 players)</option>
                                    <option value="doubles" {{ old('capacity', $ground->capacity) == 'doubles' ? 'selected' : '' }}>Doubles (4 players)</option>
                                </optgroup>
                                <optgroup label="Basketball">
                                    <option value="3v3" {{ old('capacity', $ground->capacity) == '3v3' ? 'selected' : '' }}>3v3 (6 players)</option>
                                    <option value="5v5" {{ old('capacity', $ground->capacity) == '5v5' ? 'selected' : '' }}>5v5 (10 players)</option>
                                </optgroup>
                                <optgroup label="Other">
                                    <option value="2-10" {{ old('capacity', $ground->capacity) == '2-10' ? 'selected' : '' }}>2-10 players</option>
                                    <option value="10-20" {{ old('capacity', $ground->capacity) == '10-20' ? 'selected' : '' }}>10-20 players</option>
                                    <option value="20+" {{ old('capacity', $ground->capacity) == '20+' ? 'selected' : '' }}>20+ players</option>
                                    <option value="unlimited" {{ old('capacity', $ground->capacity) == 'unlimited' ? 'selected' : '' }}>Unlimited</option>
                                </optgroup>
                            </select>
                            @error('capacity')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Capacity Details (Optional)</label>
                            <input type="text" name="capacity_description" value="{{ old('capacity_description', $ground->capacity_description) }}"
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
                              class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500">{{ old('description', $ground->description) }}</textarea>
                    @error('description')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Current Images -->
                @if($ground->images && count($ground->images) > 0)
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Current Photos ({{ count($ground->images) }}/4)
                    </label>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        @foreach($ground->images as $index => $image)
                            <div class="relative group border-2 border-gray-200 rounded-lg overflow-hidden hover:border-red-400 transition" x-data="{ removing: false }">
                                <img src="{{ asset('storage/' . $image) }}" class="w-full h-32 object-cover">
                                <div class="absolute inset-0 bg-gradient-to-t from-black/70 to-transparent opacity-0 group-hover:opacity-100 transition">
                                    <label class="absolute bottom-0 left-0 right-0 p-2 cursor-pointer">
                                        <input 
                                            type="checkbox" 
                                            name="remove_images[]" 
                                            value="{{ $index }}" 
                                            class="hidden remove-checkbox"
                                            @change="removing = $event.target.checked"
                                        >
                                        <div :class="removing ? 'bg-red-600' : 'bg-white/90'" class="flex items-center justify-center py-2 rounded transition">
                                            <i :class="removing ? 'fas fa-trash-alt text-white' : 'fas fa-times-circle text-red-600'" class="mr-1.5"></i>
                                            <span :class="removing ? 'text-white font-bold' : 'text-gray-900 font-medium'" class="text-xs" x-text="removing ? 'Will Remove' : 'Remove Photo'"></span>
                                        </div>
                                    </label>
                                </div>
                                <div x-show="removing" class="absolute top-2 right-2 bg-red-600 text-white text-xs px-2 py-1 rounded-full font-bold">
                                    ✓
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <p class="mt-2 text-xs text-gray-600">
                        <i class="fas fa-info-circle text-blue-500"></i> Click on photos to mark them for removal. Maximum 4 photos allowed.
                    </p>
                </div>
                @endif

                <!-- Upload New Images -->
                <div>
                    @php
                        $currentCount = $ground->images ? count($ground->images) : 0;
                        $remainingSlots = 4 - $currentCount;
                    @endphp
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Add More Photos 
                        @if($remainingSlots > 0)
                            <span class="text-green-600">({{ $remainingSlots }} slots remaining)</span>
                        @else
                            <span class="text-red-600">(Maximum reached - remove old photos to add new ones)</span>
                        @endif
                    </label>
                    <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md">
                        <div class="space-y-1 text-center">
                            <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                            <div class="flex text-sm text-gray-600">
                                <label for="images" class="relative cursor-pointer bg-white rounded-md font-medium text-indigo-600 hover:text-indigo-500 focus-within:outline-none">
                                    <span>Upload photos</span>
                                    <input id="images" name="images[]" type="file" class="sr-only" multiple accept="image/*">
                                </label>
                                <p class="pl-1">or drag and drop</p>
                            </div>
                            <p class="text-xs text-gray-500">PNG, JPG, GIF, WEBP up to 2MB each (max 4 total)</p>
                        </div>
                    </div>
                    <div id="image-preview" class="mt-4 grid grid-cols-2 md:grid-cols-4 gap-4"></div>
                    @error('images.*')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center">
                    <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', $ground->is_active) ? 'checked' : '' }}
                           class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                    <label for="is_active" class="ml-2 block text-sm text-gray-900">
                        Active (visible to users for booking)
                    </label>
                </div>
            </div>

            <div class="mt-6 flex space-x-4">
                <button type="submit" class="flex-1 bg-indigo-600 text-white py-3 rounded-md hover:bg-indigo-700 font-semibold">
                    <i class="fas fa-save"></i> Update Ground
                </button>
                <a href="{{ route('owner.grounds.index') }}" 
                   class="flex-1 bg-gray-200 text-gray-700 py-3 rounded-md hover:bg-gray-300 text-center font-semibold">
                    Cancel
                </a>
            </div>
        </form>

        <div class="mt-6 border-t pt-6">
            <form action="{{ route('owner.grounds.destroy', $ground) }}" method="POST" 
                  onsubmit="return confirm('Are you sure you want to delete this ground? This action cannot be undone.')">
                @csrf
                @method('DELETE')
                <button type="submit" class="bg-red-600 text-white px-6 py-2 rounded-md hover:bg-red-700">
                    <i class="fas fa-trash"></i> Delete Ground
                </button>
            </form>
        </div>
    </div>
</div>

<script>
document.getElementById('images').addEventListener('change', function(e) {
    const preview = document.getElementById('image-preview');
    const files = Array.from(e.target.files);
    const currentImages = {{ $ground->images ? count($ground->images) : 0 }};
    const remainingSlots = 4 - currentImages;
    
    if (files.length > remainingSlots) {
        alert(`You can only add ${remainingSlots} more photo(s). Remove old photos first to add more.`);
    }
    
    preview.innerHTML = '';
    
    files.slice(0, remainingSlots).forEach((file, index) => {
        const reader = new FileReader();
        reader.onload = function(e) {
            const div = document.createElement('div');
            div.className = 'relative border-2 border-green-500 rounded-lg overflow-hidden';
            div.innerHTML = `
                <img src="${e.target.result}" class="h-32 w-full object-cover">
                <div class="absolute bottom-0 left-0 right-0 bg-green-600 text-white text-xs py-1 text-center">
                    New Photo ${index + 1}
                </div>
            `;
            preview.appendChild(div);
        }
        reader.readAsDataURL(file);
    });
});
</script>
@endsection
