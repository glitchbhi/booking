<?php

namespace App\Http\Controllers;

use App\Models\Ground;
use App\Models\SportsType;
use Illuminate\Http\Request;

class GroundController extends Controller
{
    /**
     * Browse grounds with full filtering - public access
     */
    public function browse(Request $request)
    {
        $query = Ground::with(['owner', 'sportType'])
            ->where('is_active', true);

        // Search by name or location
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('location', 'like', "%{$search}%");
            });
        }

        // Filter by sport type
        if ($request->filled('sport_type')) {
            $query->where('sport_type_id', $request->sport_type);
        }

        // Filter by location (from welcome page search)
        if ($request->filled('location')) {
            $query->where('location', 'like', '%' . $request->location . '%');
        }

        // Filter by capacity
        if ($request->filled('capacity')) {
            $query->where('capacity', $request->capacity);
        }

        // Filter by date (for availability check)
        if ($request->filled('date')) {
            // Future: could add availability check for specific date
        }

        // Filter by price range
        if ($request->filled('min_price')) {
            $query->where('rate_per_hour', '>=', $request->min_price);
        }
        if ($request->filled('max_price')) {
            $query->where('rate_per_hour', '<=', $request->max_price);
        }

        // Filter by rating
        if ($request->filled('min_rating')) {
            $query->where('average_rating', '>=', $request->min_rating);
        }

        // Sorting
        $sortBy = $request->get('sort', 'latest');
        switch ($sortBy) {
            case 'price_low':
                $query->orderBy('rate_per_hour', 'asc');
                break;
            case 'price_high':
                $query->orderBy('rate_per_hour', 'desc');
                break;
            case 'rating':
                $query->orderBy('average_rating', 'desc');
                break;
            default:
                $query->latest();
        }

        $grounds = $query->paginate(12);

        // Get trending grounds (last 7 days)
        $trendingGrounds = Ground::active()
            ->trending(7)
            ->limit(6)
            ->get();

        // Get best-rated grounds
        $bestGrounds = Ground::active()
            ->best()
            ->limit(6)
            ->get();

        // Get sports types for filter
        $sportsTypes = SportsType::active()->get();
        
        // Get unique capacities for filter
        $capacities = Ground::active()
            ->whereNotNull('capacity')
            ->distinct()
            ->pluck('capacity')
            ->sort();

        return view('grounds.browse', compact('grounds', 'trendingGrounds', 'bestGrounds', 'sportsTypes', 'capacities'));
    }

    public function show(Ground $ground)
    {
        $ground->load([
            'owner',
            'sportType',
            'availabilities',
            'reviews' => function ($query) {
                $query->with('user')->latest()->limit(10);
            }
        ]);

        return view('grounds.show', compact('ground'));
    }

    public function index(Request $request)
    {
        $query = Ground::with(['owner', 'sportType'])->where('is_active', true);

        // Apply filters
        if ($request->filled('sport_type')) {
            $query->where('sport_type_id', $request->sport_type);
        }

        if ($request->filled('location')) {
            $query->where('location', 'like', '%' . $request->location . '%');
        }

        $grounds = $query->latest()->paginate(12);

        return view('grounds.index', compact('grounds'));
    }
}
