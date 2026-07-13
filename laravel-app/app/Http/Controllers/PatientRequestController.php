<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pharmacy; // Make sure to import your Pharmacy model at the top!

class PatientRequestController extends Controller
{
    public function index()
    {
        // 1. Fetch the pharmacy data (adjust this query to fit your actual app logic)
        $pharmacy = Pharmacy::first(); 

        // 2. Return the view and pass the variable safely
        return view('patient_requests', compact('pharmacy'));
    }
}

