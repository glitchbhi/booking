@props(['images' => [], 'placeholder' => '', 'alt' => 'Image', 'height' => 'h-48', 'showDots' => true, 'showArrows' => true])

@php
    $uniqueId = 'carousel-' . uniqid();
    $hasMultipleImages = is_array($images) && count($images) > 1;
    $hasImages = is_array($images) && count($images) > 0;
@endphp

<div x-data="{ 
    currentSlide: 0, 
    totalSlides: {{ $hasImages ? count($images) : 1 }},
    autoPlay: null,
    startAutoPlay() {
        this.autoPlay = setInterval(() => {
            this.nextSlide();
        }, 5000);
    },
    stopAutoPlay() {
        if (this.autoPlay) clearInterval(this.autoPlay);
    },
    nextSlide() {
        this.currentSlide = (this.currentSlide + 1) % this.totalSlides;
    },
    prevSlide() {
        this.currentSlide = (this.currentSlide - 1 + this.totalSlides) % this.totalSlides;
    },
    goToSlide(index) {
        this.currentSlide = index;
    }
}" 
    x-init="startAutoPlay()"
    @mouseenter="stopAutoPlay()"
    @mouseleave="startAutoPlay()"
    class="relative {{ $height }} overflow-hidden group bg-gray-100 rounded-t-xl"
    id="{{ $uniqueId }}"
>
    <!-- Slides Container -->
    <div class="relative w-full h-full">
        @if($hasImages)
            @foreach($images as $index => $image)
                <div 
                    x-show="currentSlide === {{ $index }}"
                    x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0 transform translate-x-full"
                    x-transition:enter-end="opacity-100 transform translate-x-0"
                    x-transition:leave="transition ease-in duration-300"
                    x-transition:leave-start="opacity-100 transform translate-x-0"
                    x-transition:leave-end="opacity-0 transform -translate-x-full"
                    class="absolute inset-0"
                >
                    <img 
                        src="{{ asset('storage/' . $image) }}" 
                        alt="{{ $alt }} - Image {{ $index + 1 }}" 
                        class="w-full h-full object-cover"
                        loading="{{ $index === 0 ? 'eager' : 'lazy' }}"
                        onerror="this.onerror=null; this.src='{{ $placeholder }}'; console.error('Image load failed:', '{{ asset('storage/' . $image) }}');"
                    >
                </div>
            @endforeach
        @else
            <!-- Placeholder Image -->
            <div class="absolute inset-0">
                <img 
                    src="{{ $placeholder }}" 
                    alt="{{ $alt }}" 
                    class="w-full h-full object-cover"
                    onerror="this.onerror=null; this.parentElement.innerHTML='<div class=\'w-full h-full bg-gradient-to-br from-green-400 to-green-600 flex items-center justify-center\'><i class=\'fas fa-image text-white text-5xl opacity-50\'></i></div>';"
                >
            </div>
        @endif
    </div>

    @if($hasMultipleImages && $showArrows)
        <!-- Navigation Arrows -->
        <button 
            @click.prevent="prevSlide()"
            class="absolute left-2 top-1/2 -translate-y-1/2 bg-black/40 hover:bg-black/60 text-white w-8 h-8 rounded-full flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-200 backdrop-blur-sm"
        >
            <i class="fas fa-chevron-left text-sm"></i>
        </button>
        <button 
            @click.prevent="nextSlide()"
            class="absolute right-2 top-1/2 -translate-y-1/2 bg-black/40 hover:bg-black/60 text-white w-8 h-8 rounded-full flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-200 backdrop-blur-sm"
        >
            <i class="fas fa-chevron-right text-sm"></i>
        </button>
    @endif

    @if($hasMultipleImages && $showDots)
        <!-- Dots Indicator -->
        <div class="absolute bottom-2 left-1/2 -translate-x-1/2 flex space-x-1.5">
            @foreach($images as $index => $image)
                <button 
                    @click.prevent="goToSlide({{ $index }})"
                    :class="currentSlide === {{ $index }} ? 'bg-white scale-110' : 'bg-white/50 hover:bg-white/75'"
                    class="w-2 h-2 rounded-full transition-all duration-200"
                ></button>
            @endforeach
        </div>
    @endif

    @if($hasMultipleImages)
        <!-- Image Counter Badge -->
        <div class="absolute top-2 left-2 bg-black/50 backdrop-blur-sm text-white text-xs px-2 py-1 rounded-full flex items-center space-x-1">
            <i class="fas fa-images text-xs"></i>
            <span x-text="(currentSlide + 1) + '/{{ count($images) }}'"></span>
        </div>
    @endif
</div>
