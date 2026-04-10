@extends('layouts.app')

@section('title', 'Manage Owner Requests')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <h1 class="text-3xl font-bold text-gray-900 mb-6">Owner Requests</h1>
    
    <!-- Pending Requests -->
    @if($pendingRequests->count() > 0)
        <div class="mb-8">
            <h2 class="text-xl font-bold text-gray-900 mb-4">Pending Requests ({{ $pendingRequests->count() }})</h2>
            <div class="space-y-4">
                @foreach($pendingRequests as $request)
                    <div class="bg-white rounded-lg shadow-md p-6">
                        <div class="flex justify-between items-start">
                            <div class="flex-1">
                                <h3 class="text-lg font-bold text-gray-900">{{ $request->user->name }}</h3>
                                <p class="text-gray-600 mt-1"><strong>Email:</strong> {{ $request->user->email }}</p>
                                <p class="text-gray-600"><strong>Ground Name:</strong> {{ $request->ground_name }}</p>
                                <p class="text-gray-600"><strong>Category:</strong> {{ $request->category }}</p>
                                <p class="text-gray-600"><strong>Address:</strong> {{ $request->business_address }}</p>
                                <p class="text-gray-600"><strong>Contact:</strong> {{ $request->contact_number }}</p>
                                <p class="text-gray-600"><strong>Opening Time:</strong> {{ $request->opening_time }} - {{ $request->closing_time }}</p>
                                <p class="text-gray-600"><strong>Day Pricing:</strong> BTN {{ number_format($request->price_day, 2) }} /hour</p>
                                @if($request->available_at_night)
                                    <p class="text-gray-600"><strong>Night Pricing:</strong> BTN {{ number_format($request->price_night, 2) }} /hour</p>
                                @endif
                                @if($request->reason)
                                    <p class="text-gray-600 mt-2"><strong>Reason:</strong> {{ $request->reason }}</p>
                                @endif
                                @if($request->business_details)
                                    <p class="text-gray-600"><strong>Business Details:</strong> {{ $request->business_details }}</p>
                                @endif
                                <p class="text-sm text-gray-400 mt-2">Requested {{ $request->created_at->diffForHumans() }}</p>
                            </div>
                            <div class="flex flex-col space-y-2 ml-4">
                                <a href="{{ route('admin.owner-requests.show', $request) }}" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 text-center">
                                    <i class="fas fa-eye"></i> View Details
                                </a>
                                <form action="{{ route('admin.owner-requests.approve', $request) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="w-full bg-green-600 text-white px-4 py-2 rounded-md hover:bg-green-700">
                                        <i class="fas fa-check"></i> Approve
                                    </button>
                                </form>
                                <form action="{{ route('admin.owner-requests.reject', $request) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="w-full bg-red-600 text-white px-4 py-2 rounded-md hover:bg-red-700">
                                        <i class="fas fa-times"></i> Reject
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @else
        <div class="bg-white rounded-lg shadow-md p-8 text-center mb-8">
            <i class="fas fa-inbox text-gray-400 text-6xl mb-4"></i>
            <p class="text-gray-600 text-lg">No pending requests.</p>
        </div>
    @endif

    <!-- All Requests History -->
    <div>
        <h2 class="text-xl font-bold text-gray-900 mb-4">Request History</h2>
        @if($allRequests->count() > 0)
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">User</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Ground Name</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Contact</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Action</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($allRequests as $request)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">{{ $request->user->name }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">{{ $request->ground_name }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">{{ $request->contact_number }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 py-1 text-xs rounded-full
                                        {{ $request->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                        {{ $request->status === 'approved' ? 'bg-green-100 text-green-800' : '' }}
                                        {{ $request->status === 'rejected' ? 'bg-red-100 text-red-800' : '' }}">
                                        {{ ucfirst($request->status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $request->created_at->format('M d, Y') }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    <a href="{{ route('admin.owner-requests.show', $request) }}" class="text-blue-600 hover:text-blue-900">
                                        View
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="mt-6">
                {{ $allRequests->links() }}
            </div>
        @else
            <p class="text-gray-600 text-center py-8">No requests found.</p>
        @endif
    </div>
</div>
@endsection
