<?php

namespace App\Http\Controllers;

use App\Models\Ground;
use App\Models\SportsType;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Welcome page - main landing page for all users
     */
    public function welcome(Request $request)
    {
        // Get sports types for search and category icons
        $sportsTypes = SportsType::active()->get();

        // Get popular/featured grounds for display
        $popularGrounds = Ground::with('sportType')
            ->where('is_active', true)
            ->orderBy('total_bookings', 'desc')
            ->take(6)
            ->get();

        return view('welcome', compact('sportsTypes', 'popularGrounds'));
    }

    public function index(Request $request)
    {
        // Search and filters
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

        return view('home', compact('grounds', 'trendingGrounds', 'bestGrounds', 'sportsTypes'));
    }

    public function search(Request $request)
    {
        $query = Ground::with(['owner', 'sportType'])
            ->where('is_active', true);

        if ($request->filled('q')) {
            $search = $request->q;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('location', 'like', "%{$search}%")
                  ->orWhereHas('sportType', function ($sq) use ($search) {
                      $sq->where('name', 'like', "%{$search}%");
                  });
            });
        }

        $results = $query->limit(10)->get();

        return response()->json($results);
    }
}
