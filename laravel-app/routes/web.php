<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\View;

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
        $alerts = session()->get('alerts', []);
        $alerts[] = [
            'category' => $category,
            'message' => $message,
        ];
        session()->flash('alerts', $alerts);
    }
}

if (!function_exists('redirectToDashboard')) {
    function redirectToDashboard(object $user)
    {
        $role = strtolower($user->role);
        if ($role === 'admin') {
            return redirect('/admin');
        }

        if ($role === 'pharmacist') {
            return redirect('/pharmacist');
        }

        return redirect('/requests');
    }
}

// --- PUBLIC PAGES (Home, Search, About) ---

// 1. Core Landing / Search Route (Assigned to 'index')
Route::get('/', function (Request $request) {
    $input = $request->validate([
        'search' => 'nullable|string|max:1000',
        'item_names' => 'nullable|array|max:20',
        'item_names.*' => 'required|string|max:255',
    ]);
    // Support the search field and links created by the previous tag-based form.
    $terms = collect([$input['search'] ?? '', ...($input['item_names'] ?? [])])
        ->flatMap(fn ($value) => explode(',', $value))
        ->map(fn ($value) => trim(preg_replace('/\s+/u', ' ', $value)))
        ->filter(fn ($value) => $value !== '')
        ->unique(fn ($value) => mb_strtolower($value))
        ->values();
    $searchQuery = $terms->implode(', ');
    $results = collect();

    if (!empty(trim($searchQuery))) {
        // Break up comma-separated terms so users can search multiple drugs at once
        $requestedNames = explode(',', $searchQuery);

        // Normalize strings to strip accidental spaces and lower case variations smoothly
        $cleanNames = array_filter(array_map(function ($name) {
            return trim(mb_strtolower($name));
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
                        ->where(function ($q) use ($cleanNames) {
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
                        ->where(function ($q) use ($cleanNames) {
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
                    ->where(function ($q) use ($cleanNames) {
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
        'searchQuery' => $searchQuery,
        'results'     => $results,
        'currentUser' => currentUser()
    ]);
})->name('index');

// 2. Explicit Redirect Alias Route for 'home' to guarantee template helpers resolve instantly
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
    if ($request->isMethod('get')) {
        $userId = session('user_id');
        $user = $userId ? DB::table('users')->where('id', $userId)->first() : null;

        if ($user) {
            return redirectToDashboard($user);
        }
        return renderView('auth.login');
    }

    if ($request->isMethod('post')) {
        $email = strtolower(trim($request->input('email', '')));
        $password = $request->input('password', '');

        $user = DB::table('users')->where('email', $email)->first();

        if ($user && Hash::check($password, $user->password)) {
            session(['user_id' => $user->id]);

            flash('success', "Hi, " . $user->name . "! Welcome back.");

            // Explicit direct check to prevent silent redirection failures
            if (in_array($user->role, ['pharmacist', 'pharmacy'], true)) {
                return redirect('/pharmacist');
            }

            if ($user->role === 'admin') {
                return redirect('/admin/dashboard');
            }

            return redirect('/requests');
        }

        flash('danger', 'Invalid credentials.');
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

        $user = null;

        DB::transaction(function () use ($name, $email, $password, $role, $pharmacyName, $licenseNumber, $location, $phoneNumber, &$user) {
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
                    'address' => $location,
                    'phone_number' => $phoneNumber, // Set to phone_number matching database structure
                    'license_number' => $licenseNumber,
                    'status' => 'pending',
                    'owner_id' => $userId,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            $user = DB::table('users')->where('id', $userId)->first();
        });

        // Store active session immediately after creation
        session(['user_id' => $user->id]);

        flash('success', $role === 'pharmacist'
            ? 'Registration successful. Welcome to your dashboard!'
            : 'Registration successful. Welcome!');

        return redirectToDashboard($user);
    }

    return renderView('auth.register');
})->name('register');

Route::get('/logout', function () {
    session()->forget('user_id');
    flash('info', 'Logged out.');
    return redirect('/');
})->name('logout');

// --- USER ACCOUNT & PROFILE SETTINGS ---

Route::match(['get', 'post'], '/account', function (Request $request) {
    // 1. Authenticate user using your custom global session helper
    $user = currentUser();
    if (!$user) {
        flash('danger', 'Please log in to view your profile.');
        return redirect('/login');
    }

    // 2. Form Submission: Update Profile Information
    if ($request->isMethod('post')) {
        $name = trim((string) $request->input('name', ''));
        $email = strtolower(trim($request->input('email', '')));
        $password = $request->input('password', '');

        if ($name === '' || $email === '') {
            flash('danger', 'Name and email fields are required.');
            return redirect('/account');
        }

        // Prepare the update array
        $updateData = [
            'name' => $name,
            'email' => $email,
            'updated_at' => now(),
        ];

        // Hash and include password if they provided a new one
        if (!empty($password)) {
            if (strlen($password) < 8) {
                flash('danger', 'New password must be at least 8 characters long.');
                return redirect('/account');
            }
            $updateData['password'] = Hash::make($password);
        }

        // Perform table save operations safely
        DB::table('users')->where('id', $user->id)->update($updateData);

        flash('success', 'Profile settings updated successfully!');
        return redirect('/account');
    }

    // 3. Page Render: Compile layout setup variables manually
    // This feeds your layout variables ($profileName, $profileRole, etc.) to match your dashboard
    return renderView('account', [
        'user'          => $user,
        'profileName'   => $user->name,
        'profileRole'   => ucfirst($user->role),
        'initials'      => strtoupper(substr($user->name, 0, 2)),
        'notificationBadge' => 0 // Fallback placeholder to keep your bell layout clean
    ]);
})->name('account');

// Route alias matching route('profile.settings') in dashboard.blade.php
Route::get('/profile/settings', function () {
    return redirect('/account');
})->name('profile.settings');

// --- PHARMACIST DASHBOARD & INVENTORY ---

Route::get('/pharmacist', [\App\Http\Controllers\PharmacyController::class, 'page'])->name('pharmacist.dashboard');
Route::get('/pharmacist/{section}', [\App\Http\Controllers\PharmacyController::class, 'page'])
    ->whereIn('section', ['medicines', 'inventory', 'prices', 'requests', 'subscription', 'profile', 'settings']);
Route::post('/pharmacist/add', [\App\Http\Controllers\PharmacyController::class, 'saveStock']);
Route::put('/pharmacist/inventory/{item}', [\App\Http\Controllers\PharmacyController::class, 'saveStock']);
Route::delete('/pharmacist/inventory/{item}', [\App\Http\Controllers\PharmacyController::class, 'removeStock']);
Route::post('/pharmacist/profile', [\App\Http\Controllers\PharmacyController::class, 'profile']);
Route::post('/pharmacist/settings', [\App\Http\Controllers\PharmacyController::class, 'settings']);
Route::post('/pharmacist/subscription', [\App\Http\Controllers\PharmacyController::class, 'submitPayment']);
Route::post('/pharmacist/requests/{reservation}/{action}', [\App\Http\Controllers\PharmacyController::class, 'reservation']);

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

    if (!$user) {
        return redirect('/login');
    }

    if (strtolower($user->role) !== 'patient') {
        return redirectToDashboard($user);
    }

    $reservations = DB::table('reservations as r')
        ->join('pharmacies as p', 'r.pharmacy_id', '=', 'p.id')
        ->join('medicines as m', 'r.medicine_id', '=', 'm.id')
        ->selectRaw('r.*, m.name as medicine_name, p.name as pharmacy_name, p.location as pharmacy_address')
        ->where('r.user_id', $user->id)
        ->orderByDesc('r.created_at')
        ->get();

    return renderView('patient_requests', compact('reservations'));
});

// --- ADMIN DASHBOARD ---
Route::get('/admin/subscriptions', [\App\Http\Controllers\PharmacyController::class, 'payments']);
Route::post('/admin/subscriptions/{payment}/{action}', [\App\Http\Controllers\PharmacyController::class, 'reviewPayment']);

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
