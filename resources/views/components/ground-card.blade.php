@php
    $ground = $ground ?? null;
    
    // Get actual uploaded images from database only (limit to max 4)
    $groundImages = $ground->images && is_array($ground->images) && count($ground->images) > 0 
        ? array_slice($ground->images, 0, 4) 
        : [];
    $hasImages = count($groundImages) > 0;
@endphp

<a href="{{ route('grounds.show', $ground) }}" class="block bg-white rounded-lg sm:rounded-xl shadow-md overflow-hidden hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 cursor-pointer w-full">
    <!-- Image Carousel -->
    <div class="relative">
        @if($hasImages)
            <x-image-carousel 
                :images="$groundImages" 
                :alt="$ground->name"
                height="h-36 sm:h-40 md:h-44 lg:h-48 xl:h-52"
            />
        @else
            <div class="h-36 sm:h-40 md:h-44 lg:h-48 xl:h-52 bg-gradient-to-br from-gray-100 to-gray-200 flex flex-col items-center justify-center">
                <i class="fas fa-image text-gray-400 text-2xl sm:text-3xl md:text-4xl mb-2"></i>
                <span class="text-gray-500 text-xs sm:text-sm font-medium">No images uploaded</span>
            </div>
        @endif
        
        <!-- Maintenance Badge -->
        @if($ground->is_under_maintenance)
            <div class="absolute inset-0 bg-black/40 flex items-center justify-center z-20">
                <div class="bg-yellow-500 text-white px-2 sm:px-3 py-1 sm:py-2 rounded-lg font-semibold flex items-center gap-1 sm:gap-2 text-xs sm:text-sm">
                    <i class="fas fa-tools text-xs sm:text-sm"></i>
                    <span class="hidden xs:inline">Under Maintenance</span>
                    <span class="xs:hidden">Maintenance</span>
                </div>
            </div>
        @endif
        
        <!-- Rating Badge -->
        <div class="absolute top-1 sm:top-2 right-1 sm:right-2 bg-white/90 backdrop-blur-sm px-1.5 sm:px-2 py-1 rounded-lg text-xs sm:text-sm font-semibold flex items-center shadow-lg z-10">
            <i class="fas fa-star text-yellow-400 mr-0.5 sm:mr-1 text-xs sm:text-sm"></i>
            {{ number_format($ground->average_rating, 1) }}
        </div>
    </div>
    
    <div class="p-3 sm:p-4">
        <h3 class="text-sm sm:text-base md:text-lg font-bold text-gray-900 line-clamp-1">{{ $ground->name }}</h3>
        
        <div class="flex items-center text-xs sm:text-sm text-gray-500 mt-1">
            <i class="fas fa-map-marker-alt text-green-500 mr-1 sm:mr-1.5 text-xs sm:text-sm"></i>
            <span class="line-clamp-1">{{ $ground->location }}</span>
        </div>
        
        <!-- Sport & Capacity Tags -->
        <div class="flex flex-wrap gap-1 sm:gap-2 mt-2 sm:mt-3">
            <span class="inline-flex items-center px-1.5 sm:px-2 py-0.5 sm:py-1 bg-green-100 text-green-700 text-xs font-medium rounded-full">
                <i class="fas fa-running mr-0.5 sm:mr-1 text-xs"></i> {{ $ground->sportType->name }}
            </span>
            @if($ground->capacity)
                <span class="inline-flex items-center px-1.5 sm:px-2 py-0.5 sm:py-1 bg-blue-100 text-blue-700 text-xs font-medium rounded-full">
                    <i class="fas fa-users mr-0.5 sm:mr-1 text-xs"></i> {{ $ground->capacity }}
                </span>
            @endif
            @if($ground->night_rate_per_hour)
                <span class="inline-flex items-center px-1.5 sm:px-2 py-0.5 sm:py-1 bg-indigo-100 text-indigo-700 text-xs font-medium rounded-full">
                    <i class="fas fa-moon mr-0.5 sm:mr-1 text-xs"></i> 
                    <span class="hidden sm:inline">Night</span>
                    <span class="sm:hidden">🌙</span>
                </span>
            @endif
        </div>
        
        <!-- Pricing -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mt-3 sm:mt-4 pt-2 sm:pt-3 border-t border-gray-100 gap-2 sm:gap-0">
            <div class="flex-1">
                <span class="text-xs text-gray-500 block">Starting at</span>
                <div class="flex items-baseline">
                    <span class="text-base sm:text-lg md:text-xl font-bold text-gray-900">BTN {{ number_format($ground->rate_per_hour, 0) }}</span>
                    <span class="text-xs sm:text-sm text-gray-500 ml-1">/hr</span>
                </div>
            </div>
            <span class="bg-green-600 hover:bg-green-700 text-white px-3 sm:px-4 py-1.5 sm:py-2 rounded-lg text-xs sm:text-sm font-medium transition text-center">
                <span class="hidden sm:inline">View Details</span>
                <span class="sm:hidden">View</span>
            </span>
        </div>
    </div>
</a>
