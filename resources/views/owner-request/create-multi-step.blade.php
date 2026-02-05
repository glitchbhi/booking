@extends('layouts.app')

@section('title', 'Request to Become Owner')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="bg-white rounded-lg shadow-md p-6 md:p-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-2">Request to Become a Ground Owner</h1>
        <p class="text-gray-600 mb-8">Fill out the information step by step to submit your request</p>
        
        <!-- Progress Steps -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <div class="flex items-center">
                        <div class="flex items-center text-green-600 relative">
                            <div class="rounded-full transition duration-500 ease-in-out h-12 w-12 py-3 border-2 border-green-600 bg-green-600 text-white flex items-center justify-center font-bold step-circle" id="step1-circle">1</div>
                            <div class="absolute top-0 -ml-10 text-center mt-16 w-32 text-xs font-medium uppercase text-green-600" id="step1-label">Ground Info</div>
                        </div>
                        <div class="flex-auto border-t-2 transition duration-500 ease-in-out border-gray-300" id="line1"></div>
                    </div>
                </div>
                <div class="flex-1">
                    <div class="flex items-center">
                        <div class="flex items-center text-gray-500 relative">
                            <div class="rounded-full transition duration-500 ease-in-out h-12 w-12 py-3 border-2 border-gray-300 flex items-center justify-center font-bold step-circle" id="step2-circle">2</div>
                            <div class="absolute top-0 -ml-10 text-center mt-16 w-32 text-xs font-medium uppercase" id="step2-label">Pricing</div>
                        </div>
                        <div class="flex-auto border-t-2 transition duration-500 ease-in-out border-gray-300" id="line2"></div>
                    </div>
                </div>
                <div class="flex-1">
                    <div class="flex items-center">
                        <div class="flex items-center text-gray-500 relative">
                            <div class="rounded-full transition duration-500 ease-in-out h-12 w-12 py-3 border-2 border-gray-300 flex items-center justify-center font-bold step-circle" id="step3-circle">3</div>
                            <div class="absolute top-0 -ml-10 text-center mt-16 w-32 text-xs font-medium uppercase" id="step3-label">Images</div>
                        </div>
                        <div class="flex-auto border-t-2 transition duration-500 ease-in-out border-gray-300" id="line3"></div>
                    </div>
                </div>
                <div class="flex-1">
                    <div class="flex items-center">
                        <div class="flex items-center text-gray-500 relative">
                            <div class="rounded-full transition duration-500 ease-in-out h-12 w-12 py-3 border-2 border-gray-300 flex items-center justify-center font-bold step-circle" id="step4-circle">4</div>
                            <div class="absolute top-0 -ml-10 text-center mt-16 w-32 text-xs font-medium uppercase" id="step4-label">Details</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <form action="{{ route('owner-request.store') }}" method="POST" enctype="multipart/form-data" id="ownerRequestForm">
            @csrf
            
            <!-- Step 1: Ground Information -->
            <div class="step-content" id="step1">
                <h2 class="text-2xl font-semibold text-gray-800 mb-6">Step 1: Ground Information</h2>
                
                <div class="space-y-5">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Ground Name *</label>
                        <input type="text" name="ground_name" id="ground_name" required
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                               placeholder="e.g., Green Valley Sports Complex"
                               value="{{ old('ground_name') }}">
                        <p class="mt-1 text-sm text-gray-500">Enter the official name of your sports ground</p>
                        @error('ground_name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">License Number *</label>
                        <input type="text" name="license_number" id="license_number" required
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                               placeholder="e.g., LIC-2024-12345"
                               value="{{ old('license_number') }}">
                        <p class="mt-1 text-sm text-gray-500">Your business or ground operating license number</p>
                        @error('license_number')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Category / Sports Type *</label>
                        <select name="category" id="category" required
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                                onchange="toggleGroundSize()">
                            <option value="">Select a category</option>
                            @foreach(\App\Models\SportsType::orderBy('name')->get() as $sport)
                                <option value="{{ $sport->name }}" {{ old('category') == $sport->name ? 'selected' : '' }}>
                                    {{ $sport->name }}
                                </option>
                            @endforeach
                        </select>
                        <p class="mt-1 text-sm text-gray-500">Select the primary sport for this ground</p>
                        @error('category')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div id="groundSizeContainer" class="hidden">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Team Size / Players per Team *</label>
                        <div>
                            <input type="number" name="team_size" id="team_size" min="5" max="11" step="1"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                                   placeholder="Number of players per team (5-11)"
                                   value="{{ old('team_size') }}">
                        </div>
                        <p class="mt-1 text-sm text-gray-500">Enter number of players per team (Football: minimum 5, maximum 11)</p>
                        @error('team_size')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Step 2: Pricing -->
            <div class="step-content hidden" id="step2">
                <h2 class="text-2xl font-semibold text-gray-800 mb-6">Step 2: Pricing Details</h2>
                
                <div class="space-y-5">
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                        <p class="text-sm text-blue-800">
                            <i class="fas fa-info-circle mr-1"></i>
                            Define your pricing for different time slots during the day
                        </p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Day Time Pricing</label>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                            <div>
                                <label class="block text-xs text-gray-600 mb-1">Day Time Starts At</label>
                                <input type="time" name="day_time_start" id="day_time_start" required
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                                       value="{{ old('day_time_start', '06:00') }}">
                            </div>
                            <div>
                                <label class="block text-xs text-gray-600 mb-1">Price (per hour) *</label>
                                <div class="relative">
                                    <span class="absolute left-4 top-3.5 text-gray-500 font-medium">BTN</span>
                                    <input type="number" name="price_day" id="price_day" required min="0" step="0.01"
                                           class="w-full pl-16 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                                           placeholder="500.00"
                                           value="{{ old('price_day') }}">
                                </div>
                            </div>
                        </div>
                        <p class="mt-1 text-sm text-gray-500">Set your day time pricing (typically morning to evening)</p>
                        @error('day_time_start')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        @error('price_day')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="flex items-center space-x-3 cursor-pointer">
                            <input type="checkbox" name="available_at_night" id="available_at_night" value="1"
                                   class="w-5 h-5 text-green-600 border-gray-300 rounded focus:ring-green-500"
                                   {{ old('available_at_night') ? 'checked' : '' }}
                                   onchange="toggleNightPrice()">
                            <span class="text-sm font-medium text-gray-700">Available at Night</span>
                        </label>
                        <p class="mt-1 ml-8 text-sm text-gray-500">Check if your ground operates during night time</p>
                    </div>

                    <div id="nightPriceContainer" class="hidden">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Night Time Pricing</label>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                            <div>
                                <label class="block text-xs text-gray-600 mb-1">Night Time Starts At</label>
                                <input type="time" name="night_time_start" id="night_time_start"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                                       value="{{ old('night_time_start', '18:00') }}">
                            </div>
                            <div>
                                <label class="block text-xs text-gray-600 mb-1">Price (per hour)</label>
                                <div class="relative">
                                    <span class="absolute left-4 top-3.5 text-gray-500 font-medium">BTN</span>
                                    <input type="number" name="price_night" id="price_night" min="0" step="0.01"
                                           class="w-full pl-16 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                                           placeholder="700.00"
                                           value="{{ old('price_night') }}">
                                </div>
                            </div>
                        </div>
                        <p class="mt-1 text-sm text-gray-500">Set your night time pricing (typically evening onwards)</p>
                        @error('night_time_start')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        @error('price_night')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="bg-gray-50 rounded-lg p-4">
                        <h4 class="text-sm font-semibold text-gray-700 mb-2">Pricing Preview</h4>
                        <div id="pricingPreview" class="text-sm text-gray-600">
                            <p id="dayPricePreview">• Day Time: Set your pricing above</p>
                            <p id="nightPricePreview" class="hidden">• Night Time: Set your pricing above</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Step 3: Images -->
            <div class="step-content hidden" id="step3">
                <h2 class="text-2xl font-semibold text-gray-800 mb-6">Step 3: Ground Images</h2>
                
                <div class="space-y-5">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Upload Ground Images *</label>
                        <div class="mt-2 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg hover:border-green-400 transition-colors">
                            <div class="space-y-1 text-center">
                                <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                    <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                                <div class="flex text-sm text-gray-600">
                                    <label for="ground_images" class="relative cursor-pointer bg-white rounded-md font-medium text-green-600 hover:text-green-500 focus-within:outline-none">
                                        <span>Upload files</span>
                                        <input id="ground_images" name="ground_images[]" type="file" class="sr-only" multiple accept="image/*" onchange="previewImages(event)">
                                    </label>
                                    <p class="pl-1">or drag and drop</p>
                                </div>
                                <p class="text-xs text-gray-500">PNG, JPG, JPEG up to 10MB each (Max 5 images)</p>
                            </div>
                        </div>
                        @error('ground_images')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Image Previews -->
                    <div id="imagePreviewContainer" class="grid grid-cols-2 md:grid-cols-3 gap-4 mt-4 hidden">
                        <!-- Previews will be inserted here -->
                    </div>
                </div>
            </div>

            <!-- Step 4: Additional Details -->
            <div class="step-content hidden" id="step4">
                <h2 class="text-2xl font-semibold text-gray-800 mb-6">Step 4: Business & Contact Details</h2>
                
                <div class="space-y-5">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Business Address *</label>
                        <textarea name="business_address" id="business_address" rows="3" required
                                  class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                                  placeholder="Enter your complete business address including city, state, and pincode">{{ old('business_address') }}</textarea>
                        <p class="mt-1 text-sm text-gray-500">Full address where your ground is located</p>
                        @error('business_address')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Contact Number *</label>
                        <input type="tel" name="contact_number" id="contact_number" required
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                               placeholder="e.g., +91 98765 43210"
                               value="{{ old('contact_number') }}"
                               pattern="[0-9+\s\-()]*">
                        <p class="mt-1 text-sm text-gray-500">Phone number for customer inquiries</p>
                        @error('contact_number')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Operating Hours / Availability *</label>
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-xs text-gray-600 mb-1">Opening Time</label>
                                <input type="time" name="opening_time" id="opening_time" required
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                                       value="{{ old('opening_time', '06:00') }}">
                            </div>
                            <div>
                                <label class="block text-xs text-gray-600 mb-1">Closing Time</label>
                                <input type="time" name="closing_time" id="closing_time" required
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                                       value="{{ old('closing_time', '22:00') }}">
                            </div>
                        </div>
                        <p class="mt-1 text-sm text-gray-500">Daily operating hours (e.g., 6:00 AM to 10:00 PM)</p>
                        @error('opening_time')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        @error('closing_time')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Facilities & Amenities</label>
                        <textarea name="facilities" id="facilities" rows="3"
                                  class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                                  placeholder="e.g., Parking, Changing rooms, Washrooms, Drinking water, First aid, etc.">{{ old('facilities') }}</textarea>
                        <p class="mt-1 text-sm text-gray-500">List available facilities (optional)</p>
                        @error('facilities')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Why do you want to become an owner? *</label>
                        <textarea name="reason" id="reason" rows="4" required
                                  class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                                  placeholder="Tell us about your motivation and experience...">{{ old('reason') }}</textarea>
                        @error('reason')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                        <h3 class="font-semibold text-blue-900 mb-2 flex items-center">
                            <i class="fas fa-star mr-2"></i> Benefits of Becoming an Owner
                        </h3>
                        <ul class="list-disc list-inside text-blue-800 space-y-1 text-sm">
                            <li>List and manage your sports grounds</li>
                            <li>Set your own pricing and availability</li>
                            <li>Accept both online and offline bookings</li>
                            <li>Track revenue and analytics</li>
                            <li>Earn 98% commission on all bookings</li>
                        </ul>
                    </div>

                    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                        <p class="text-sm text-yellow-800 flex items-start">
                            <i class="fas fa-info-circle mt-0.5 mr-2"></i>
                            <span><strong>Note:</strong> Your request will be reviewed by our admin team. You will receive an email notification once your request is processed.</span>
                        </p>
                    </div>
                </div>
            </div>

            <!-- Navigation Buttons -->
            <div class="mt-8 flex justify-between">
                <button type="button" id="prevBtn" onclick="changeStep(-1)" 
                        class="px-6 py-3 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 font-semibold transition-colors hidden">
                    <i class="fas fa-arrow-left mr-2"></i> Previous
                </button>
                <a href="{{ route('welcome') }}" id="cancelBtn"
                   class="px-6 py-3 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 font-semibold transition-colors">
                    Cancel
                </a>
                <button type="button" id="nextBtn" onclick="changeStep(1)" 
                        class="px-6 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 font-semibold transition-colors">
                    Next <i class="fas fa-arrow-right ml-2"></i>
                </button>
                <button type="submit" id="submitBtn" 
                        class="px-6 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 font-semibold transition-colors hidden">
                    <i class="fas fa-paper-plane mr-2"></i> Submit Request
                </button>
            </div>
        </form>
    </div>
</div>

<script>
let currentStep = 1;
const totalSteps = 4;

// Show the current step on page load
document.addEventListener('DOMContentLoaded', function() {
    showStep(currentStep);
    
    // Check if night is available on load
    if (document.getElementById('available_at_night').checked) {
        document.getElementById('nightPriceContainer').classList.remove('hidden');
        document.getElementById('price_night').required = true;
    }
    
    // Check if ground size should be shown on load
    toggleGroundSize();
    
    // Update pricing preview on input
    document.getElementById('day_time_start').addEventListener('change', updatePricingPreview);
    document.getElementById('price_day').addEventListener('input', updatePricingPreview);
    document.getElementById('night_time_start').addEventListener('change', updatePricingPreview);
    document.getElementById('price_night').addEventListener('input', updatePricingPreview);
    
    updatePricingPreview();
});

function showStep(step) {
    // Hide all steps
    for (let i = 1; i <= totalSteps; i++) {
        document.getElementById('step' + i).classList.add('hidden');
    }
    
    // Show current step
    document.getElementById('step' + step).classList.remove('hidden');
    
    // Update step indicators
    updateStepIndicators(step);
    
    // Update buttons
    updateButtons(step);
}

function updateStepIndicators(step) {
    for (let i = 1; i <= totalSteps; i++) {
        const circle = document.getElementById('step' + i + '-circle');
        const label = document.getElementById('step' + i + '-label');
        
        if (i < step) {
            // Completed step
            circle.classList.remove('border-gray-300', 'border-green-600', 'bg-green-600', 'text-white');
            circle.classList.add('border-green-600', 'bg-green-600', 'text-white');
            circle.innerHTML = '<i class="fas fa-check"></i>';
            label.classList.add('text-green-600');
            label.classList.remove('text-gray-500');
        } else if (i === step) {
            // Current step
            circle.classList.remove('border-gray-300', 'bg-white');
            circle.classList.add('border-green-600', 'bg-green-600', 'text-white');
            circle.textContent = i;
            label.classList.add('text-green-600');
            label.classList.remove('text-gray-500');
        } else {
            // Future step
            circle.classList.remove('border-green-600', 'bg-green-600', 'text-white');
            circle.classList.add('border-gray-300');
            circle.textContent = i;
            label.classList.remove('text-green-600');
            label.classList.add('text-gray-500');
        }
    }
    
    // Update connecting lines
    for (let i = 1; i < totalSteps; i++) {
        const line = document.getElementById('line' + i);
        if (i < step) {
            line.classList.remove('border-gray-300');
            line.classList.add('border-green-600');
        } else {
            line.classList.remove('border-green-600');
            line.classList.add('border-gray-300');
        }
    }
}

function updateButtons(step) {
    const prevBtn = document.getElementById('prevBtn');
    const nextBtn = document.getElementById('nextBtn');
    const submitBtn = document.getElementById('submitBtn');
    const cancelBtn = document.getElementById('cancelBtn');
    
    // Show/hide previous button
    if (step === 1) {
        prevBtn.classList.add('hidden');
        cancelBtn.classList.remove('hidden');
    } else {
        prevBtn.classList.remove('hidden');
        cancelBtn.classList.add('hidden');
    }
    
    // Show/hide next/submit button
    if (step === totalSteps) {
        nextBtn.classList.add('hidden');
        submitBtn.classList.remove('hidden');
    } else {
        nextBtn.classList.remove('hidden');
        submitBtn.classList.add('hidden');
    }
}

function changeStep(direction) {
    // Validate current step before moving
    if (direction === 1 && !validateStep(currentStep)) {
        return;
    }
    
    currentStep += direction;
    
    if (currentStep < 1) currentStep = 1;
    if (currentStep > totalSteps) currentStep = totalSteps;
    
    showStep(currentStep);
    
    // Scroll to top
    window.scrollTo({ top: 0, behavior: 'smooth' });
}

function validateStep(step) {
    let isValid = true;
    let errorMessage = '';
    
    if (step === 1) {
        const groundName = document.getElementById('ground_name').value.trim();
        const licenseNumber = document.getElementById('license_number').value.trim();
        const category = document.getElementById('category').value;
        
        if (!groundName) {
            errorMessage = 'Please enter the ground name';
            isValid = false;
        } else if (!licenseNumber) {
            errorMessage = 'Please enter the license number';
            isValid = false;
        } else if (!category) {
            errorMessage = 'Please select a category';
            isValid = false;
        }
    } else if (step === 2) {
        const priceDay = document.getElementById('price_day').value;
        const availableAtNight = document.getElementById('available_at_night').checked;
        const priceNight = document.getElementById('price_night').value;
        
        if (!priceDay || priceDay <= 0) {
            errorMessage = 'Please enter a valid day time price';
            isValid = false;
        } else if (availableAtNight && (!priceNight || priceNight <= 0)) {
            errorMessage = 'Please enter a valid night time price';
            isValid = false;
        }
    } else if (step === 3) {
        const images = document.getElementById('ground_images').files;
        if (images.length === 0) {
            errorMessage = 'Please upload at least one image of your ground';
            isValid = false;
        }
    } else if (step === 4) {
        const address = document.getElementById('business_address').value.trim();
        const contact = document.getElementById('contact_number').value.trim();
        const opening = document.getElementById('opening_time').value;
        const closing = document.getElementById('closing_time').value;
        const reason = document.getElementById('reason').value.trim();
        
        if (!address) {
            errorMessage = 'Please enter the business address';
            isValid = false;
        } else if (!contact) {
            errorMessage = 'Please enter the contact number';
            isValid = false;
        } else if (!opening || !closing) {
            errorMessage = 'Please select operating hours';
            isValid = false;
        } else if (!reason) {
            errorMessage = 'Please tell us why you want to become an owner';
            isValid = false;
        }
    }
    
    if (!isValid) {
        alert(errorMessage);
    }
    
    return isValid;
}

function toggleNightPrice() {
    const checkbox = document.getElementById('available_at_night');
    const container = document.getElementById('nightPriceContainer');
    const priceNight = document.getElementById('price_night');
    const nightTimeStart = document.getElementById('night_time_start');
    
    if (checkbox.checked) {
        container.classList.remove('hidden');
        priceNight.required = true;
        nightTimeStart.required = true;
    } else {
        container.classList.add('hidden');
        priceNight.required = false;
        nightTimeStart.required = false;
        priceNight.value = '';
        nightTimeStart.value = '18:00';
    }
    updatePricingPreview();
}

function updatePricingPreview() {
    const dayStart = document.getElementById('day_time_start').value;
    const dayPrice = document.getElementById('price_day').value;
    const nightStart = document.getElementById('night_time_start').value;
    const nightPrice = document.getElementById('price_night').value;
    const availableAtNight = document.getElementById('available_at_night').checked;
    
    const dayPreview = document.getElementById('dayPricePreview');
    const nightPreview = document.getElementById('nightPricePreview');
    
    if (dayStart && dayPrice) {
        dayPreview.textContent = `• Day Time: ${formatTime(dayStart)} onwards - BTN${parseFloat(dayPrice).toFixed(2)}/hour`;
    } else {
        dayPreview.textContent = '• Day Time: Set your pricing above';
    }
    
    if (availableAtNight) {
        nightPreview.classList.remove('hidden');
        if (nightStart && nightPrice) {
            nightPreview.textContent = `• Night Time: ${formatTime(nightStart)} onwards - BTN${parseFloat(nightPrice).toFixed(2)}/hour`;
        } else {
            nightPreview.textContent = '• Night Time: Set your pricing above';
        }
    } else {
        nightPreview.classList.add('hidden');
    }
}

function formatTime(time) {
    const [hours, minutes] = time.split(':');
    const hour = parseInt(hours);
    const ampm = hour >= 12 ? 'PM' : 'AM';
    const displayHour = hour % 12 || 12;
    return `${displayHour}:${minutes} ${ampm}`;
}

function toggleGroundSize() {
    const category = document.getElementById('category').value;
    const container = document.getElementById('groundSizeContainer');
    const teamSizeInput = document.getElementById('team_size');
    
    // Sports that require team size
    const sportsRequiringSize = ['Football', 'Cricket', 'Hockey', 'Basketball', 'Volleyball', 'Tennis', 'Badminton', 'Rugby'];
    
    if (category && sportsRequiringSize.includes(category)) {
        container.classList.remove('hidden');
        teamSizeInput.required = true;
    } else {
        container.classList.add('hidden');
        teamSizeInput.required = false;
        teamSizeInput.value = '';
    }
}

function previewImages(event) {
    const container = document.getElementById('imagePreviewContainer');
    const files = event.target.files;
    
    if (files.length === 0) {
        container.classList.add('hidden');
        return;
    }
    
    if (files.length > 5) {
        alert('You can only upload a maximum of 5 images');
        event.target.value = '';
        return;
    }
    
    container.innerHTML = '';
    container.classList.remove('hidden');
    
    Array.from(files).forEach((file, index) => {
        if (file.size > 10 * 1024 * 1024) {
            alert(`File ${file.name} is too large. Maximum size is 10MB`);
            return;
        }
        
        const reader = new FileReader();
        reader.onload = function(e) {
            const div = document.createElement('div');
            div.className = 'relative group';
            div.innerHTML = `
                <img src="${e.target.result}" class="w-full h-32 object-cover rounded-lg border-2 border-gray-200">
                <div class="absolute top-2 right-2 bg-green-600 text-white text-xs px-2 py-1 rounded">
                    Image ${index + 1}
                </div>
            `;
            container.appendChild(div);
        };
        reader.readAsDataURL(file);
    });
}
</script>
@endsection
