@extends('layouts.app')

@section('title', 'User Details - ' . $user->name)

@section('content')
<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <div class="flex justify-between items-start mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">{{ $user->name }}</h1>
                <p class="text-gray-600">{{ $user->email }}</p>
            </div>
            <div class="flex items-center space-x-2">
                <span class="px-3 py-1 rounded-full {{ $user->role === 'admin' ? 'bg-purple-100 text-purple-800' : ($user->role === 'owner' ? 'bg-green-100 text-green-800' : 'bg-blue-100 text-blue-800') }}">
                    {{ ucfirst($user->role) }}
                </span>
                @if($user->is_suspended)
                    <span class="px-3 py-1 rounded-full bg-red-100 text-red-800">Suspended</span>
                @endif
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
            <div class="bg-gray-50 rounded-md p-4">
                <p class="text-sm text-gray-600">Wallet Balance</p>
                <p class="text-2xl font-bold text-gray-900">BTN {{ number_format($user->wallet_balance, 2) }}</p>
            </div>
            <div class="bg-gray-50 rounded-md p-4">
                <p class="text-sm text-gray-600">Total Bookings</p>
                <p class="text-2xl font-bold text-gray-900">{{ $user->bookings->count() }}</p>
            </div>
            <div class="bg-gray-50 rounded-md p-4">
                <p class="text-sm text-gray-600">Cancellation Strikes</p>
                <p class="text-2xl font-bold text-gray-900">{{ $user->cancellation_strikes }}</p>
            </div>
        </div>

        @if($user->role === 'owner')
            <div class="mb-6 pb-6 border-b">
                <h2 class="text-lg font-semibold text-gray-900 mb-2">Owner Information</h2>
                <p class="text-gray-600">Total Grounds: {{ $user->grounds->count() }}</p>
            </div>
        @endif

        @if($user->is_suspended)
            <div class="bg-red-50 border border-red-200 rounded-md p-4 mb-6">
                <p class="text-red-800">
                    <strong>Account Suspended</strong><br>
                    Until: {{ $user->suspended_until ? \Carbon\Carbon::parse($user->suspended_until)->format('M d, Y h:i A') : 'Indefinitely' }}<br>
                    Reason: {{ $user->suspension_reason ?? 'Not specified' }}
                </p>
            </div>
        @endif

        <div class="flex space-x-4">
            @if($user->role !== 'admin')
                @if($user->is_suspended)
                    <form action="{{ route('admin.users.unsuspend', $user) }}" method="POST">
                        @csrf
                        <button type="submit" class="bg-green-600 text-white px-6 py-2 rounded-md hover:bg-green-700">
                            <i class="fas fa-check"></i> Unsuspend User
                        </button>
                    </form>
                @else
                    <form action="{{ route('admin.users.suspend', $user) }}" method="POST">
                        @csrf
                        <button type="submit" class="bg-red-600 text-white px-6 py-2 rounded-md hover:bg-red-700">
                            <i class="fas fa-ban"></i> Suspend User
                        </button>
                    </form>
                @endif
            @endif
            <a href="{{ route('admin.users.index') }}" class="bg-gray-200 text-gray-700 px-6 py-2 rounded-md hover:bg-gray-300">
                <i class="fas fa-arrow-left"></i> Back
            </a>
        </div>
    </div>

    <!-- Recent Bookings -->
    @if($user->bookings->count() > 0)
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-bold text-gray-900 mb-4">Recent Bookings</h2>
            <div class="space-y-3">
                @foreach($user->bookings()->latest()->take(5)->get() as $booking)
                    <div class="flex justify-between items-center border-b border-gray-200 pb-2">
                        <div>
                            <p class="text-sm font-semibold text-gray-900">{{ $booking->ground->name }}</p>
                            <p class="text-xs text-gray-500">{{ $booking->start_time->format('M d, Y') }}</p>
                        </div>
                        <div class="text-right">
                            <p class="text-sm font-semibold text-gray-900">BTN {{ number_format($booking->total_amount, 2) }}</p>
                            <span class="px-2 py-1 text-xs rounded-full
                                {{ $booking->status === 'booked' ? 'bg-blue-100 text-blue-800' : '' }}
                                {{ $booking->status === 'ongoing' ? 'bg-green-100 text-green-800' : '' }}
                                {{ $booking->status === 'completed' ? 'bg-gray-100 text-gray-800' : '' }}
                                {{ $booking->status === 'cancelled' ? 'bg-red-100 text-red-800' : '' }}">
                                {{ ucfirst($booking->status) }}
                            </span>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
</div>
@endsection
