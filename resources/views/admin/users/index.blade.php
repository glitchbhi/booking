@extends('layouts.app')

@section('title', 'Manage Users')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-900">Manage Users</h1>
        <a href="{{ route('admin.users.create') }}" class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700">
            <i class="fas fa-plus"></i> Add User
        </a>
    </div>
    
    @if($users->count() > 0)
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Name</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Email</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Role</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Wallet</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($users as $user)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $user->name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $user->email }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                <span class="px-2 py-1 text-xs rounded-full
                                    {{ $user->role === 'admin' ? 'bg-purple-100 text-purple-800' : '' }}
                                    {{ $user->role === 'owner' ? 'bg-green-100 text-green-800' : '' }}
                                    {{ $user->role === 'user' ? 'bg-blue-100 text-blue-800' : '' }}">
                                    {{ ucfirst($user->role) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">BTN {{ number_format($user->wallet_balance, 2) }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($user->is_suspended)
                                    <span class="px-2 py-1 text-xs rounded-full bg-red-100 text-red-800">Suspended</span>
                                @else
                                    <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800">Active</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                <a href="{{ route('admin.users.show', $user) }}" class="text-indigo-600 hover:text-indigo-900 mr-3">
                                    <i class="fas fa-eye"></i> View
                                </a>
                                @if($user->role !== 'admin')
                                    @if($user->is_suspended)
                                        <form action="{{ route('admin.users.unsuspend', $user) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit" class="text-green-600 hover:text-green-900">
                                                <i class="fas fa-check"></i> Unsuspend
                                            </button>
                                        </form>
                                    @else
                                        <form action="{{ route('admin.users.suspend', $user) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit" class="text-red-600 hover:text-red-900">
                                                <i class="fas fa-ban"></i> Suspend
                                            </button>
                                        </form>
                                    @endif
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="mt-6">
            {{ $users->links() }}
        </div>
    @else
        <p class="text-gray-600 text-center py-8">No users found.</p>
    @endif
</div>
@endsection
