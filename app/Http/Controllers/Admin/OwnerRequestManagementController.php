<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\OwnerRequest;
use App\Services\OwnerRequestService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OwnerRequestManagementController extends Controller
{
    public function __construct(
        protected OwnerRequestService $ownerRequestService
    ) {
        $this->middleware(['auth', 'admin']);
    }

    public function index()
    {
        $pendingRequests = OwnerRequest::with('user')
            ->pending()
            ->latest()
            ->paginate(15);

        $allRequests = OwnerRequest::with(['user', 'reviewer'])
            ->latest()
            ->paginate(15);

        return view('admin.owner-requests.index', compact('pendingRequests', 'allRequests'));
    }

    public function show(OwnerRequest $ownerRequest)
    {
        $ownerRequest->load('user');
        return view('admin.owner-requests.show', compact('ownerRequest'));
    }

    public function approve(Request $request, OwnerRequest $ownerRequest)
    {
        $validated = $request->validate([
            'notes' => 'nullable|string|max:1000',
        ]);

        try {
            $this->ownerRequestService->approveRequest(
                $ownerRequest,
                Auth::user(),
                $validated['notes'] ?? null
            );

            return redirect()
                ->route('admin.owner-requests.index')
                ->with('success', 'Owner request approved successfully!');

        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function reject(Request $request, OwnerRequest $ownerRequest)
    {
        $validated = $request->validate([
            'reason' => 'required|string|max:1000',
        ]);

        try {
            $this->ownerRequestService->rejectRequest(
                $ownerRequest,
                Auth::user(),
                $validated['reason']
            );

            return redirect()
                ->route('admin.owner-requests.index')
                ->with('success', 'Owner request rejected');

        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}
