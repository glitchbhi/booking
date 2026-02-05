<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SportsType;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SportsTypeController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'admin']);
    }

    public function index()
    {
        $sportsTypes = SportsType::withCount('grounds')->latest()->paginate(15);
        return view('admin.sports-types.index', compact('sportsTypes'));
    }

    public function create()
    {
        return view('admin.sports-types.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:sports_types,name',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $validated['slug'] = Str::slug($validated['name']);

        SportsType::create($validated);

        return redirect()
            ->route('admin.sports-types.index')
            ->with('success', 'Sports type created successfully!');
    }

    public function edit(SportsType $sportsType)
    {
        return view('admin.sports-types.edit', compact('sportsType'));
    }

    public function update(Request $request, SportsType $sportsType)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:sports_types,name,' . $sportsType->id,
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $validated['slug'] = Str::slug($validated['name']);

        $sportsType->update($validated);

        return redirect()
            ->route('admin.sports-types.index')
            ->with('success', 'Sports type updated successfully!');
    }

    public function destroy(SportsType $sportsType)
    {
        if ($sportsType->grounds()->count() > 0) {
            return back()->with('error', 'Cannot delete sports type with associated grounds');
        }

        $sportsType->delete();

        return redirect()
            ->route('admin.sports-types.index')
            ->with('success', 'Sports type deleted successfully');
    }
}
