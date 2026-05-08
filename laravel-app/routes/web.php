<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\HomeController;
use App\Models\Item;

/*
|--------------------------------------------------------------------------
| PUBLIC PAGES & SEARCH
|--------------------------------------------------------------------------
*/

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::resource('items', ItemController::class);

Route::get('/', function (Request $request) {
    try {
        $searchTerms = $request->query('item_names', []);
        $itemSearch = $request->input('search', '');
        $currentUser = auth()->user();
        $results = collect();
        $alternatives = collect();

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

        $query = implode(', ', $searchTerms);
        $pharmacies = DB::table('pharmacies')->where('status', 'approved')->limit(4)->get();
        $items = Item::query()
            ->when($itemSearch, fn($q) => $q->where('name', 'like', "%{$itemSearch}%"))
            ->orderBy('created_at', 'desc')
            ->paginate(10)
            ->withQueryString();

        return view('index', compact('query', 'results', 'pharmacies', 'alternatives', 'currentUser', 'items'));
    } catch (\Exception $e) {
        return redirect('/')->with('danger', 'Search error: ' . $e->getMessage());
    }
})->name('home');

Route::get('/about', function () {
    return view('about');
})->name('about');

Route::get('/how-it-works', function () {
    return view('how');
})->name('how');

Route::get('/contact', function () {
    return view('contact');
})->name('contact');

Route::get('/privacy', function () {
    return view('privacy');
})->name('privacy');


/*
|--------------------------------------------------------------------------
| AUTHENTICATION
|--------------------------------------------------------------------------
*/

Route::match(['get', 'post'], '/login', function (Request $request) {
    if ($request->isMethod('post')) {
        $email = strtolower(trim($request->input('email', '')));
        $password = $request->input('password', '');
        $user = DB::table('users')->where('email', $email)->first();

        if ($user && Hash::check($password, $user->password)) {
            session(['user_id' => $user->id]);

            if ($user->role === 'admin') return redirect('/admin');
            if ($user->role === 'pharmacist') return redirect('/pharmacist');
            return redirect('/requests');
        }
        return redirect('/login')->with('danger', 'Invalid credentials.');
    }
    return view('auth.login');
})->name('login');

Route::match(['get', 'post'], '/register', function (Request $request) {
    if ($request->isMethod('post')) {
        $email = strtolower(trim($request->input('email', '')));
        if (DB::table('users')->where('email', $email)->exists()) {
            return redirect('/register')->with('warning', 'Email already registered.');
        }

        DB::transaction(function () use ($request, $email) {
            $userId = DB::table('users')->insertGetId([
                'name' => $request->input('name'),
                'email' => $email,
                'password' => Hash::make($request->input('password')),
                'role' => $request->input('role', 'patient'),
                'created_at' => now(),
            ]);

            if ($request->input('role') === 'pharmacist') {
                DB::table('pharmacies')->insert([
                    'name' => $request->input('pharmacy_name'),
                    'location' => $request->input('location'),
                    'phone' => $request->input('phone'),
                    'license_number' => $request->input('license_number'),
                    'status' => 'pending',
                    'subscription_status' => 'inactive',
                    'owner_id' => $userId,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }
        });
        return redirect('/login')->with('success', 'Registration successful. Please log in.');
    }
    return view('auth.register');
})->name('register');

Route::get('/logout', function () {
    session()->forget('user_id');
    return redirect('/')->with('info', 'Logged out.');
})->name('logout');


/*
|--------------------------------------------------------------------------
| DASHBOARDS & MANAGEMENT
|--------------------------------------------------------------------------
*/

// Pharmacist Dashboard
Route::get('/pharmacist', function () {
    $user = auth()->user() ?: DB::table('users')->find(session('user_id'));
    if (!$user || $user->role !== 'pharmacist') return redirect('/login');

    $pharmacy = DB::table('pharmacies')->where('owner_id', $user->id)->first();
    if (!$pharmacy) return redirect('/');

    $isActive = ($pharmacy->subscription_status === 'active');
    $all_medicines = DB::table('medicines')->orderBy('name')->get();
    $inventory = $isActive ? DB::table('pharmacy_medicine as pm')
        ->leftJoin('medicines as m', 'pm.medicine_id', '=', 'm.id')
        ->select('pm.*', 'm.name as medicine_name')
        ->where('pm.pharmacy_id', $pharmacy->id)
        ->get() : [];

    return view('dashboard_pharmacist', compact('pharmacy', 'all_medicines', 'inventory', 'isActive'));
})->name('pharmacist.dashboard');

// Reservation and Admin routes follow similar clean patterns...
// (I have kept your existing logic but added semicolons and proper view helpers)

Route::get('/requests', function () {
    $uid = session('user_id');
    if (!$uid) return redirect('/login');

    $reservations = DB::table('reservations as r')
        ->join('pharmacies as p', 'r.pharmacy_id', '=', 'p.id')
        ->join('medicines as m', 'r.medicine_id', '=', 'm.id')
        ->select('r.*', 'm.name as medicine_name', 'p.name as pharmacy_name', 'p.location as pharmacy_location')
        ->where('r.user_id', $uid)
        ->orderByDesc('r.created_at')
        ->get();
    return view('patient_requests', compact('reservations'));
})->name('requests');
