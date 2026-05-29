@extends('layouts.app')

@section('title', 'Edit ' . $ground->name)

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="bg-white rounded-lg shadow-md p-6">
        <h1 class="text-2xl font-bold text-gray-900 mb-6">Edit Ground</h1>
        
        <form action="{{ route('admin.grounds.update', $ground) }}" method="POST" enctype="multipart/form-data">
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

                <!-- Bank Account Details -->
                <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                    <h3 class="font-semibold text-gray-800 mb-4 flex items-center">
                        <i class="fas fa-bank text-green-600 mr-2"></i> Bank Account Details (For Payment Transfers)
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Bank Name</label>
                            <input type="text" name="bank_name" value="{{ old('bank_name', $ground->bank_name) }}"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-green-500 focus:border-green-500"
                                   placeholder="e.g., Bhutan National Bank (BNB)">
                            <p class="mt-1 text-xs text-gray-500"><i class="fas fa-info-circle mr-1"></i> Bank name where customers will transfer payment</p>
                            @error('bank_name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Account Number</label>
                            <input type="text" name="account_number" value="{{ old('account_number', $ground->account_number) }}"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-green-500 focus:border-green-500"
                                   placeholder="e.g., 1234567890">
                            <p class="mt-1 text-xs text-gray-500"><i class="fas fa-info-circle mr-1"></i> Your bank account number for payment transfers</p>
                            @error('account_number')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    <div class="bg-blue-100 border border-blue-300 rounded p-2 mt-3">
                        <p class="text-xs text-blue-800">
                            <i class="fas fa-shield-alt mr-1"></i> This information will only be displayed to customers on the payment page before they make a transfer.
                        </p>
                    </div>
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
                </div>

                <!-- Available at Night Option -->
                <div class="bg-purple-50 border border-purple-200 rounded-lg p-4">
                    <label class="flex items-center space-x-3 cursor-pointer">
                        <input type="checkbox" name="available_at_night" id="available_at_night" value="1"
                               class="w-5 h-5 text-purple-600 border-gray-300 rounded focus:ring-purple-500"
                               {{ old('available_at_night', $ground->available_at_night) ? 'checked' : '' }}
                               onchange="toggleNightPricingAdminEdit()">
                        <span class="text-sm font-medium text-gray-700">
                            <i class="fas fa-moon text-purple-600 mr-1"></i> Available at Night
                        </span>
                    </label>
                    <p class="mt-2 ml-8 text-sm text-gray-600"><i class="fas fa-info-circle mr-1"></i> Check this if this ground operates and accepts bookings during night hours (for night rate pricing)</p>
                </div>

                <!-- Night Time Pricing Section -->
                <div id="nightPricingAdminEdit" class="bg-purple-50 border border-purple-200 rounded-lg p-4 space-y-4 {{ old('available_at_night', $ground->available_at_night) ? '' : 'hidden' }}">
                    <h3 class="font-semibold text-gray-800 flex items-center">
                        <i class="fas fa-moon text-purple-600 mr-2"></i> Night Time Pricing
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-clock text-purple-500 mr-1"></i> Night Time Starts At
                            </label>
                            <input type="time" name="night_rate_start" 
                                   value="{{ old('night_rate_start', $ground->night_rate_start ? \Carbon\Carbon::parse($ground->night_rate_start)->format('H:i') : '18:00') }}"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-purple-500 focus:border-purple-500">
                            <p class="mt-1 text-xs text-gray-500">When night rate pricing starts (e.g., 6:00 PM)</p>
                            @error('night_rate_start')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-dollar-sign text-purple-500 mr-1"></i> Price (per hour) *
                            </label>
                            <div class="relative">
                                <span class="absolute left-3 top-2 text-gray-500 font-medium">BTN</span>
                                <input type="number" name="night_rate_per_hour" 
                                       value="{{ old('night_rate_per_hour', $ground->night_rate_per_hour) }}" 
                                       min="0" step="0.01"
                                       class="w-full pl-12 pr-4 py-2 border border-gray-300 rounded-md focus:ring-purple-500 focus:border-purple-500"
                                       placeholder="700.00">
                            </div>
                            <p class="mt-1 text-xs text-gray-500">This ground's night time hourly rate</p>
                            @error('night_rate_per_hour')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    <p class="text-xs text-gray-600"><i class="fas fa-info-circle mr-1"></i> Set night time pricing (typically evening onwards)</p>
                </div>

                <!-- Operating Hours Section -->
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 space-y-4">
                    <h3 class="font-semibold text-gray-800 flex items-center">
                        <i class="fas fa-building text-blue-600 mr-2"></i> Operating Hours (When Ground is Open for Bookings)
                    </h3>
                    <p class="text-xs text-gray-600"><i class="fas fa-info-circle mr-1"></i> These are the ground's actual opening and closing times - when customers can book slots. This is separate from pricing schedule below.</p>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-unlock text-green-500 mr-1"></i> Opening Time *
                            </label>
                            <input type="time" name="opening_time" 
                                   value="{{ old('opening_time', $ground->opening_time ? \Carbon\Carbon::parse($ground->opening_time)->format('H:i') : '06:00') }}"
                                   required
                                   class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500">
                            <p class="mt-1 text-xs text-gray-500">When bookings start for the day (e.g., 6:00 AM)</p>
                            @error('opening_time')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-lock text-red-500 mr-1"></i> Closing Time *
                            </label>
                            <input type="time" name="closing_time" 
                                   value="{{ old('closing_time', $ground->closing_time ? \Carbon\Carbon::parse($ground->closing_time)->format('H:i') : '22:00') }}"
                                   required
                                   class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500">
                            <p class="mt-1 text-xs text-gray-500">When bookings end for the day (e.g., 10:00 PM)</p>
                            @error('closing_time')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    <div class="bg-white border border-blue-200 rounded p-2">
                        <p class="text-xs text-blue-800">
                            <i class="fas fa-lightbulb mr-1"></i> <strong>Example:</strong> If the ground opens at 8:00 AM and closes at 10:00 PM, set these times accordingly. Customers will only see available slots between these hours.
                        </p>
                    </div>
                </div>

                <!-- Rate Timing Section -->
                <div class="bg-gray-50 rounded-lg p-4 space-y-4">
                    <h3 class="font-semibold text-gray-800 flex items-center">
                        <i class="fas fa-clock text-green-500 mr-2"></i> Rate Timing (Pricing Schedule)
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
                    <p class="text-xs text-gray-500"><i class="fas fa-info-circle mr-1"></i> <strong>For Pricing Only:</strong> These times determine when different rates apply. Day rate applies during day hours, night rate applies during night hours (if set). Customers can still book during operating hours you set above.</p>
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
                <a href="{{ route('admin.grounds.show', $ground) }}" 
                   class="flex-1 bg-gray-200 text-gray-700 py-3 rounded-md hover:bg-gray-300 text-center font-semibold">
                    Cancel
                </a>
            </div>
        </form>

        <!-- JavaScript for auto-populate Operating Hours from Rate Timing -->
        <script>
            // Auto-populate Operating Hours based on Rate Timing for convenience
            function updateOperatingHours() {
                const dayRateStart = document.querySelector('input[name="day_rate_start"]').value;
                const nightRateEnd = document.querySelector('input[name="night_rate_end"]').value;
                const openingTimeField = document.querySelector('input[name="opening_time"]');
                const closingTimeField = document.querySelector('input[name="closing_time"]');

                // If day rate start is set, use it as opening time (user can override)
                if (dayRateStart && !openingTimeField.value) {
                    openingTimeField.value = dayRateStart;
                }

                // If night rate end is set, use it as closing time (user can override)
                if (nightRateEnd && !closingTimeField.value) {
                    closingTimeField.value = nightRateEnd;
                }
            }

            // Listen for changes to rate timing fields
            document.querySelector('input[name="day_rate_start"]').addEventListener('change', updateOperatingHours);
            document.querySelector('input[name="night_rate_end"]').addEventListener('change', updateOperatingHours);

            // Run on page load in case values exist
            document.addEventListener('DOMContentLoaded', updateOperatingHours);
        </script>

    <script>
document.getElementById('images').addEventListener('change', function(e) {
    const preview = document.getElementById('image-preview');
    preview.innerHTML = '';
    
    for (let file of e.target.files) {
        const reader = new FileReader();
        reader.onload = function(event) {
            const img = document.createElement('img');
            img.src = event.target.result;
            img.className = 'w-full h-32 object-cover rounded border border-gray-300';
            preview.appendChild(img);
        };
        reader.readAsDataURL(file);
    }
});

// Toggle Night Pricing Section
function toggleNightPricingAdminEdit() {
    const checkbox = document.getElementById('available_at_night');
    const container = document.getElementById('nightPricingAdminEdit');
    
    if (!checkbox || !container) {
        console.warn('Warning: Night pricing elements not found');
        return;
    }
    
    if (checkbox.checked) {
        container.classList.remove('hidden');
        container.style.display = 'block';
    } else {
        container.classList.add('hidden');
        container.style.display = 'none';
    }
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    toggleNightPricingAdminEdit();
});
</script>
@endsection
