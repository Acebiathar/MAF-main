<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;

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

Route::get('/', function (Request $request) {
    $query = trim((string)$request->query('q', ''));
    $results = collect();
    $alternatives = collect();
    if ($query !== '') {
        $results = DB::table('pharmacy_medicine as pm')
            ->join('medicine as m', 'pm.medicine_id', '=', 'm.id')
            ->join('pharmacies as p', 'pm.pharmacy_id', '=', 'p.id')
            ->select('pm.*', 'm.name as medicine_name', 'm.category as medicine_category', 'p.name as pharmacy_name', 'p.location as pharmacy_location')
            ->where('m.name', 'like', "%{$query}%")
            ->where('p.status', '=', 'approved')
            ->get();

        $category = $results->first()->medicine_category ?? optional(
            DB::table('medicine')->where('name', 'like', "%{$query}%")->first()
        )->category;

        if ($category) {
            $alternatives = DB::table('medicine')
                ->where('category', $category)
                ->where('name', 'not like', "%{$query}%")
                ->limit(4)
                ->get();
        }
    }

    $pharmacies = DB::table('pharmacies')->where('status', 'approved')->limit(4)->get();
    return renderView('home', [
        'query' => $query,
        'results' => $results,
        'alternatives' => $alternatives,
        'pharmacies' => $pharmacies,
    ]);
});

Route::get('/how-it-works', function () {
    return renderView('how');
});

Route::get('/about', function () {
    return renderView('about');
});

Route::match(['get', 'post'], '/contact', function (Request $request) {
    if ($request->isMethod('post')) {
        flash('success', 'Thanks! We received your message and will reply soon.');
        return redirect('/contact');
    }
    return renderView('contact');
});

Route::match(['get', 'post'], '/login', function (Request $request) {
    if ($request->isMethod('post')) {
        $email = strtolower(trim($request->input('email', '')));
        $password = $request->input('password', '');
        $user = DB::table('users')->where('email', $email)->first();
        if ($user && Hash::check($password, $user->password)) {
            session(['user_id' => $user->id]);
            flash('success', 'Welcome back!');
            return redirect('/');
        }
        flash('danger', 'Invalid credentials.');
        return redirect('/login');
    }
    return renderView('auth.login');
});

Route::match(['get', 'post'], '/register', function (Request $request) {
    if ($request->isMethod('post')) {
        $email = strtolower(trim($request->input('email', '')));
        $exists = DB::table('users')->where('email', $email)->exists();
        if ($exists) {
            flash('warning', 'Email already registered.');
            return redirect('/register');
        }
        $name = $request->input('name', '');
        $role = $request->input('role', 'patient');
        $password = $request->input('password', '');

        DB::transaction(function () use ($request, $name, $email, $password, $role) {
            $userId = DB::table('users')->insertGetId([
                'name' => $name,
                'email' => $email,
                'password' => Hash::make($password),
                'role' => $role,
            ]);

            if ($role === 'pharmacist') {
                DB::table('pharmacy')->insert([
                    'name' => $request->input('pharmacy_name', ''),
                    'location' => $request->input('location', ''),
                    'license_number' => $request->input('license', ''),
                    'phone' => $request->input('phone', ''),
                    'status' => 'pending',
                    'owner_id' => $userId,
                ]);
                flash('info', 'Pharmacy submitted for approval. You can log in after admin approval.');
            } else {
                flash('success', 'Account created. You can now log in.');
            }
        });

        return redirect('/login');
    }

    return renderView('auth.register');
});

Route::get('/logout', function () {
    session()->forget('user_id');
    flash('info', 'Logged out.');
    return redirect('/');
});

Route::get('/pharmacist', function () {
    $user = currentUser();
    if (!$user) {
        flash('warning', 'Please log in first.');
        return redirect('/login');
    }
    if ($user->role !== 'pharmacist') {
        flash('danger', 'Not authorized.');
        return redirect('/');
    }
    $pharmacy = DB::table('pharmacy')->where('owner_id', $user->id)->first();
    if (!$pharmacy) {
        flash('warning', 'No pharmacy profile found.');
        return redirect('/');
    }
    $medicines = DB::table('medicine')->orderBy('name')->get();
    $inventory = DB::table('pharmacy_medicine as pm')
        ->join('medicine as m', 'pm.medicine_id', '=', 'm.id')
        ->select('pm.*', 'm.name as medicine_name')
        ->where('pm.pharmacy_id', $pharmacy->id)
        ->orderBy('m.name')
        ->get();
    return renderView('dashboard_pharmacist', compact('pharmacy', 'medicines', 'inventory'));
});

Route::post('/pharmacist/add', function (Request $request) {
    $user = currentUser();
    if (!$user || $user->role !== 'pharmacist') {
        flash('danger', 'Not authorized.');
        return redirect('/');
    }
    $pharmacy = DB::table('pharmacy')->where('owner_id', $user->id)->first();
    if (!$pharmacy) {
        flash('warning', 'No pharmacy profile found.');
        return redirect('/');
    }
    $medId = (int)$request->input('medicine_id');
    $price = (float)$request->input('price');
    $qty = (int)$request->input('quantity');
    $status = $request->input('stock_status', 'in_stock');

    $existing = DB::table('pharmacy_medicine')
        ->where('pharmacy_id', $pharmacy->id)
        ->where('medicine_id', $medId)
        ->first();

    if ($existing) {
        DB::table('pharmacy_medicine')
            ->where('id', $existing->id)
            ->update(['price' => $price, 'quantity' => $qty, 'stock_status' => $status]);
    } else {
        DB::table('pharmacy_medicine')->insert([
            'pharmacy_id' => $pharmacy->id,
            'medicine_id' => $medId,
            'price' => $price,
            'stock_status' => $status,
            'quantity' => $qty,
        ]);
    }
    flash('success', 'Inventory updated.');
    return redirect('/pharmacist');
});

Route::post('/reserve/{item}', function (int $item) {
    $user = currentUser();
    if (!$user) {
        flash('warning', 'Please log in as a patient to place a request.');
        return redirect('/login');
    }
    if ($user->role !== 'patient') {
        flash('danger', 'Only patients can place reservations.');
        return redirect('/');
    }
    $itemRow = DB::table('pharmacy_medicine')->where('id', $item)->first();
    if (!$itemRow) {
        flash('danger', 'Item not found.');
        return redirect('/');
    }
    $note = request('note', '');
    DB::table('reservation')->insert([
        'user_id' => $user->id,
        'pharmacy_id' => $itemRow->pharmacy_id,
        'medicine_id' => $itemRow->medicine_id,
        'status' => 'pending',
        'created_at' => now(),
        'note' => $note,
    ]);
    $medName = DB::table('medicine')->where('id', $itemRow->medicine_id)->value('name') ?? '';
    flash('success', 'Reservation sent to the pharmacy.');
    return redirect('/?q=' . urlencode($medName));
});

Route::get('/requests', function () {
    $user = currentUser();
    if (!$user || $user->role !== 'patient') {
        flash('warning', 'Please log in first.');
        return redirect('/login');
    }
    $reservations = DB::table('reservation as r')
        ->join('pharmacy as p', 'r.pharmacy_id', '=', 'p.id')
        ->join('medicine as m', 'r.medicine_id', '=', 'm.id')
        ->select('r.*', 'm.name as medicine_name', 'p.name as pharmacy_name', 'p.location as pharmacy_location')
        ->where('r.user_id', $user->id)
        ->orderByDesc('r.created_at')
        ->get();
    return renderView('patient_requests', compact('reservations'));
});

Route::get('/pharmacist/requests', function () {
    $user = currentUser();
    if (!$user || $user->role !== 'pharmacist') {
        flash('warning', 'Please log in first.');
        return redirect('/login');
    }
    $pharmacy = DB::table('pharmacy')->where('owner_id', $user->id)->first();
    if (!$pharmacy) {
        flash('warning', 'No pharmacy profile found.');
        return redirect('/');
    }
    $reservations = DB::table('reservation as r')
        ->join('user as u', 'r.user_id', '=', 'u.id')
        ->join('medicine as m', 'r.medicine_id', '=', 'm.id')
        ->select('r.*', 'u.name as user_name', 'u.email as user_email', 'm.name as medicine_name')
        ->where('r.pharmacy_id', $pharmacy->id)
        ->orderByDesc('r.created_at')
        ->get();
    return renderView('pharmacist_requests', compact('reservations', 'pharmacy'));
});

Route::post('/pharmacist/requests/{reservation}/{action}', function (int $reservation, string $action) {
    $user = currentUser();
    if (!$user || $user->role !== 'pharmacist') {
        flash('warning', 'Please log in first.');
        return redirect('/login');
    }
    $pharmacy = DB::table('pharmacy')->where('owner_id', $user->id)->first();
    if (!$pharmacy) {
        flash('warning', 'No pharmacy profile found.');
        return redirect('/');
    }
    $res = DB::table('reservation')->where('id', $reservation)->first();
    if (!$res || (int)$res->pharmacy_id !== (int)$pharmacy->id) {
        flash('danger', 'Not authorized.');
        return redirect('/');
    }
    $status = $action === 'confirm' ? 'confirmed' : 'declined';
    DB::table('reservation')->where('id', $reservation)->update(['status' => $status]);
    flash('success', 'Reservation updated.');
    return redirect('/pharmacist/requests');
});

Route::get('/admin', function () {
    $user = currentUser();
    if (!$user || $user->role !== 'admin') {
        flash('danger', 'Not authorized.');
        return redirect('/');
    }
    $pending = DB::table('pharmacy')->where('status', 'pending')->get();
    $stats = [
        'users' => DB::table('user')->count(),
        'pharmacies' => DB::table('pharmacy')->count(),
        'medicines' => DB::table('medicine')->count(),
        'reservations' => DB::table('reservation')->count(),
    ];
    return renderView('admin_dashboard', compact('pending', 'stats'));
});

Route::get('/admin/pharmacies/{pharmacy}/{action}', function (int $pharmacy, string $action) {
    $user = currentUser();
    if (!$user || $user->role !== 'admin') {
        flash('danger', 'Not authorized.');
        return redirect('/');
    }
    $ph = DB::table('pharmacy')->where('id', $pharmacy)->first();
    if (!$ph) {
        flash('danger', 'Pharmacy not found.');
        return redirect('/admin');
    }
    $newStatus = $action === 'approve' ? 'approved' : 'rejected';
    DB::table('pharmacy')->where('id', $pharmacy)->update(['status' => $newStatus]);
    flash($newStatus === 'approved' ? 'success' : 'warning', 'Pharmacy ' . ($newStatus === 'approved' ? 'approved' : 'rejected') . '.');
    return redirect('/admin');
});
