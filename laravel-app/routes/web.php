<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\View;
use App\Http\Controllers\HomeController;

// --- GLOBAL HELPERS ---
if (!function_exists('renderView')) {
    function renderView($view, $data = [])
    {
        return view($view, $data);
    }
}

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
        session()->flash('alerts', [
            'category' => $category,
            'message' => $message,
        ]);
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

// Integrated Multi-Item Prioritized Stock Match Search Engine (Unified Root Route)
Route::get('/', function (Request $request) {
    // Read the single search query string instead of the array
    $searchQuery = $request->input('search', '');
    $results = collect();

    if (!empty(trim($searchQuery))) {
        // Break up comma-separated terms so users can search multiple drugs at once
        $requestedNames = explode(',', $searchQuery);

        // Normalize strings to strip accidental spaces and lower case variations smoothly
        $cleanNames = array_filter(array_map(function($name) {
            return trim(strtolower($name));
        }, $requestedNames));

        if (!empty($cleanNames)) {
            // 1. Fetch matching pharmacies ordered by how many requested items they have in stock (> 0)
            $pharmacies = DB::table('pharmacies as p')
                ->where('p.status', 'approved')
                ->whereExists(function ($query) use ($cleanNames) {
                    $query->select(DB::raw(1))
                        ->from('pharmacy_medicine as pm')
                        ->join('medicines as m', 'pm.medicine_id', '=', 'm.id')
                        ->whereColumn('pm.pharmacy_id', 'p.id')
                        ->where(function($q) use ($cleanNames) {
                            foreach ($cleanNames as $name) {
                                $q->orWhere(DB::raw('LOWER(m.name)'), 'LIKE', '%' . $name . '%');
                            }
                        });
                })
                ->select('p.*')
                // Calculates intersection hit depth parameters dynamically for row ranking
                ->selectSub(function ($query) use ($cleanNames) {
                    $query->select(DB::raw('count(*)'))
                        ->from('pharmacy_medicine as pm')
                        ->join('medicines as m', 'pm.medicine_id', '=', 'm.id')
                        ->whereColumn('pm.pharmacy_id', 'p.id')
                        ->where('pm.quantity', '>', 0)
                        ->where(function($q) use ($cleanNames) {
                            foreach ($cleanNames as $name) {
                                $q->orWhere(DB::raw('LOWER(m.name)'), 'LIKE', '%' . $name . '%');
                            }
                        });
                }, 'available_items_count')
                ->orderBy('available_items_count', 'desc')
                ->get();

            // 2. Fetch the concrete medicine specifications linked via pivot table constraints
            if ($pharmacies->isNotEmpty()) {
                $pharmacyIds = $pharmacies->pluck('id')->toArray();

                $allMedicines = DB::table('pharmacy_medicine as pm')
                    ->join('medicines as m', 'pm.medicine_id', '=', 'm.id')
                    ->whereIn('pm.pharmacy_id', $pharmacyIds)
                    ->where(function($q) use ($cleanNames) {
                        foreach ($cleanNames as $name) {
                            $q->orWhere(DB::raw('LOWER(m.name)'), 'LIKE', '%' . $name . '%');
                        }
                    })
                    ->select('pm.id as pivot_id', 'pm.pharmacy_id', 'pm.price', 'pm.quantity', 'm.id as medicine_id', 'm.name')
                    ->get()
                    ->groupBy('pharmacy_id');

                // 3. Construct nested object maps to natively feed structural blades parameters safely
                $results = $pharmacies->map(function ($pharmacy) use ($allMedicines) {
                    $pharmacy->medicines = collect($allMedicines->get($pharmacy->id, []))->map(function ($med) {
                        return (object) [
                            'id' => $med->medicine_id,
                            'name' => $med->name,
                            'pivot' => (object) [
                                'id' => $med->pivot_id,
                                'price' => $med->price,
                                'quantity' => $med->quantity
                            ]
                        ];
                    });
                    return $pharmacy;
                });
            }
        }
    }

    return renderView('index', [
        'results'     => $results,
        'currentUser' => currentUser()
    ]);
});

Route::get('/home', function () {
    return redirect('/');
})->name('home');

Route::get('/about', function () {
    return renderView('about');
})->name('about');

Route::get('/how', function () {
    return view('how'); 
})->name('how');

Route::get('/contact', function () {
    return renderView('contact');
})->name('contact');

Route::get('/privacy', function () {
    return renderView('privacy');
})->name('privacy');

// --- AUTHENTICATION (Login, Register, Logout) ---


Route::match(['get', 'post'], '/login', function (Request $request) {
    // 1. GET Request: If user is already authenticated, send them to their dashboard
    if ($request->isMethod('get')) {
        $userId = session('user_id');
        $user = $userId ? DB::table('users')->where('id', $userId)->first() : null;

        if ($user) {
            $role = strtolower($user->role);
            if ($role === 'admin') return redirect('/admin');
            if ($role === 'pharmacist') return redirect('/pharmacist');
            return redirect('/requests');
        }
        return renderView('auth.login');
    }

    // 2. POST Request: Form Submission Processing
    if ($request->isMethod('post')) {
        $email = strtolower(trim($request->input('email', '')));
        $password = $request->input('password', '');
        
        $user = DB::table('users')->where('email', $email)->first();

        if ($user && Hash::check($password, $user->password)) {
            session(['user_id' => $user->id]);
            
            session()->flash('alerts', [
                'category' => 'success',
                'message' => "Hi, " . $user->name . "! Welcome back.",
            ]);

            $role = strtolower($user->role);
            if ($role === 'admin') return redirect('/admin');
            if ($role === 'pharmacist') return redirect('/pharmacist');
            return redirect('/requests');
        }

        session()->flash('alerts', [
            'category' => 'danger',
            'message' => 'Invalid credentials.',
        ]);
        return redirect('/login');
    }
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
    
    // 1. Guard: If not logged in, force them to log in
    if (!$user) {
        return redirect('/login');
    }

    // 2. Guard: If they ARE logged in, but they are NOT a patient,
    // send them to their correct dashboard instead of /login (this prevents the infinite loop!)
    if (strtolower($user->role) !== 'patient') {
        if (strtolower($user->role) === 'admin') return redirect('/admin');
        if (strtolower($user->role) === 'pharmacist') return redirect('/pharmacist');
    }

    // 3. Main Logic: If they pass the guards above, they are a valid patient.
    // Fetch their reservations and show the page.
    $reservations = DB::table('reservations as r')
        ->join('pharmacies as p', 'r.pharmacy_id', '=', 'p.id')
        ->join('medicines as m', 'r.medicine_id', '=', 'm.id')
        ->select('r.*', 'm.name as medicine_name', 'p.name as pharmacy_name', 'p.address as pharmacy_address')
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

    $stats = [
        'users' => DB::table('users')->count(),
        'pharmacies' => DB::table('pharmacies')->where('status', 'approved')->count(),
        'medicines' => DB::table('medicines')->count(),
        'reservations' => DB::table('reservations')->count(),
    ];

    $pending = DB::table('pharmacies')->where('status', 'pending')->get();

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

// Inject testimonials directly into the index view automatically
View::composer('index', function ($view) {
    $testimonials = [
        [
            'name' => 'John Doe',
            'role' => 'Patient',
            'quote' => 'Finding my prescribed medication used to take hours. With this platform, I found a nearby pharmacy carrying it in under two minutes!',
            'avatar' => 'https://i.pravatar.cc/150?img=11'
        ],
        [
            'name' => 'Dr. Sarah Jane',
            'role' => 'Pharmacist (City Pharma)',
            'quote' => 'This system has streamlined our inventory requests. We can easily confirm stock availability and help patients when they need it most.',
            'avatar' => 'https://i.pravatar.cc/150?img=47'
        ],
        [
            'name' => 'Michael Carter',
            'role' => 'Patient',
            'quote' => 'Excellent service! The reservation feature gave me peace of mind knowing my chronic illness medication was held for pickup.',
            'avatar' => 'https://i.pravatar.cc/150?img=33'
        ]
    ];

    $view->with('testimonials', $testimonials);
});