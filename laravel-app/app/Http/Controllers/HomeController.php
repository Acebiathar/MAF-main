<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        // 1. Collect the tag array sent by the JavaScript frontend engine
        $tags = $request->input('item_names', []);
        
        // Initialize an empty collection to hold results if no search happens
        $results = collect();

        // 2. Only query the database if the user has added items to the list
        if (!empty($tags) && is_array($tags)) {
            $results = DB::table('pharmacy_medicine as pm')
                ->join('medicines as m', 'pm.medicine_id', '=', 'm.id')
                ->join('pharmacies as p', 'pm.pharmacy_id', '=', 'p.id')
                ->select(
                    'pm.id as id',
                    'm.name as medicine_name',
                    'p.name as pharmacy_name',
                    'p.location as pharmacy_location',
                    'pm.price as price',
                    'pm.quantity as quantity'
                )
                ->where('p.status', '=', 'approved')
                ->where('pm.stock_status', '=', 'in_stock')
                ->where('pm.quantity', '>', 0)
                ->where(function ($query) use ($tags) {
                    foreach ($tags as $tag) {
                        if (trim($tag) !== '') {
                            $query->orWhere('m.name', 'ILIKE', '%' . trim($tag) . '%');
                        }
                    }
                })
                ->get();
        }

        // 3. Hand the dataset back to your index layout view scope
        return view('index', [
            'query' => !empty($tags) ? implode(', ', $tags) : '',
            'results' => $results,
            'pharmacies' => collect(),
            'alternatives' => collect(),
            'currentUser' => session('user_id') ? DB::table('users')->where('id', session('user_id'))->first() : null,
            'items' => collect(),
            'itemSearch' => ''
        ]);
    }
}