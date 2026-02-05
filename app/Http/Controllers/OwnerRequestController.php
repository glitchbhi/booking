<?php

namespace App\Http\Controllers;

use App\Services\OwnerRequestService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OwnerRequestController extends Controller
{
    public function __construct(
        protected OwnerRequestService $ownerRequestService
    ) {}

    public function create()
    {
        $existingRequest = Auth::user()->ownerRequest;
        
        if ($existingRequest && $existingRequest->isPending()) {
            return redirect()
                ->route('welcome')
                ->with('info', 'You already have a pending owner request');
        }

        return view('owner-request.create-multi-step');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'ground_name' => 'required|string|max:255',
            'license_number' => 'required|string|max:255|unique:owner_requests,license_number',
            'category' => 'required|string|max:255',
            'team_size' => 'nullable|integer|min:5|max:11',
            'day_time_start' => 'required|date_format:H:i',
            'price_day' => 'required|numeric|min:0',
            'price_night' => 'nullable|numeric|min:0',
            'night_time_start' => 'nullable|date_format:H:i',
            'available_at_night' => 'boolean',
            'ground_images.*' => 'required|image|mimes:jpeg,png,jpg|max:10240',
            'business_address' => 'required|string|max:500',
            'contact_number' => 'required|string|max:20',
            'opening_time' => 'required|date_format:H:i',
            'closing_time' => 'required|date_format:H:i',
            'facilities' => 'nullable|string|max:1000',
            'reason' => 'required|string|max:1000',
            'business_details' => 'nullable|string|max:2000',
        ]);

        try {
            // Handle image uploads
            $imagePaths = [];
            if ($request->hasFile('ground_images')) {
                foreach ($request->file('ground_images') as $image) {
                    $path = $image->store('ground-images', 'public');
                    $imagePaths[] = $path;
                }
            }

            $this->ownerRequestService->createRequest(
                Auth::user(),
                $validated['reason'],
                $validated['business_details'] ?? null,
                [
                    'ground_name' => $validated['ground_name'],
                    'license_number' => $validated['license_number'],
                    'category' => $validated['category'],
                    'team_size' => $validated['team_size'] ?? null,
                    'day_time_start' => $validated['day_time_start'],
                    'price_day' => $validated['price_day'],
                    'price_night' => $validated['price_night'] ?? null,
                    'night_time_start' => $validated['night_time_start'] ?? null,
                    'available_at_night' => $request->has('available_at_night'),
                    'ground_images' => $imagePaths,
                    'business_address' => $validated['business_address'],
                    'contact_number' => $validated['contact_number'],
                    'opening_time' => $validated['opening_time'],
                    'closing_time' => $validated['closing_time'],
                    'facilities' => $validated['facilities'] ?? null,
                ]
            );

            return redirect()
                ->route('welcome')
                ->with('success', 'Successfully added! Waiting for admin approval. You will receive an email notification once reviewed.');

        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', $e->getMessage());
        }
    }
}
