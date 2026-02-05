@extends('layouts.app')

@section('title', 'Owner Request Details')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="bg-white rounded-lg shadow-md p-6">
        <h1 class="text-2xl font-bold text-gray-900 mb-6">Owner Request Details</h1>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div>
                <h2 class="text-lg font-semibold text-gray-900 mb-4">User Information</h2>
                <div class="space-y-2">
                    <p class="text-gray-600"><strong>Name:</strong> {{ $ownerRequest->user->name }}</p>
                    <p class="text-gray-600"><strong>Email:</strong> {{ $ownerRequest->user->email }}</p>
                    <p class="text-gray-600"><strong>User Since:</strong> {{ $ownerRequest->user->created_at->format('M d, Y') }}</p>
                </div>
            </div>

            <div>
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Request Status</h2>
                <div class="space-y-2">
                    <p class="text-gray-600"><strong>Submitted:</strong> {{ $ownerRequest->created_at->format('M d, Y H:i') }}</p>
                    <p class="text-gray-600"><strong>Status:</strong> 
                        <span class="px-2 py-1 text-xs rounded-full
                            {{ $ownerRequest->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                            {{ $ownerRequest->status === 'approved' ? 'bg-green-100 text-green-800' : '' }}
                            {{ $ownerRequest->status === 'rejected' ? 'bg-red-100 text-red-800' : '' }}">
                            {{ ucfirst($ownerRequest->status) }}
                        </span>
                    </p>
                </div>
            </div>
        </div>

        @if($ownerRequest->ground_name)
            <div class="mb-6 bg-blue-50 rounded-lg p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Ground Information</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm text-gray-500">Ground Name</p>
                        <p class="text-gray-900 font-medium">{{ $ownerRequest->ground_name }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">License Number</p>
                        <p class="text-gray-900 font-medium">{{ $ownerRequest->license_number }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Category</p>
                        <p class="text-gray-900 font-medium">{{ $ownerRequest->category }}</p>
                    </div>
                    @if($ownerRequest->team_size)
                        <div>
                            <p class="text-sm text-gray-500">Team Size</p>
                            <p class="text-gray-900 font-medium">
                                <i class="fas fa-users mr-1"></i>
                                {{ $ownerRequest->team_size }} players per team
                            </p>
                        </div>
                    @endif
                    <div>
                        <p class="text-sm text-gray-500">Day Time Pricing</p>
                        <p class="text-gray-900 font-medium">
                            <i class="fas fa-sun mr-1 text-yellow-500"></i>
                            {{ date('g:i A', strtotime($ownerRequest->day_time_start ?? '06:00:00')) }} onwards - 
                            {{ number_format($ownerRequest->price_day, 2) }}/hour
                        </p>
                    </div>
                    @if($ownerRequest->available_at_night)
                        <div>
                            <p class="text-sm text-gray-500">Night Time Pricing</p>
                            <p class="text-gray-900 font-medium">
                                <i class="fas fa-moon mr-1 text-indigo-500"></i>
                                {{ date('g:i A', strtotime($ownerRequest->night_time_start ?? '18:00:00')) }} onwards - 
                                {{ number_format($ownerRequest->price_night, 2) }}/hour
                            </p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Night Availability</p>
                            <p class="text-green-600 font-medium"><i class="fas fa-check-circle"></i> Available at night</p>
                        </div>
                    @else
                        <div>
                            <p class="text-sm text-gray-500">Night Availability</p>
                            <p class="text-gray-500"><i class="fas fa-times-circle"></i> Not available at night</p>
                        </div>
                    @endif
                </div>

                @if($ownerRequest->ground_images && count($ownerRequest->ground_images) > 0)
                    <div class="mt-4">
                        <p class="text-sm text-gray-500 mb-2">Ground Images</p>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                            @foreach($ownerRequest->ground_images as $image)
                                <div class="relative group">
                                    <img src="{{ asset('storage/' . $image) }}" 
                                         alt="Ground image" 
                                         class="w-full h-32 object-cover rounded-lg border-2 border-gray-200 hover:border-green-500 cursor-pointer transition-colors"
                                         onclick="openImageModal('{{ asset('storage/' . $image) }}')">
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        @endif

        @if($ownerRequest->business_address || $ownerRequest->contact_number)
            <div class="mb-6 bg-green-50 rounded-lg p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Business & Contact Information</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @if($ownerRequest->business_address)
                        <div class="md:col-span-2">
                            <p class="text-sm text-gray-500">Business Address</p>
                            <p class="text-gray-900 font-medium">{{ $ownerRequest->business_address }}</p>
                        </div>
                    @endif
                    @if($ownerRequest->contact_number)
                        <div>
                            <p class="text-sm text-gray-500">Contact Number</p>
                            <p class="text-gray-900 font-medium">
                                <i class="fas fa-phone mr-1"></i> {{ $ownerRequest->contact_number }}
                            </p>
                        </div>
                    @endif
                    @if($ownerRequest->opening_time && $ownerRequest->closing_time)
                        <div>
                            <p class="text-sm text-gray-500">Operating Hours</p>
                            <p class="text-gray-900 font-medium">
                                <i class="fas fa-clock mr-1"></i> 
                                {{ date('g:i A', strtotime($ownerRequest->opening_time)) }} - 
                                {{ date('g:i A', strtotime($ownerRequest->closing_time)) }}
                            </p>
                        </div>
                    @endif
                    @if($ownerRequest->facilities)
                        <div class="md:col-span-2">
                            <p class="text-sm text-gray-500 mb-1">Facilities & Amenities</p>
                            <p class="text-gray-900">{{ $ownerRequest->facilities }}</p>
                        </div>
                    @endif
                </div>
            </div>
        @endif

        @if($ownerRequest->reason)
            <div class="mb-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-2">Reason for Request</h2>
                <p class="text-gray-600 bg-gray-50 rounded-md p-4 whitespace-pre-line">{{ $ownerRequest->reason }}</p>
            </div>
        @endif

        @if($ownerRequest->business_details)
            <div class="mb-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-2">Additional Information</h2>
                <p class="text-gray-600 bg-gray-50 rounded-md p-4 whitespace-pre-line">{{ $ownerRequest->business_details }}</p>
            </div>
        @endif

        @if($ownerRequest->status === 'pending')
            <div class="border-t pt-6 mt-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Take Action</h2>
                <div class="flex flex-col sm:flex-row gap-4">
                    <form action="{{ route('admin.owner-requests.approve', $ownerRequest) }}" method="POST" class="flex-1">
                        @csrf
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Admin Notes (Optional)</label>
                            <textarea name="notes" rows="2" 
                                      class="w-full px-3 py-2 border border-gray-300 rounded-md"
                                      placeholder="Add notes for the user..."></textarea>
                        </div>
                        <button type="submit" class="w-full bg-green-600 text-white py-3 rounded-md hover:bg-green-700 font-semibold transition-colors">
                            <i class="fas fa-check"></i> Approve Request
                        </button>
                    </form>
                    <form action="{{ route('admin.owner-requests.reject', $ownerRequest) }}" method="POST" class="flex-1">
                        @csrf
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Rejection Reason (Optional)</label>
                            <textarea name="reason" rows="2" 
                                      class="w-full px-3 py-2 border border-gray-300 rounded-md"
                                      placeholder="Explain why the request is rejected..."></textarea>
                        </div>
                        <button type="submit" class="w-full bg-red-600 text-white py-3 rounded-md hover:bg-red-700 font-semibold transition-colors">
                            <i class="fas fa-times"></i> Reject Request
                        </button>
                    </form>
                </div>
            </div>
        @else
            @if($ownerRequest->reviewer)
                <div class="border-t pt-6 mt-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-2">Review Information</h2>
                    <p class="text-gray-600"><strong>Reviewed by:</strong> {{ $ownerRequest->reviewer->name }}</p>
                    <p class="text-gray-600"><strong>Reviewed at:</strong> {{ $ownerRequest->reviewed_at?->format('M d, Y H:i') }}</p>
                    @if($ownerRequest->admin_notes)
                        <p class="text-gray-600 mt-2"><strong>Notes:</strong> {{ $ownerRequest->admin_notes }}</p>
                    @endif
                </div>
            @endif
        @endif

        <div class="mt-6">
            <a href="{{ route('admin.owner-requests.index') }}" class="text-indigo-600 hover:text-indigo-800">
                <i class="fas fa-arrow-left"></i> Back to Requests
            </a>
        </div>
    </div>
</div>

<!-- Image Modal -->
<div id="imageModal" class="hidden fixed inset-0 bg-black bg-opacity-75 z-50 flex items-center justify-center p-4" onclick="closeImageModal()">
    <div class="relative max-w-4xl max-h-full">
        <button onclick="closeImageModal()" class="absolute top-4 right-4 text-white text-3xl font-bold hover:text-gray-300">&times;</button>
        <img id="modalImage" src="" alt="Full size image" class="max-w-full max-h-screen rounded-lg">
    </div>
</div>

<script>
function openImageModal(imageSrc) {
    document.getElementById('imageModal').classList.remove('hidden');
    document.getElementById('modalImage').src = imageSrc;
    document.body.style.overflow = 'hidden';
}

function closeImageModal() {
    document.getElementById('imageModal').classList.add('hidden');
    document.body.style.overflow = 'auto';
}
</script>
@endsection
