@props(['ground'])

@php
    // Array of placeholder images for different sport types
    $placeholderImages = [
        'Football' => 'https://images.unsplash.com/photo-1551958219-acbc608c6377?w=400&h=300&fit=crop',
        'Cricket' => 'https://images.unsplash.com/photo-1531415074968-036ba1b575da?w=400&h=300&fit=crop',
        'Badminton' => 'https://images.unsplash.com/photo-1626224583764-f87db24ac4ea?w=400&h=300&fit=crop',
        'Tennis' => 'https://images.unsplash.com/photo-1554068865-24cecd4e34b8?w=400&h=300&fit=crop',
        'Basketball' => 'https://images.unsplash.com/photo-1546519638-68e109498ffc?w=400&h=300&fit=crop',
        'Archery' => 'https://images.unsplash.com/photo-1510925758641-869d353cecc7?w=400&h=300&fit=crop',
        'Swimming' => 'https://images.unsplash.com/photo-1576013551627-0cc20b96c2a7?w=400&h=300&fit=crop',
        'default' => 'https://images.unsplash.com/photo-1574629810360-7efbbe195018?w=400&h=300&fit=crop'
    ];
    
    $sportName = $ground->sportType->name ?? 'default';
    $placeholderImage = $placeholderImages[$sportName] ?? $placeholderImages['default'];
    
    // Check if ground has uploaded images
    $groundImages = $ground->images && is_array($ground->images) ? $ground->images : [];
@endphp

<a href="{{ route('grounds.show', $ground) }}" class="block bg-white rounded-xl shadow-md overflow-hidden hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 cursor-pointer">
    <!-- Image Carousel -->
    <div class="relative">
        <x-image-carousel 
            :images="$groundImages" 
            :placeholder="$placeholderImage" 
            :alt="$ground->name"
            height="h-48"
        />
        
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
                <span class="text-xl font-bold text-gray-900">{{ number_format($ground->rate_per_hour, 0) }}</span>
                <span class="text-sm text-gray-500">/hr</span>
            </div>
            <span class="bg-green-600 text-white px-4 py-2 rounded-lg text-sm font-medium">
                View Details
            </span>
        </div>
    </div>
</a>
