<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserManagementController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'admin']);
    }

    public function index(Request $request)
    {
        $query = User::query();

        // Filter by role
        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        // Filter by suspension status
        if ($request->filled('suspended')) {
            if ($request->suspended === 'yes') {
                $query->where('is_suspended', true);
            } elseif ($request->suspended === 'no') {
                $query->where('is_suspended', false);
            }
        }

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $users = $query->latest()->paginate(15);

        return view('admin.users.index', compact('users'));
    }

    public function show(User $user)
    {
        $user->load(['bookings', 'grounds', 'reviews', 'walletTransactions']);
        
        return view('admin.users.show', compact('user'));
    }

    public function suspend(Request $request, User $user)
    {
        $validated = $request->validate([
            'days' => 'required|integer|min:1|max:365',
            'reason' => 'nullable|string|max:1000',
        ]);

        $user->update([
            'is_suspended' => true,
            'suspended_until' => now()->addDays($validated['days']),
        ]);

        return redirect()
            ->route('admin.users.show', $user)
            ->with('success', "User suspended for {$validated['days']} days");
    }

    public function unsuspend(User $user)
    {
        $user->update([
            'is_suspended' => false,
            'suspended_until' => null,
            'late_cancel_count' => 0,
        ]);

        return redirect()
            ->route('admin.users.show', $user)
            ->with('success', 'User suspension removed');
    }

    public function create()
    {
        return view('admin.users.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:user,owner,admin',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'],
            'owner_status' => $validated['role'] === 'owner' ? 'approved' : 'none',
            'email_verified_at' => now(),
        ]);

        return redirect()
            ->route('admin.users.show', $user)
            ->with('success', 'User created successfully!');
    }
}
