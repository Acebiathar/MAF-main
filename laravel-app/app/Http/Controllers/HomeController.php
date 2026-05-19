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
        return view('index', [
            'query' => '',
            'results' => collect(),
            'pharmacies' => collect(),
            'alternatives' => collect(),
            'currentUser' => null,
            'items' => collect(),
            'itemSearch' => ''
        ]);
    }
}
