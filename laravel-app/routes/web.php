<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;

// --- GLOBAL HELPERS ---

if (!function_exists('currentUser')) {
    function currentUser()
    {
        $userId = session('user_id');
        return $userId ? DB::table('users')->where('id', $userId)->first() : null;
    }
}

if (!function_exists('flash')) {
    function flash($category, $message)
    {
        session()->put('alerts', [
            'category' => $category,
            'message' => $message,
        ]);
    }
}

if (!function_exists('renderView')) {
    function renderView(string $view, array $data = [])
    {
        $data['currentUser'] = currentUser();
        return view($view, $data);
    }
}

if (!function_exists('redirectToDashboard')) {
    function redirectToDashboard(object $user)
    {
        if ($user->role === 'admin') {
            return redirect('/admin');
        }

        if ($user->role === 'pharmacist') {
            return redirect('/pharmacist');
        }

        return redirect('/requests');
    }
}

// --- PUBLIC PAGES (Home, Search, About) ---

Route::get('/', function (Request $request) {
    try {
        // Get items from the editable list
        $items = $request->query('item_names', []);

        $currentUser = currentUser();
        // Initialize collections to prevent "Undefined variable" errors
        $results = collect();
        $alternatives = collect();

        if (!empty($items)) {
            // 1. Get all matches for any of the items from the database
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
                ->where(function ($query) use ($items) {
                    foreach ($items as $item) {
                        $query->orWhere('m.name', 'like', "%{$item}%");
                    }
                })
                ->where('pm.quantity', '>', 0)
                ->get();

            // 2. Group by pharmacy and sort so those with the MOST matches appear first
            $results = $allMatches->groupBy('pharmacy_name')
                ->sortByDesc(function ($group) {
                    return $group->unique('medicine_name')->count();
                })
                ->flatten();

            // 3. Get Alternatives (Same category as the first item searched)
            if ($allMatches->isNotEmpty()) {
                $firstCategory = $allMatches->first()->medicine_category;
                $alternatives = DB::table('medicines')
                    ->where('category', $firstCategory)
                    ->whereNotIn('name', $items) // Don't suggest what they already searched for
                    ->limit(4)
                    ->get();
            }
        }

        $query = implode(', ', $items);
        $pharmacies = DB::table('pharmacies')->where('status', 'approved')->limit(4)->get();

        // Added 'alternatives' to the compact list to stop the error
        return renderView('index', compact('query', 'results', 'pharmacies', 'alternatives', 'currentUser'));
    } catch (\Exception $e) {
        flash('danger', 'Search error: ' . $e->getMessage());
        return redirect('/');
    }
});
Route::get('/home', function () {
    return redirect('home');
})->name('home');
Route::get('/about', function () {
    return renderView('about');
})->name('about');

Route::get('/how', function () {
    return view('how'); // Ensure this view exists
})->name('how');
Route::get('/contact', function () {
    return renderView('contact');
})->name('contact');
Route::get('/privacy', function () {
    return renderView('privacy');
})->name('privacy');

// --- AUTHENTICATION (Login, Register, Logout) ---

Route::match(['get', 'post'], '/login', function (Request $request) {
    if ($request->isMethod('get') && ($user = currentUser())) {
        return redirectToDashboard($user);
    }

    if ($request->isMethod('post')) {
        $email = strtolower(trim($request->input('email', '')));
        $password = $request->input('password', '');
        $user = DB::table('users')->where('email', $email)->first();

        if ($user && Hash::check($password, $user->password)) {
            session(['user_id' => $user->id]);

            // Set the greeting to be caught by the layout on the next page
            flash('success', "Hi, " . $user->name . "! Welcome back.");

            return redirectToDashboard($user);
        }

        flash('danger', 'Invalid credentials.');
        return redirect('/login');
    }
    return renderView('auth.login');
})->name('login');

Route::match(['get', 'post'], '/register', function (Request $request) {
    if ($request->isMethod('get') && ($user = currentUser())) {
        return redirectToDashboard($user);
    }

    if ($request->isMethod('post')) {
        $name = trim((string) $request->input('name', ''));
        $email = strtolower(trim($request->input('email', '')));
        $password = (string) $request->input('password', '');
        $role = $request->input('role', 'patient');
        $pharmacyName = trim((string) $request->input('pharmacy_name', ''));
        $licenseNumber = trim((string) $request->input('license_number', ''));
        $location = trim((string) $request->input('location', ''));
        $phoneNumber = trim((string) $request->input('phone', ''));

        if ($name === '' || $email === '' || $password === '') {
            flash('danger', 'Name, email, and password are required.');
            return redirect('/register')->withInput();
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            flash('danger', 'Please enter a valid email address.');
            return redirect('/register')->withInput();
        }

        if (strlen($password) < 8) {
            flash('danger', 'Password must be at least 8 characters long.');
            return redirect('/register')->withInput();
        }

        if (!in_array($role, ['patient', 'pharmacist'], true)) {
            flash('danger', 'Please choose a valid account type.');
            return redirect('/register')->withInput();
        }

        if ($role === 'pharmacist' && ($pharmacyName === '' || $licenseNumber === '' || $location === '' || $phoneNumber === '')) {
            flash('danger', 'All pharmacy verification details are required for pharmacist accounts.');
            return redirect('/register')->withInput();
        }

        if (DB::table('users')->where('email', $email)->exists()) {
            flash('warning', 'Email already registered.');
            return redirect('/register')->withInput();
        }

        DB::transaction(function () use ($name, $email, $password, $role, $pharmacyName, $licenseNumber, $location, $phoneNumber) {
            $userId = DB::table('users')->insertGetId([
                'name' => $name,
                'email' => $email,
                'password' => Hash::make($password),
                'role' => $role,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            if ($role === 'pharmacist') {
                DB::table('pharmacies')->insert([
                    'name' => $pharmacyName,
                    'location' => $location,
                    'phone' => $phoneNumber,
                    'license_number' => $licenseNumber,
                    'status' => 'pending',
                    'owner_id' => $userId,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        });

        flash('success', $role === 'pharmacist'
            ? 'Registration successful. Your pharmacy account is pending admin verification.'
            : 'Registration successful. Please log in.');
        return redirect('/login');
    }
    return renderView('auth.register');
})->name('register');

Route::get('/logout', function () {
    session()->forget('user_id');
    flash('info', 'Logged out.');
    return redirect('/');
});

// --- PHARMACIST DASHBOARD & INVENTORY ---

Route::get('/pharmacist', function () {
    try {
        $user = currentUser();
        if (!$user || $user->role !== 'pharmacist') return redirect('/login');

        $pharmacy = DB::table('pharmacies')->where('owner_id', $user->id)->first();
        if (!$pharmacy) return redirect('/');

        $isActive = ($pharmacy->status === 'approved');

        $all_medicines = DB::table('medicines')->orderBy('name')->get();
        $requestCollection = DB::table('reservations')
            ->where('pharmacy_id', $pharmacy->id)
            ->orderByDesc('created_at')
            ->get();

        // Fixed: JOIN syntax and singular table name
        $inventory = collect();
        if ($isActive) {
            $inventory = DB::table('pharmacy_medicine as pm')
                ->leftJoin('medicines as m', 'pm.medicine_id', '=', 'm.id')
                ->select('pm.*', 'm.name as medicine_name')
                ->where('pm.pharmacy_id', $pharmacy->id)
                ->get();
        } else {
            flash('info', 'Your pharmacy account is pending admin verification. Inventory becomes visible after approval.');
        }

        return renderView('dashboard_pharmacist', [
            'pharmacy' => $pharmacy,
            'all_medicines' => $all_medicines,
            'inventory' => $inventory,
            'isActive' => $isActive,
            'requestCollection' => $requestCollection,
        ]);
    } catch (\Exception $e) {
        flash('danger', 'Dashboard error: ' . $e->getMessage());
        return redirect('/');
    }
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
            'updated_at' => now(),
        ]);
        flash('success', 'Pharmacy approved successfully.');
    } else {
        DB::table('pharmacies')->where('id', $pharmacy)->update([
            'status' => 'rejected',
            'updated_at' => now(),
        ]);
        flash('info', 'Pharmacy application rejected.');
    }

    return redirect('/admin');
});
