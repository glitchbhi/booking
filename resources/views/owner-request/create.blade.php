@extends('layouts.app')

@section('title', 'Request to Become Owner')

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="bg-white rounded-lg shadow-md p-6">
        <h1 class="text-2xl font-bold text-gray-900 mb-6">Request to Become a Ground Owner</h1>
        
        <div class="bg-blue-50 border border-blue-200 rounded-md p-4 mb-6">
            <h3 class="font-semibold text-blue-900 mb-2">Benefits of Becoming an Owner</h3>
            <ul class="list-disc list-inside text-blue-800 space-y-1 text-sm">
                <li>List and manage your sports grounds</li>
                <li>Set your own pricing and availability</li>
                <li>Accept both online and offline bookings</li>
                <li>Track revenue and analytics</li>
                <li>Earn 98% commission on all bookings</li>
            </ul>
        </div>

        <form action="{{ route('owner-request.store') }}" method="POST">
            @csrf
            
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Why do you want to become an owner? *</label>
                    <textarea name="reason" rows="3" required
                              class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500"
                              placeholder="Tell us why you want to list your grounds on our platform">{{ old('reason') }}</textarea>
                    @error('reason')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Business Details *</label>
                    <textarea name="business_details" rows="6" required
                              class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500"
                              placeholder="Please provide:
• Business name and address
• Contact phone number
• Type of grounds you own (Football, Cricket, etc.)
• Number of grounds
• Day time rate per hour
• Night time rate per hour (if applicable)
• Any additional information">{{ old('business_details') }}</textarea>
                    @error('business_details')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="bg-yellow-50 border border-yellow-200 rounded-md p-4">
                    <p class="text-sm text-yellow-800">
                        <i class="fas fa-info-circle"></i> <strong>Note:</strong> 
                        Your request will be reviewed by our admin team. You will receive an email notification once your request is processed.
                        No photos are required at this stage - you can add them after approval.
                    </p>
                </div>
            </div>

            <div class="mt-6 flex space-x-4">
                <button type="submit" class="flex-1 bg-indigo-600 text-white py-3 rounded-md hover:bg-indigo-700 font-semibold">
                    <i class="fas fa-paper-plane"></i> Submit Request
                </button>
                <a href="{{ route('welcome') }}" 
                   class="flex-1 bg-gray-200 text-gray-700 py-3 rounded-md hover:bg-gray-300 text-center font-semibold">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
