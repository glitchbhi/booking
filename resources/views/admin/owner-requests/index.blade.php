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
                                <p class="text-gray-600"><strong>Business:</strong> {{ $request->business_name }}</p>
                                <p class="text-gray-600"><strong>Address:</strong> {{ $request->business_address }}</p>
                                <p class="text-gray-600"><strong>Phone:</strong> {{ $request->phone }}</p>
                                @if($request->additional_info)
                                    <p class="text-gray-600 mt-2"><strong>Additional Info:</strong> {{ $request->additional_info }}</p>
                                @endif
                                <p class="text-sm text-gray-400 mt-2">Requested {{ $request->created_at->diffForHumans() }}</p>
                            </div>
                            <div class="flex space-x-2 ml-4">
                                <form action="{{ route('admin.owner-requests.approve', $request) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded-md hover:bg-green-700">
                                        <i class="fas fa-check"></i> Approve
                                    </button>
                                </form>
                                <form action="{{ route('admin.owner-requests.reject', $request) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded-md hover:bg-red-700">
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
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Business</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Phone</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($allRequests as $request)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">{{ $request->user->name }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">{{ $request->business_name }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">{{ $request->phone }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 py-1 text-xs rounded-full
                                        {{ $request->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                        {{ $request->status === 'approved' ? 'bg-green-100 text-green-800' : '' }}
                                        {{ $request->status === 'rejected' ? 'bg-red-100 text-red-800' : '' }}">
                                        {{ ucfirst($request->status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $request->created_at->format('M d, Y') }}</td>
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
