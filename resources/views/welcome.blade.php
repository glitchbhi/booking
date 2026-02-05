<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <title>Thunder Booking - Find & Book Sports Facilities in Bhutan</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    screens: {
                        '3xl': '1920px',
                        '4xl': '2560px',
                    }
                }
            }
        }
    </script>
    <style>
        .hero-bg {
            background: linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5)), url('https://images.unsplash.com/photo-1551958219-acbc608c6377?ixlib=rb-4.0.3&auto=format&fit=crop&w=2070&q=80');
            background-size: cover;
            background-position: center;
        }
        .sport-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.15);
        }
        .venue-card:hover {
            transform: translateY(-8px);
        }
        
        /* TV Screen Optimizations */
        @media (min-width: 1920px) {
            body {
                font-size: 18px;
            }
            .container-tv {
                max-width: 1600px;
                margin-left: auto;
                margin-right: auto;
            }
        }
        
        @media (min-width: 2560px) {
            body {
                font-size: 22px;
            }
            .container-tv {
                max-width: 2000px;
            }
        }
    </style>
</head>
<body class="bg-gray-50">
    <!-- Navigation -->
    <nav class="bg-white shadow-sm fixed w-full z-50" x-data="{ userOpen: false }">
        <div class="container-tv max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 xl:px-12 2xl:px-16">
            <div class="flex justify-between items-center h-16">
                <div class="flex items-center space-x-4 sm:space-x-8">
                    <a href="{{ route('welcome') }}" class="flex items-center space-x-2">
                        <i class="fas fa-bolt text-xl sm:text-2xl text-green-500"></i>
                        <span class="text-lg sm:text-xl font-bold text-gray-900">ThunderBooking</span>
                    </a>
                    @auth
                    <div class="hidden md:flex space-x-6">
                        <a href="{{ route('bookings.index') }}" class="text-gray-600 hover:text-green-600 font-medium">My Bookings</a>
                        <a href="{{ route('wallet.index') }}" class="text-gray-600 hover:text-green-600 font-medium">Wallet</a>
                    </div>
                    @endauth
                </div>
                <div class="flex items-center space-x-3 sm:space-x-4">
                    @auth
                        @if(Auth::user()->role === 'user' && Auth::user()->owner_status !== 'pending')
                            <a href="{{ route('owner-request.create') }}" class="text-gray-600 hover:text-green-600 font-medium hidden md:inline">Become Owner</a>
                        @endif
                        @if(Auth::user()->isOwner())
                            <a href="{{ route('owner.dashboard') }}" class="text-gray-600 hover:text-green-600 font-medium hidden md:inline">Owner Panel</a>
                        @endif
                        @if(Auth::user()->isAdmin())
                            <a href="{{ route('admin.dashboard') }}" class="text-gray-600 hover:text-green-600 font-medium hidden md:inline">Admin Panel</a>
                        @endif
                        
                        <!-- User Dropdown (All Devices) -->
                        <div class="relative">
                            <button @click="userOpen = !userOpen" class="flex items-center space-x-1 sm:space-x-2 text-gray-700 hover:text-green-600 focus:outline-none px-2 sm:px-3 py-2 rounded-md hover:bg-gray-50">
                                <i class="fas fa-user-circle text-lg md:hidden"></i>
                                <span class="font-medium hidden md:inline text-sm sm:text-base">{{ Auth::user()->name }}</span>
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>
                            <div x-show="userOpen" @click.away="userOpen = false" x-cloak class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-50 border border-gray-200">
                                <!-- Mobile-only navigation links -->
                                <div class="md:hidden border-b border-gray-100 pb-2 mb-2">
                                    <a href="{{ route('bookings.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                        <i class="fas fa-calendar-check w-5 inline-block"></i> My Bookings
                                    </a>
                                    <a href="{{ route('wallet.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                        <i class="fas fa-wallet w-5 inline-block"></i> Wallet
                                    </a>
                                    @if(Auth::user()->role === 'user' && Auth::user()->owner_status !== 'pending')
                                        <a href="{{ route('owner-request.create') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                            <i class="fas fa-user-tie w-5 inline-block"></i> Become Owner
                                        </a>
                                    @endif
                                    @if(Auth::user()->isOwner())
                                        <a href="{{ route('owner.dashboard') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                            <i class="fas fa-store w-5 inline-block"></i> Owner Panel
                                        </a>
                                    @endif
                                    @if(Auth::user()->isAdmin())
                                        <a href="{{ route('admin.dashboard') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                            <i class="fas fa-user-shield w-5 inline-block"></i> Admin Panel
                                        </a>
                                    @endif
                                </div>
                                
                                <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    <i class="fas fa-user-cog w-5 inline-block"></i> Profile
                                </a>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                        <i class="fas fa-sign-out-alt w-5 inline-block"></i> Log Out
                                    </button>
                                </form>
                            </div>
                        </div>
                    @else
                        <a href="{{ route('login') }}" class="text-gray-700 hover:text-green-600 px-3 py-1.5 sm:px-4 sm:py-2 text-sm sm:text-base font-medium rounded-md hover:bg-gray-50 transition">Login</a>
                        <a href="{{ route('register') }}" class="bg-green-600 text-white px-3 py-1.5 sm:px-5 sm:py-2 rounded-full font-medium hover:bg-green-700 transition shadow-sm text-sm sm:text-base">Sign Up</a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero-bg pt-32 pb-40 text-white">
        <div class="container-tv max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 xl:px-12 2xl:px-16 text-center">
            <h1 class="text-3xl sm:text-4xl md:text-5xl lg:text-6xl xl:text-7xl 2xl:text-8xl 3xl:text-9xl font-bold mb-6 lg:mb-8 2xl:mb-12 leading-tight">Find & Book the Best Sports<br class="hidden sm:block">Facilities in Bhutan</h1>
            <p class="text-base sm:text-lg md:text-xl lg:text-2xl 2xl:text-3xl 3xl:text-4xl text-gray-200 mb-12 2xl:mb-16 3xl:mb-20 max-w-3xl 2xl:max-w-5xl mx-auto">Instant access to football turfs, cricket grounds, archery ranges, and more.</p>
            
            <!-- Search Box -->
            <div class="bg-white rounded-xl 2xl:rounded-2xl shadow-2xl p-4 md:p-6 2xl:p-10 3xl:p-12 max-w-4xl 2xl:max-w-6xl mx-auto">
                <form action="{{ route('grounds.browse') }}" method="GET" class="flex flex-col md:flex-row gap-4 2xl:gap-6">
                    <div class="flex-1">
                        <label class="block text-left text-xs 2xl:text-sm text-gray-500 mb-1 2xl:mb-2 uppercase tracking-wide">Select Sport</label>
                        <select name="sport_type" class="w-full px-4 2xl:px-6 py-3 2xl:py-5 3xl:py-6 border border-gray-200 rounded-lg 2xl:rounded-xl text-gray-700 text-base 2xl:text-xl focus:ring-2 focus:ring-green-500 focus:border-transparent">
                            <option value="">All Sports</option>
                            @foreach($sportsTypes as $sport)
                                <option value="{{ $sport->id }}">{{ $sport->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="flex-1">
                        <label class="block text-left text-xs 2xl:text-sm text-gray-500 mb-1 2xl:mb-2 uppercase tracking-wide">Location</label>
                        <div class="relative">
                            <i class="fas fa-map-marker-alt absolute left-4 2xl:left-6 top-1/2 -translate-y-1/2 text-gray-400 text-base 2xl:text-xl"></i>
                            <input type="text" name="location" placeholder="Thimphu, Paro, Punakha..." class="w-full pl-10 2xl:pl-14 pr-4 2xl:pr-6 py-3 2xl:py-5 3xl:py-6 border border-gray-200 rounded-lg 2xl:rounded-xl text-gray-700 text-base 2xl:text-xl focus:ring-2 focus:ring-green-500 focus:border-transparent">
                        </div>
                    </div>
                    <div class="flex-1">
                        <label class="block text-left text-xs 2xl:text-sm text-gray-500 mb-1 2xl:mb-2 uppercase tracking-wide">Search</label>
                        <input type="text" name="search" placeholder="Ground name..." class="w-full px-4 2xl:px-6 py-3 2xl:py-5 3xl:py-6 border border-gray-200 rounded-lg 2xl:rounded-xl text-gray-700 text-base 2xl:text-xl focus:ring-2 focus:ring-green-500 focus:border-transparent">
                    </div>
                    <div class="flex items-end">
                        <button type="submit" class="w-full md:w-auto bg-green-500 hover:bg-green-600 text-white px-8 2xl:px-12 3xl:px-16 py-3 2xl:py-5 3xl:py-6 rounded-lg 2xl:rounded-xl font-semibold text-base 2xl:text-xl 3xl:text-2xl transition">
                            Search Venues
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </section>

    <!-- Sport Categories -->
    <section class="py-12 lg:py-16 2xl:py-20 bg-white border-b">
        <div class="container-tv max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 xl:px-12 2xl:px-16">
            <div class="flex flex-wrap justify-center gap-6 sm:gap-8 md:gap-12 lg:gap-16 xl:gap-20 2xl:gap-24">
                @php
                    $sportIcons = [
                        'Football' => 'fa-futbol',
                        'Cricket' => 'fa-baseball-bat-ball',
                        'Badminton' => 'fa-table-tennis-paddle-ball',
                        'Tennis' => 'fa-table-tennis-paddle-ball',
                        'Basketball' => 'fa-basketball',
                        'Archery' => 'fa-bullseye',
                        'Khuru' => 'fa-bullseye',
                        'Swimming' => 'fa-person-swimming',
                        'Volleyball' => 'fa-volleyball',
                        'Table Tennis' => 'fa-table-tennis-paddle-ball',
                    ];
                @endphp
                @foreach($sportsTypes->take(6) as $sport)
                    <a href="{{ route('grounds.browse', ['sport_type' => $sport->id]) }}" class="flex flex-col items-center group">
                        <div class="w-16 h-16 lg:w-20 lg:h-20 xl:w-24 xl:h-24 2xl:w-32 2xl:h-32 3xl:w-40 3xl:h-40 flex items-center justify-center rounded-full bg-gray-100 group-hover:bg-green-100 transition mb-3 2xl:mb-4">
                            <i class="fas {{ $sportIcons[$sport->name] ?? 'fa-medal' }} text-2xl lg:text-3xl xl:text-4xl 2xl:text-5xl 3xl:text-6xl text-gray-600 group-hover:text-green-600 transition"></i>
                        </div>
                        <span class="text-sm lg:text-base xl:text-lg 2xl:text-2xl 3xl:text-3xl text-gray-600 group-hover:text-green-600 font-medium">{{ $sport->name }}</span>
                    </a>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Popular Venues -->
    <section class="py-16 lg:py-20 2xl:py-24 bg-gray-50">
        <div class="container-tv max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 xl:px-12 2xl:px-16">
            <div class="flex justify-between items-center mb-10 lg:mb-12 2xl:mb-16">
                <h2 class="text-2xl md:text-3xl lg:text-4xl xl:text-5xl 2xl:text-6xl 3xl:text-7xl font-bold text-gray-900">Popular Venues in Bhutan</h2>
            </div>
            
            @php
                // Placeholder images for different sport types
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
            @endphp
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-3 gap-6 lg:gap-8">
                @forelse($popularGrounds as $ground)
                    @php
                        $sportName = $ground->sportType->name ?? 'default';
                        $placeholderImage = $placeholderImages[$sportName] ?? $placeholderImages['default'];
                        $groundImages = $ground->images && is_array($ground->images) ? $ground->images : [];
                    @endphp
                    <a href="{{ route('grounds.show', $ground) }}" class="block bg-white rounded-xl shadow-md overflow-hidden venue-card hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 cursor-pointer">
                        <!-- Image Carousel -->
                        <div class="relative">
                            <x-image-carousel 
                                :images="$groundImages" 
                                :placeholder="$placeholderImage" 
                                :alt="$ground->name"
                                height="h-48"
                            />
                        </div>
                        
                        <div class="p-4">
                            <h3 class="font-bold text-lg text-gray-900 mb-1 line-clamp-1">{{ $ground->name }}</h3>
                            <div class="flex items-center text-sm text-gray-500 mb-3">
                                <i class="fas fa-map-marker-alt text-green-500 mr-1.5"></i>
                                <span class="line-clamp-1">{{ $ground->location }}</span>
                            </div>
                            
                            <!-- Tags -->
                            <div class="flex flex-wrap gap-2 mb-4">
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
                            
                            <div class="flex items-center justify-between pt-3 border-t border-gray-100">
                                <div class="flex-1">
                                    <span class="text-xs text-gray-500">Starting at</span>
                                    <p class="text-green-600 font-bold text-lg 2xl:text-xl">BTN {{ number_format($ground->rate_per_hour) }} <span class="text-sm font-normal text-gray-500">/ hr</span></p>
                                    @if($ground->total_reviews > 0)
                                        <div class="flex items-center mt-1">
                                            <div class="flex text-yellow-400 text-xs">
                                                @for($i = 1; $i <= 5; $i++)
                                                    @if($i <= floor($ground->average_rating))
                                                        <i class="fas fa-star"></i>
                                                    @elseif($i - 0.5 <= $ground->average_rating)
                                                        <i class="fas fa-star-half-alt"></i>
                                                    @else
                                                        <i class="far fa-star"></i>
                                                    @endif
                                                @endfor
                                            </div>
                                            <span class="text-xs text-gray-500 ml-1">({{ $ground->total_reviews }})</span>
                                        </div>
                                    @endif
                                </div>
                                <div class="flex flex-col space-y-1">
                                    <span class="bg-green-600 text-white px-3 py-1.5 2xl:px-4 2xl:py-2 rounded-lg text-xs 2xl:text-sm font-medium text-center">
                                        View Details
                                    </span>
                                    @auth
                                        @if(auth()->user()->hasVerifiedEmail())
                                            <span class="bg-blue-600 text-white px-3 py-1.5 2xl:px-4 2xl:py-2 rounded-lg text-xs 2xl:text-sm font-medium text-center">
                                                <i class="fas fa-star mr-1"></i> Ratings
                                            </span>
                                        @endif
                                    @endauth
                                </div>
                            </div>
                        </div>
                    </a>
                @empty
                    <div class="col-span-3 text-center py-12">
                        <i class="fas fa-store text-gray-300 text-6xl mb-4"></i>
                        <p class="text-gray-500">No venues available yet. Check back soon!</p>
                    </div>
                @endforelse
            </div>
            
            <!-- See All Grounds Button -->
            <div class="text-center mt-10">
                <a href="{{ route('grounds.browse') }}" class="inline-block bg-green-600 hover:bg-green-700 text-white px-8 py-4 rounded-full font-semibold text-lg transition shadow-lg">
                    <i class="fas fa-th-large mr-2"></i> See All Grounds
                </a>
            </div>
        </div>
    </section>

    <!-- Why Choose Us -->
    <section class="py-16 lg:py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid md:grid-cols-2 gap-12 lg:gap-16 items-center">
                <div>
                    <p class="text-green-600 font-semibold mb-2 uppercase tracking-wide text-sm lg:text-base">Why Choose Us</p>
                    <h2 class="text-2xl md:text-3xl lg:text-4xl xl:text-5xl font-bold text-gray-900 mb-6 lg:mb-8">Premium Amenities for Peak Performance</h2>
                    <p class="text-gray-600 mb-6 lg:mb-8 text-base lg:text-lg leading-relaxed">We partner with facilities that maintain the highest standards of hygiene, equipment quality, and player comfort. Whether you're training for a championship or playing a friendly match, enjoy a hassle-free experience with top-tier amenities.</p>
                    <a href="{{ route('grounds.browse') }}" class="text-green-600 font-semibold hover:text-green-700 text-base lg:text-lg">Explore Facilities →</a>
                </div>
                <div class="grid grid-cols-2 gap-4 lg:gap-6">
                    <div class="bg-gray-50 rounded-xl p-6 text-center">
                        <div class="w-14 h-14 bg-green-100 rounded-xl flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-lock text-green-600 text-xl"></i>
                        </div>
                        <h3 class="font-semibold text-gray-900 mb-1">Secure Lockers</h3>
                        <p class="text-sm text-gray-500">Keep your valuables safe</p>
                    </div>
                    <div class="bg-gray-50 rounded-xl p-6 text-center">
                        <div class="w-14 h-14 bg-green-100 rounded-xl flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-utensils text-green-600 text-xl"></i>
                        </div>
                        <h3 class="font-semibold text-gray-900 mb-1">Cafeteria</h3>
                        <p class="text-sm text-gray-500">Refreshments & snacks</p>
                    </div>
                    <div class="bg-gray-50 rounded-xl p-6 text-center">
                        <div class="w-14 h-14 bg-green-100 rounded-xl flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-restroom text-green-600 text-xl"></i>
                        </div>
                        <h3 class="font-semibold text-gray-900 mb-1">Changing Rooms</h3>
                        <p class="text-sm text-gray-500">Clean showers & hygiene</p>
                    </div>
                    <div class="bg-gray-50 rounded-xl p-6 text-center">
                        <div class="w-14 h-14 bg-green-100 rounded-xl flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-lightbulb text-green-600 text-xl"></i>
                        </div>
                        <h3 class="font-semibold text-gray-900 mb-1">Night Lighting</h3>
                        <p class="text-sm text-gray-500">FIFA standard floodlights</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- How It Works -->
    <section id="how-it-works" class="py-16 lg:py-20 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-2xl md:text-3xl lg:text-4xl xl:text-5xl font-bold text-gray-900 mb-4 lg:mb-6">It's Easier Than You Think</h2>
            <p class="text-gray-600 mb-12 lg:mb-16 text-base lg:text-xl max-w-3xl mx-auto">From discovery to game time in three simple steps.</p>
            
            <div class="grid md:grid-cols-3 gap-8 lg:gap-12 max-w-5xl mx-auto">
                <div class="flex flex-col items-center">
                    <div class="w-20 h-20 lg:w-24 lg:h-24 xl:w-28 xl:h-28 bg-green-100 rounded-full flex items-center justify-center mb-6 lg:mb-8">
                        <i class="fas fa-search text-green-600 text-3xl lg:text-4xl xl:text-5xl"></i>
                    </div>
                    <h3 class="font-bold text-xl lg:text-2xl text-gray-900 mb-3">Search</h3>
                    <p class="text-gray-500 text-base lg:text-lg">Find nearby courts, turfs, and pools based on your location and preferred sport.</p>
                </div>
                <div class="flex flex-col items-center">
                    <div class="w-20 h-20 lg:w-24 lg:h-24 xl:w-28 xl:h-28 bg-green-100 rounded-full flex items-center justify-center mb-6 lg:mb-8">
                        <i class="fas fa-calendar-check text-green-600 text-3xl lg:text-4xl xl:text-5xl"></i>
                    </div>
                    <h3 class="font-bold text-xl lg:text-2xl text-gray-900 mb-3">Book</h3>
                    <p class="text-gray-500 text-base lg:text-lg">Check availability in real-time and secure your preferred time slot instantly.</p>
                </div>
                <div class="flex flex-col items-center">
                    <div class="w-20 h-20 lg:w-24 lg:h-24 xl:w-28 xl:h-28 bg-green-100 rounded-full flex items-center justify-center mb-6 lg:mb-8">
                        <i class="fas fa-play text-green-600 text-3xl lg:text-4xl xl:text-5xl"></i>
                    </div>
                    <h3 class="font-bold text-xl lg:text-2xl text-gray-900 mb-3">Play</h3>
                    <p class="text-gray-500 text-base lg:text-lg">Receive your booking confirmation, show up at the venue, and enjoy the game.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-20 lg:py-28 bg-gradient-to-r from-green-600 to-green-700">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-2xl md:text-3xl lg:text-4xl xl:text-5xl font-bold text-white mb-8 lg:mb-12">Ready to get in the game?</h2>
            <a href="{{ route('grounds.browse') }}" class="inline-block bg-white text-green-600 px-8 lg:px-12 py-4 lg:py-5 rounded-full font-semibold text-lg lg:text-xl hover:bg-gray-100 transition shadow-lg hover:shadow-2xl transform hover:scale-105">
                <i class="fas fa-th-large mr-2"></i> Browse All Venues
            </a>
        </div>
    </section>

    <!-- System Rating Section -->
    <section class="py-12 2xl:py-20 3xl:py-24 bg-gradient-to-br from-green-50 to-blue-50">
        <div class="container-tv max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 xl:px-12 2xl:px-16">
            <div class="text-center mb-8 2xl:mb-12">
                <h2 class="text-2xl 2xl:text-4xl 3xl:text-5xl font-bold text-gray-900 mb-3 2xl:mb-4">
                    <i class="fas fa-star text-yellow-400 mr-2"></i> Rate Our System
                </h2>
                <p class="text-gray-600 text-base 2xl:text-xl 3xl:text-2xl">Help us improve by sharing your experience</p>
            </div>

            @php
                $systemAverage = \App\Models\SystemRating::getAverageRating();
                $systemTotal = \App\Models\SystemRating::getTotalCount();
            @endphp

            <div class="max-w-4xl mx-auto">
                <div class="bg-white rounded-2xl 2xl:rounded-3xl shadow-xl p-6 2xl:p-10 3xl:p-12">
                    <div class="flex flex-col md:flex-row items-center justify-between mb-6 2xl:mb-8">
                        <div class="text-center md:text-left mb-6 md:mb-0">
                            <div class="flex items-center justify-center md:justify-start mb-3">
                                <div class="text-5xl 2xl:text-7xl 3xl:text-8xl font-bold text-green-600 mr-4 2xl:mr-6">
                                    {{ number_format($systemAverage, 1) }}
                                </div>
                                <div>
                                    <div class="flex text-yellow-400 text-2xl 2xl:text-4xl 3xl:text-5xl mb-2">
                                        @for($i = 1; $i <= 5; $i++)
                                            @if($i <= floor($systemAverage))
                                                <i class="fas fa-star"></i>
                                            @elseif($i - 0.5 <= $systemAverage)
                                                <i class="fas fa-star-half-alt"></i>
                                            @else
                                                <i class="far fa-star"></i>
                                            @endif
                                        @endfor
                                    </div>
                                    <p class="text-gray-600 text-sm 2xl:text-lg 3xl:text-xl">
                                        {{ $systemTotal }} {{ Str::plural('rating', $systemTotal) }}
                                    </p>
                                </div>
                            </div>
                            <p class="text-gray-700 text-base 2xl:text-xl 3xl:text-2xl font-medium">Thunder Booking System</p>
                        </div>
                        
                        <div class="flex flex-col space-y-3 2xl:space-y-4">
                            <a href="{{ route('system-ratings.index') }}" 
                               class="inline-flex items-center justify-center px-6 2xl:px-8 py-3 2xl:py-4 bg-green-600 hover:bg-green-700 text-white font-medium rounded-lg 2xl:rounded-xl shadow-lg transition transform hover:scale-105 text-base 2xl:text-xl 3xl:text-2xl">
                                <i class="fas fa-eye mr-2"></i> View All Ratings
                            </a>
                            @auth
                                @if(auth()->user()->hasVerifiedEmail())
                                    @if(!auth()->user()->systemRating)
                                        <a href="{{ route('system-ratings.index') }}" 
                                           class="inline-flex items-center justify-center px-6 2xl:px-8 py-3 2xl:py-4 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg 2xl:rounded-xl shadow-lg transition transform hover:scale-105 text-base 2xl:text-xl 3xl:text-2xl">
                                            <i class="fas fa-plus mr-2"></i> Rate System
                                        </a>
                                    @endif
                                @else
                                    <span class="inline-flex items-center justify-center px-6 2xl:px-8 py-3 2xl:py-4 bg-yellow-100 text-yellow-800 font-medium rounded-lg 2xl:rounded-xl text-sm 2xl:text-base 3xl:text-lg">
                                        <i class="fas fa-envelope mr-2"></i> Verify email to rate
                                    </span>
                                @endif
                            @else
                                <a href="{{ route('login') }}" 
                                   class="inline-flex items-center justify-center px-6 2xl:px-8 py-3 2xl:py-4 bg-gray-600 hover:bg-gray-700 text-white font-medium rounded-lg 2xl:rounded-xl shadow-lg transition text-base 2xl:text-xl 3xl:text-2xl">
                                    <i class="fas fa-sign-in-alt mr-2"></i> Login to Rate
                                </a>
                            @endauth
                        </div>
                    </div>

                    <div class="border-t border-gray-200 pt-6 2xl:pt-8">
                        <p class="text-gray-600 text-center text-sm 2xl:text-lg 3xl:text-xl">
                            <i class="fas fa-info-circle mr-2"></i>
                            Your feedback helps us provide better service to all users
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Simple Footer -->
    <footer class="bg-gray-900 text-gray-400 py-6 2xl:py-10">
        <div class="container-tv max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 xl:px-12 2xl:px-16">
            <div class="flex flex-col md:flex-row justify-between items-center">
                <div class="flex items-center space-x-2 mb-4 md:mb-0">
                    <i class="fas fa-bolt text-xl 2xl:text-3xl text-green-500"></i>
                    <span class="text-lg 2xl:text-2xl 3xl:text-3xl font-bold text-white">ThunderBooking</span>
                </div>
                <p class="text-sm 2xl:text-lg 3xl:text-xl">&copy; {{ date('Y') }} ThunderBooking. All rights reserved.</p>
            </div>
        </div>
    </footer>
</body>
</html>
