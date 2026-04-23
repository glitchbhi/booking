<nav class="bg-white border-b border-gray-100">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex items-center">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('welcome') }}" class="flex items-center space-x-2">
                        <div class="logo-circular">
                            <img src="{{ asset('images/logo.png') }}" alt="Thunder Booking">
                        </div>
                        <span class="text-xl sm:text-2xl font-bold text-blue-600 hidden sm:inline">Thunder Booking</span>
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden md:flex space-x-4 lg:space-x-8 ml-6 lg:ml-10">
                    <x-nav-link :href="route('welcome')" :active="request()->routeIs('welcome')">
                        {{ __('Home') }}
                    </x-nav-link>
                    <x-nav-link :href="route('bookings.index')" :active="request()->routeIs('bookings.*')">
                        {{ __('Bookings') }}
                    </x-nav-link>
                    <x-nav-link :href="route('system-ratings.index')" :active="request()->routeIs('system-ratings.*')">
                        {{ __('Ratings') }}
                    </x-nav-link>
                    
                    @if(Auth::user()->role === 'user' && Auth::user()->owner_status !== 'pending')
                        <x-nav-link :href="route('owner-request.create')" :active="request()->routeIs('owner-request.*')">
                            {{ __('Become Owner') }}
                        </x-nav-link>
                    @endif
                    
                    @if(Auth::user()->isOwner())
                        <x-nav-link :href="route('owner.dashboard')" :active="request()->routeIs('owner.*')">
                            {{ __('Owner') }}
                        </x-nav-link>
                    @endif
                    
                    @if(Auth::user()->isAdmin())
                        <x-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.*')">
                            {{ __('Admin') }}
                        </x-nav-link>
                    @endif
                </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="flex items-center">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-2 sm:px-3 py-2 border border-transparent text-xs sm:text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                            <i class="fas fa-user-circle text-lg mr-2"></i>
                            <div class="truncate max-w-[120px] sm:max-w-none">{{ Auth::user()->name ?? 'User' }}</div>

                            <div class="ml-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <!-- Mobile-only navigation links -->
                        <div class="md:hidden border-b border-gray-100 pb-2 mb-2">
                            <x-dropdown-link :href="route('welcome')">
                                <i class="fas fa-home w-5"></i> {{ __('Home') }}
                            </x-dropdown-link>
                            <x-dropdown-link :href="route('bookings.index')">
                                <i class="fas fa-calendar-check w-5"></i> {{ __('My Bookings') }}
                            </x-dropdown-link>
                            <x-dropdown-link :href="route('system-ratings.index')">
                                <i class="fas fa-star w-5"></i> {{ __('Ratings') }}
                            </x-dropdown-link>
                            
                            @if(Auth::user()->role === 'user' && Auth::user()->owner_status !== 'pending')
                                <x-dropdown-link :href="route('owner-request.create')">
                                    <i class="fas fa-user-tie w-5"></i> {{ __('Become Owner') }}
                                </x-dropdown-link>
                            @endif
                            
                            @if(Auth::user()->isOwner())
                                <x-dropdown-link :href="route('owner.dashboard')">
                                    <i class="fas fa-store w-5"></i> {{ __('Owner Panel') }}
                                </x-dropdown-link>
                            @endif
                            
                            @if(Auth::user()->isAdmin())
                                <x-dropdown-link :href="route('admin.dashboard')">
                                    <i class="fas fa-user-shield w-5"></i> {{ __('Admin Panel') }}
                                </x-dropdown-link>
                            @endif
                        </div>

                        <x-dropdown-link :href="route('profile.edit')">
                            <i class="fas fa-user-cog w-5"></i> {{ __('Profile') }}
                        </x-dropdown-link>

                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf

                            <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                <i class="fas fa-sign-out-alt w-5"></i> {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>
        </div>
    </div>
</nav>
