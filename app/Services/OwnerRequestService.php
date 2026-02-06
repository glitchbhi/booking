<?php

namespace App\Services;

use App\Models\Ground;
use App\Models\OwnerRequest;
use App\Models\SportsType;
use App\Models\User;
use App\Notifications\NewOwnerRequest;
use App\Notifications\OwnerRequestApproved;
use App\Notifications\OwnerRequestRejected;
use Illuminate\Support\Facades\DB;

class OwnerRequestService
{
    /**
     * Create owner request
     */
    public function createRequest(User $user, string $reason = null, string $businessDetails = null, array $groundDetails = []): OwnerRequest
    {
        // Check if user already has a pending request
        $existingRequest = $user->ownerRequest()
            ->where('status', 'pending')
            ->first();

        if ($existingRequest) {
            throw new \Exception('You already have a pending owner request');
        }

        // Check if user is already an owner
        if ($user->isOwner()) {
            throw new \Exception('You are already an approved owner');
        }

        $ownerRequest = OwnerRequest::create([
            'user_id' => $user->id,
            'ground_name' => $groundDetails['ground_name'] ?? null,
            'license_number' => $groundDetails['license_number'] ?? null,
            'category' => $groundDetails['category'] ?? null,
            'team_size' => $groundDetails['team_size'] ?? null,
            'day_time_start' => $groundDetails['day_time_start'] ?? '06:00:00',
            'price_day' => $groundDetails['price_day'] ?? null,
            'price_night' => $groundDetails['price_night'] ?? null,
            'night_time_start' => $groundDetails['night_time_start'] ?? '18:00:00',
            'available_at_night' => $groundDetails['available_at_night'] ?? false,
            'ground_images' => $groundDetails['ground_images'] ?? [],
            'business_address' => $groundDetails['business_address'] ?? null,
            'contact_number' => $groundDetails['contact_number'] ?? null,
            'opening_time' => $groundDetails['opening_time'] ?? null,
            'closing_time' => $groundDetails['closing_time'] ?? null,
            'facilities' => $groundDetails['facilities'] ?? null,
            'reason' => $reason,
            'business_details' => $businessDetails,
            'status' => 'pending',
        ]);

        // Update user's owner status
        $user->update(['owner_status' => 'pending']);

        // Send notifications asynchronously
        $this->sendOwnerRequestNotifications($ownerRequest, $user);

        return $ownerRequest;
    }

    /**
     * Send owner request notifications (called after transaction)
     */
    private function sendOwnerRequestNotifications($ownerRequest, $user)
    {
        // Notify all admins about the new request (non-blocking)
        dispatch(function () use ($ownerRequest) {
            $admins = User::where('role', 'admin')->get();
            foreach ($admins as $admin) {
                try {
                    $admin->notify(new NewOwnerRequest($ownerRequest));
                } catch (\Exception $e) {
                    \Log::warning('Admin notification email failed: ' . $e->getMessage());
                }
            }
        })->afterResponse();
        
        // Notify the user that request was submitted (non-blocking)
        dispatch(function () use ($ownerRequest, $user) {
            try {
                $user->notify(new \App\Notifications\OwnerRequestSubmitted($ownerRequest));
            } catch (\Exception $e) {
                \Log::warning('Owner request submitted email failed: ' . $e->getMessage());
            }
        })->afterResponse();
    }

    /**
     * Approve owner request
     */
    public function approveRequest(OwnerRequest $request, User $admin, string $notes = null): bool
    {
        return DB::transaction(function () use ($request, $admin, $notes) {
            $request->update([
                'status' => 'approved',
                'reviewed_by' => $admin->id,
                'admin_notes' => $notes,
                'reviewed_at' => now(),
            ]);

            // Update user role and status
            $request->user->update([
                'role' => 'owner',
                'owner_status' => 'approved',
            ]);
            
            // Automatically create ground from owner request data
            // Ground is created as inactive and requires admin approval to go live
            if ($request->ground_name && $request->category) {
                // Find the sport type
                $sportType = SportsType::where('name', $request->category)->first();
                
                if ($sportType) {
                    $ground = Ground::create([
                        'owner_id' => $request->user_id,
                        'sport_type_id' => $sportType->id,
                        'name' => $request->ground_name,
                        'description' => $this->generateGroundDescription($request),
                        'location' => $this->extractLocation($request->business_address),
                        'address' => $request->business_address ?? '',
                        'phone' => $request->contact_number ?? '',
                        'rate_per_hour' => $request->price_day ?? 0,
                        'night_rate_per_hour' => $request->price_night,
                        'capacity' => $this->formatCapacity($request->team_size, $request->category),
                        'capacity_description' => $request->team_size ? "{$request->team_size} players per team" : null,
                        'day_rate_start' => $request->day_time_start ?? '06:00:00',
                        'day_rate_end' => $request->night_time_start ?? '18:00:00',
                        'night_rate_start' => $request->night_time_start ?? '18:00:00',
                        'night_rate_end' => $request->closing_time ?? '23:00:00',
                        'images' => $request->ground_images ?? [],
                        'is_active' => true, // Active immediately when owner is approved
                    ]);

                    // Create default availabilities based on operating hours
                    $days = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];
                    foreach ($days as $day) {
                        $ground->availabilities()->create([
                            'day_of_week' => $day,
                            'start_time' => $request->opening_time ?? '06:00:00',
                            'end_time' => $request->closing_time ?? '23:00:00',
                            'is_active' => true,
                        ]);
                    }
                }
            }
            
            return true;
        });

        // Send approval notification after transaction (non-blocking)
        dispatch(function () use ($request, $notes) {
            try {
                $request->user->notify(new OwnerRequestApproved($notes ?? ''));
            } catch (\Exception $e) {
                \Log::warning('Owner approval email failed: ' . $e->getMessage());
            }
        })->afterResponse();

        return true;
    }

    /**
     * Format capacity based on team size and sport category
     */
    private function formatCapacity($teamSize, $category): ?string
    {
        if (!$teamSize) {
            return null;
        }
        
        // Format based on common conventions
        if (in_array($category, ['Football', 'Soccer'])) {
            return "{$teamSize}-a-side";
        }
        
        if (in_array($category, ['Basketball', 'Volleyball'])) {
            return "{$teamSize}v{$teamSize}";
        }
        
        if (in_array($category, ['Cricket'])) {
            return "{$teamSize}-players";
        }
        
        // Default format
        return "{$teamSize} players";
    }
    
    /**
     * Generate ground description from owner request data
     */
    private function generateGroundDescription(OwnerRequest $request): string
    {
        $description = "{$request->category} ground";
        
        if ($request->team_size) {
            $description .= " suitable for {$request->team_size} players per team";
        }
        
        if ($request->facilities) {
            $description .= ". Facilities: {$request->facilities}";
        }
        
        if ($request->available_at_night) {
            $description .= ". Available for night bookings.";
        }
        
        return $description;
    }

    /**
     * Extract location from full address
     */
    private function extractLocation(string $address): string
    {
        // Try to extract city/area from address
        // Simple logic: take the first part before comma or the whole address if short
        $parts = explode(',', $address);
        if (count($parts) > 1) {
            // Return city/area (usually second last part)
            return trim($parts[count($parts) - 2] ?? $parts[0]);
        }
        
        // If address is short, return as is
        return strlen($address) > 50 ? substr($address, 0, 50) . '...' : $address;
    }

    /**
     * Reject owner request
     */
    public function rejectRequest(OwnerRequest $request, User $admin, string $reason = null): bool
    {
        return DB::transaction(function () use ($request, $admin, $reason) {
            $request->update([
                'status' => 'rejected',
                'reviewed_by' => $admin->id,
                'admin_notes' => $reason,
                'reviewed_at' => now(),
            ]);

            // Update user status
            $request->user->update([
                'owner_status' => 'rejected',
            ]);
            
            return true;
        });

        // Send rejection notification after transaction (non-blocking)
        dispatch(function () use ($request, $reason) {
            try {
                $request->user->notify(new OwnerRequestRejected($reason ?? ''));
            } catch (\Exception $e) {
                \Log::warning('Owner rejection email failed: ' . $e->getMessage());
            }
        })->afterResponse();

        return true;
    }

    /**
     * Get pending requests
     */
    public function getPendingRequests()
    {
        return OwnerRequest::with('user')
            ->pending()
            ->latest()
            ->get();
    }
}
