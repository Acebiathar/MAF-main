<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Item;

class HomeController extends Controller
{
    /**
     * Handle the MedFinder Homepage and Medicine Search
     */
    public function index(Request $request)
    {
        try {
            // 1. Get search parameters
            $searchTerms = $request->query('item_names', []);
            $itemSearch = $request->input('search', '');
            $currentUser = auth()->user();

            $results = collect();
            $alternatives = collect();

            // 2. Handle specific medicine matches (Pharmacy Logic)
            if (!empty($searchTerms)) {
                $allMatches = DB::table('pharmacy_medicine as pm')
                    ->join('medicines as m', 'pm.medicine_id', '=', 'm.id')
                    ->join('pharmacies as p', 'pm.pharmacy_id', '=', 'p.id')
                    ->select(
                        'pm.*',
                        'm.name as medicine_name',
                        'm.category as medicine_category',
                        'p.name as pharmacy_name',
                        'p.location as pharmacy_location'
                    )
                    ->where(function ($query) use ($searchTerms) {
                        foreach ($searchTerms as $item) {
                            $query->orWhere('m.name', 'like', "%{$item}%");
                        }
                    })
                    ->where('pm.quantity', '>', 0)
                    ->get();

                $results = $allMatches->groupBy('pharmacy_name')
                    ->sortByDesc(fn($group) => $group->unique('medicine_name')->count())
                    ->flatten();

                if ($allMatches->isNotEmpty()) {
                    $firstCategory = $allMatches->first()->medicine_category;
                    $alternatives = DB::table('medicines')
                        ->where('category', $firstCategory)
                        ->whereNotIn('name', $searchTerms)
                        ->limit(4)
                        ->get();
                }
            }

            // 3. Handle General Items (Your new Items table)
            $items = Item::query()
                ->when($itemSearch, function ($q) use ($itemSearch) {
                    return $q->where('name', 'like', "%{$itemSearch}%")
                        ->orWhere('description', 'like', "%{$itemSearch}%");
                })
                ->orderBy('created_at', 'desc')
                ->paginate(9)
                ->withQueryString();

            // 4. Get display data
            $query = implode(', ', $searchTerms);
            $pharmacies = DB::table('pharmacies')->where('status', 'approved')->limit(4)->get();

            return view('index', compact(
                'query',
                'results',
                'pharmacies',
                'alternatives',
                'currentUser',
                'items',
                'itemSearch'
            ));
        } catch (\Exception $e) {
            // Log the error for debugging if needed
            return redirect('/')->with('danger', 'Search error: ' . $e->getMessage());
        }
    }
}
