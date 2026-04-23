@php
    $ground = $ground ?? null;
    
    // Get actual uploaded images from database only (limit to max 4)
    $groundImages = $ground->images && is_array($ground->images) && count($ground->images) > 0 
        ? array_slice($ground->images, 0, 4) 
        : [];
    $hasImages = count($groundImages) > 0;
@endphp

<a href="{{ route('grounds.show', $ground) }}" class="block bg-white rounded-xl shadow-md overflow-hidden hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 cursor-pointer">
    <!-- Image Carousel -->
    <div class="relative">
        @if($hasImages)
            <x-image-carousel 
                :images="$groundImages" 
                :alt="$ground->name"
                height="h-48"
            />
        @else
            <div class="h-48 bg-gradient-to-br from-gray-100 to-gray-200 flex flex-col items-center justify-center">
                <i class="fas fa-image text-gray-400 text-4xl mb-2"></i>
                <span class="text-gray-500 text-sm font-medium">No images uploaded</span>
            </div>
        @endif
        
        <!-- Maintenance Badge -->
        @if($ground->is_under_maintenance)
            <div class="absolute inset-0 bg-black/40 flex items-center justify-center z-20">
                <div class="bg-yellow-500 text-white px-4 py-2 rounded-lg font-semibold flex items-center gap-2">
                    <i class="fas fa-tools"></i>
                    <span>Under Maintenance</span>
                </div>
            </div>
        @endif
        
        <!-- Rating Badge -->
        <div class="absolute top-2 right-2 bg-white/90 backdrop-blur-sm px-2 py-1 rounded-lg text-sm font-semibold flex items-center shadow-lg z-10">
            <i class="fas fa-star text-yellow-400 mr-1"></i>
            {{ number_format($ground->average_rating, 1) }}
        </div>
    </div>
    
    <div class="p-4">
        <h3 class="text-lg font-bold text-gray-900 line-clamp-1">{{ $ground->name }}</h3>
        
        <div class="flex items-center text-sm text-gray-500 mt-1">
            <i class="fas fa-map-marker-alt text-green-500 mr-1.5"></i>
            <span class="line-clamp-1">{{ $ground->location }}</span>
        </div>
        
        <!-- Sport & Capacity Tags -->
        <div class="flex flex-wrap gap-2 mt-3">
            <span class="inline-flex items-center px-2 py-1 bg-green-100 text-green-700 text-xs font-medium rounded-full">
                <i class="fas fa-running mr-1"></i> {{ $ground->sportType->name }}
            </span>
            @if($ground->capacity)
                <span class="inline-flex items-center px-2 py-1 bg-blue-100 text-blue-700 text-xs font-medium rounded-full">
                    <i class="fas fa-users mr-1"></i> {{ $ground->capacity }}
                </span>
            @endif
            @if($ground->night_rate_per_hour)
                <span class="inline-flex items-center px-2 py-1 bg-indigo-100 text-indigo-700 text-xs font-medium rounded-full">
                    <i class="fas fa-moon mr-1"></i> Night
                </span>
            @endif
        </div>
        
        <!-- Pricing -->
        <div class="flex items-center justify-between mt-4 pt-3 border-t border-gray-100">
            <div>
                <span class="text-xs text-gray-500 block">Starting at</span>
                <span class="text-xl font-bold text-gray-900">BTN {{ number_format($ground->rate_per_hour, 0) }}</span>
                <span class="text-sm text-gray-500">/hr</span>
            </div>
            <span class="bg-green-600 text-white px-4 py-2 rounded-lg text-sm font-medium">
                View Details
            </span>
        </div>
    </div>
</a>
