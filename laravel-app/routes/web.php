<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;

// --- GLOBAL HELPERS ---

function currentUser()
{
    $userId = session('user_id');
    return $userId ? DB::table('users')->where('id', $userId)->first() : null;
}

function flash($category, $message)
{
    session()->push('alerts', [$category, $message]);
}

function renderView(string $view, array $data = [])
{
    $data['currentUser'] = currentUser();
    $data['alerts'] = session()->pull('alerts', []);
    return view($view, $data);
}

// --- PUBLIC PAGES (Home, Search, About) ---

Route::get('/', function (Request $request) {
    $items = $request->query('item_names', []);
    $results = collect();
    $alternatives = collect();

    if (!empty($items)) {
        // Search using the array of items
        $results = DB::table('pharmacy_medicine as pm')
            ->join('medicines as m', 'pm.medicine_id', '=', 'm.id')
            ->join('pharmacies as p', 'pm.pharmacy_id', '=', 'p.id')
            ->select('pm.*', 'm.name as medicine_name', 'm.category as medicine_category', 'p.name as pharmacy_name', 'p.location as pharmacy_location')
            ->where(function($query) use ($items) {
                foreach ($items as $item) {
                    $query->orWhere('m.name', 'like', "%{$item}%");
                }
            })
            ->where('p.status', '=', 'approved')
            ->where('p.subscription_status', '=', 'active')
            ->orderBy('p.name')
            ->get();
            
        // Suggestions based on the first item found
        $category = $results->first()->medicine_category ?? null;
        if ($category) {
            $alternatives = DB::table('medicines')
                ->where('category', $category)
                ->limit(4)
                ->get();
        }
    }

    $pharmacies = DB::table('pharmacies')->where('status', 'approved')->limit(4)->get();
    
    // Convert array back to comma-string for the view logic if needed
    $query = implode(', ', $items); 
    
    return renderView('home', compact('query', 'results', 'alternatives', 'pharmacies'));
});

Route::get('/about', function () {
    return renderView('about');
});
Route::get('/how-it-works', function () {
    return renderView('how');
});
Route::get('/contact', function () {
    return renderView('contact');
});

// --- AUTHENTICATION (Login, Register, Logout) ---

Route::match(['get', 'post'], '/login', function (Request $request) {
    if ($request->isMethod('post')) {
        $email = strtolower(trim($request->input('email', '')));
        $password = $request->input('password', '');
        $user = DB::table('users')->where('email', $email)->first();

        if ($user && Hash::check($password, $user->password)) {
            session(['user_id' => $user->id]);

            // Set the greeting to be caught by the layout on the next page
            flash('success', "Hi, " . $user->name . "! Welcome back.");

            // STRICT REDIRECT LOGIC
            if ($user->role === 'admin') {
                return redirect('/admin');
            } elseif ($user->role === 'pharmacist') {
                return redirect('/pharmacist');
            } else {
                // All other users (Patients) go to their requests
                return redirect('/requests');
            }
        }

        flash('danger', 'Invalid credentials.');
        return redirect('/login');
    }
    return renderView('auth.login');
});

Route::match(['get', 'post'], '/register', function (Request $request) {
    if ($request->isMethod('post')) {
        $email = strtolower(trim($request->input('email', '')));
        if (DB::table('users')->where('email', $email)->exists()) {
            flash('warning', 'Email already registered.');
            return redirect('/register');
        }

        DB::transaction(function () use ($request, $email) {
            $userId = DB::table('users')->insertGetId([
                'name' => $request->input('name'),
                'email' => $email,
                'password' => Hash::make($request->input('password')),
                'role' => $request->input('role', 'patient'),
            ]);

            if ($request->role === 'pharmacist') {
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

        flash('success', 'Registration successful. Please log in.');
        return redirect('/login');
    }
    return renderView('auth.register');
});

Route::get('/logout', function () {
    session()->forget('user_id');
    flash('info', 'Logged out.');
    return redirect('/');
});

// --- PHARMACIST DASHBOARD & INVENTORY ---

Route::get('/pharmacist', function () {
    $user = currentUser();
    if (!$user || $user->role !== 'pharmacist') return redirect('/login');

    $pharmacy = DB::table('pharmacies')->where('owner_id', $user->id)->first();
    if (!$pharmacy) return redirect('/');

    // Check if the pharmacy has paid
    $isActive = ($pharmacy->subscription_status === 'active');

    $all_medicines = DB::table('medicines')->orderBy('name')->get();

    // Fixed: JOIN syntax and singular table name
    $inventory = [];
    if ($isActive) {
        $inventory = DB::table('pharmacy_medicine as pm')
            ->leftJoin('medicines as m', 'pm.medicine_id', '=', 'm.id')
            ->select('pm.*', 'm.name as medicine_name')
            ->where('pm.pharmacy_id', $pharmacy->id)
            ->get();
    } else {
        flash('info', 'Your account is pending payment confirmation. Please contact support to activate your shop.');
    }

    return renderView('dashboard_pharmacist', [
        'pharmacy' => $pharmacy,
        'all_medicines' => $all_medicines,
        'inventory' => $inventory,
        'isActive' => $isActive // Pass this to the view to hide/show buttons
    ]);
});
Route::post('/pharmacist/add', function (Request $request) {
    $user = currentUser();
    $pharmacy = DB::table('pharmacies')->where('owner_id', $user->id)->first();

    $medicineName = trim(strtolower($request->input('medicine_name')));
    $medicine = DB::table('medicines')->where('name', $medicineName)->first();

    if ($medicine) {
        $medId = $medicine->id;
    } else {
        // Fix: Category constraint handled
        $medId = DB::table('medicines')->insertGetId([
            'name' => $medicineName,
            'category' => 'General',
            'created_at' => now(),
            'updated_at' => now()
        ]);
    }

    DB::table('pharmacy_medicine')->updateOrInsert(
        ['pharmacy_id' => $pharmacy->id, 'medicine_id' => $medId],
        [
            'price' => (float)$request->input('price'),
            'quantity' => (int)$request->input('quantity'),
            'stock_status' => $request->input('stock_status', 'in_stock'),
            'updated_at' => now()
        ]
    );

    flash('success', 'Inventory updated.');
    return redirect('/pharmacist');
});

// --- PATIENT REQUESTS & RESERVATIONS ---

Route::post('/reserve/{item}', function (int $item) {
    $user = currentUser();
    if (!$user || $user->role !== 'patient') return redirect('/login');

    $itemRow = DB::table('pharmacy_medicine')->where('id', $item)->first();
    if (!$itemRow) return redirect('/');

    DB::table('reservations')->insert([
        'user_id' => $user->id,
        'pharmacy_id' => $itemRow->pharmacy_id,
        'medicine_id' => $itemRow->medicine_id,
        'status' => 'pending',
        'created_at' => now(),
        'note' => request('note', ''),
    ]);

    flash('success', 'Reservation sent.');
    return redirect('/');
});

Route::get('/requests', function () {
    $user = currentUser();
    if (!$user || $user->role !== 'patient') return redirect('/login');

    $reservations = DB::table('reservations as r')
        ->join('pharmacies as p', 'r.pharmacy_id', '=', 'p.id')
        ->join('medicines as m', 'r.medicine_id', '=', 'm.id')
        ->select('r.*', 'm.name as medicine_name', 'p.name as pharmacy_name', 'p.location as pharmacy_location')
        ->where('r.user_id', $user->id)
        ->orderByDesc('r.created_at')
        ->get();
    return renderView('patient_requests', compact('reservations'));
});

// --- PHARMACIST REQUEST MANAGEMENT ---

Route::get('/pharmacist/requests', function () {
    $user = currentUser();
    $pharmacy = DB::table('pharmacies')->where('owner_id', $user->id)->first();

    $reservations = DB::table('reservations as r')
        ->join('users as u', 'r.user_id', '=', 'u.id')
        ->join('medicines as m', 'r.medicine_id', '=', 'm.id')
        ->select('r.*', 'u.name as user_name', 'u.email as user_email', 'm.name as medicine_name')
        ->where('r.pharmacy_id', $pharmacy->id)
        ->orderByDesc('r.created_at')
        ->get();
    return renderView('pharmacist_requests', compact('reservations', 'pharmacy'));
});

Route::post('/pharmacist/requests/{reservation}/{action}', function (int $reservation, string $action) {
    $user = currentUser();
    $pharmacy = DB::table('pharmacies')->where('owner_id', $user->id)->first();
    $status = $action === 'confirm' ? 'confirmed' : 'declined';

    DB::table('reservations')
        ->where('id', $reservation)
        ->where('pharmacy_id', $pharmacy->id)
        ->update(['status' => $status]);

    flash('success', 'Reservation updated.');
    return redirect('/pharmacist/requests');
});

// --- ADMIN DASHBOARD ---

Route::get('/admin/{action?}/{type?}', function ($action = null, $type = null) {
    $user = currentUser();
    if (!$user || $user->role !== 'admin') return redirect('/');

    // 1. Fetch the Top Stats
    $stats = [
        'users' => DB::table('users')->count(),
        'pharmacies' => DB::table('pharmacies')->where('status', 'approved')->count(),
        'medicines' => DB::table('medicines')->count(),
        'reservations' => DB::table('reservations')->count(),
    ];

    // 2. Always fetch Pending Pharmacies for the bottom table
    $pending = DB::table('pharmacies')->where('status', 'pending')->get();

    // 3. Logic for the Clickable Cards
    $viewList = null;
    $viewType = $type;

    if ($action === 'view') {
        if ($type === 'users') {
            $viewList = DB::table('users')->orderBy('created_at', 'desc')->get();
        } elseif ($type === 'pharmacies') {
            $viewList = DB::table('pharmacies')->where('status', 'approved')->get();
        } elseif ($type === 'medicines') {
            $viewList = DB::table('medicines')->get();
        } elseif ($type === 'reservations') {
            $viewList = DB::table('reservations')
                ->join('users', 'reservations.user_id', '=', 'users.id')
                ->select('reservations.*', 'users.name as user_name')
                ->get();
        }
    }

    return renderView('admin_dashboard', [
        'stats' => $stats,
        'pending' => $pending,
        'viewList' => $viewList,
        'viewType' => $viewType
    ]);
});

Route::get('/admin/pharmacies/{pharmacy}/{action}', function (int $pharmacy, string $action) {
    $user = currentUser();
    if (!$user || $user->role !== 'admin') return redirect('/');

    if ($action === 'approve') {
        DB::table('pharmacies')->where('id', $pharmacy)->update([
            'status' => 'approved',
            'subscription_status' => 'active', // Mark as paid
            'subscription_expiry' => now()->addDays(30), // Set expiry
            'updated_at' => now() // Fixes the null timestamp issue
        ]);
        flash('success', "Pharmacy approved and subscription activated.");
    } else {
        DB::table('pharmacies')->where('id', $pharmacy)->update(['status' => 'rejected']);
    }

    return redirect('/admin');
});
