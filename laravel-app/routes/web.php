<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;

function currentUser() {
    $userId = session('user_id');
    return $userId ? DB::table('users')->where('id', $userId)->first() : null;
}

function flash($category, $message) {
    session()->push('alerts', [$category, $message]);
}

function renderView(string $view, array $data = []) {
    $data['currentUser'] = currentUser();
    $data['alerts'] = session()->pull('alerts', []);
    return view($view, $data);
}
// --- PHARMACIST DASHBOARD ---
Route::get('/pharmacist', function () {
    $user = currentUser();
    if (!$user || $user->role !== 'pharmacist') {
        flash('warning', 'Please log in first.');
        return redirect('/login');
    }

    $pharmacy = DB::table('pharmacies')->where('owner_id', $user->id)->first();
    if (!$pharmacy) {
        flash('warning', 'No pharmacy profile found.');
        return redirect('/');
    }
    
    // FIX 1: Pass the medicines list for the dropdown
    $all_medicines = DB::table('medicines')->orderBy('name')->get();
    
    // FIX 3 & 4: Use singular 'pharmacy_medicine' and correct JOIN syntax
    $inventory = DB::table('pharmacy_medicine as pm')
        ->leftJoin('medicines as m', 'pm.medicine_id', '=', 'm.id')
        ->select('pm.*', 'm.name as medicine_name')
        ->where('pm.pharmacy_id', $pharmacy->id)
        ->orderBy('m.name')
        ->get();
    
    return renderView('dashboard_pharmacist', compact('pharmacy', 'all_medicines', 'inventory'));
});

// --- ADD/UPDATE STOCK ---
Route::post('/pharmacist/add', function (Request $request) {
    $user = currentUser();
    if (!$user || $user->role !== 'pharmacist') return redirect('/');
    
    $pharmacy = DB::table('pharmacies')->where('owner_id', $user->id)->first();
    
    $medicineName = trim(strtolower($request->input('medicine_name')));
    $price = (float)$request->input('price');
    $qty = (int)$request->input('quantity');
    $status = $request->input('stock_status', 'in_stock');

    $medicine = DB::table('medicines')->where('name', $medicineName)->first();
    if ($medicine) {
        $medId = $medicine->id;
    } else {
        // FIX 2: Added 'General' category to satisfy NOT NULL constraint
        $medId = DB::table('medicines')->insertGetId([
            'name' => $medicineName,
            'category' => 'General', 
            'created_at' => now(),
            'updated_at' => now()
        ]);
    }

    // Use SINGULAR table name here as well
    DB::table('pharmacy_medicine')->updateOrInsert(
        ['pharmacy_id' => $pharmacy->id, 'medicine_id' => $medId],
        [
            'price' => $price, 
            'quantity' => $qty, 
            'stock_status' => $status,
            'updated_at' => now()
        ]
    );

    flash('success', 'Inventory updated.');
    return redirect('/pharmacist');
});

Route::post('/reserve/{item}', function (int $item) {
    $user = currentUser();
    if (!$user || $user->role !== 'patient') return redirect('/login');
    
    // Using SINGULAR pharmacy_medicine
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
    
    $medName = DB::table('medicines')->where('id', $itemRow->medicine_id)->value('name') ?? '';
    flash('success', 'Reservation sent to the pharmacy.');
    return redirect('/?q=' . urlencode($medName));
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

Route::get('/pharmacist/requests', function () {
    $user = currentUser();
    if (!$user || $user->role !== 'pharmacist') return redirect('/login');

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
    
    $res = DB::table('reservations')->where('id', $reservation)->first();
    if (!$res || (int)$res->pharmacy_id !== (int)$pharmacy->id) return redirect('/');

    $status = $action === 'confirm' ? 'confirmed' : 'declined';
    DB::table('reservations')->where('id', $reservation)->update(['status' => $status]);
    flash('success', 'Reservation updated.');
    return redirect('/pharmacist/requests');
});

Route::get('/admin', function () {
    $user = currentUser();
    if (!$user || $user->role !== 'admin') return redirect('/');

    $pending = DB::table('pharmacies')->where('status', 'pending')->get();
    $stats = [
        'users' => DB::table('users')->count(),
        'pharmacies' => DB::table('pharmacies')->count(),
        'medicines' => DB::table('medicines')->count(),
        'reservations' => DB::table('reservations')->count(),
    ];
    return renderView('admin_dashboard', compact('pending', 'stats'));
});

Route::get('/admin/pharmacies/{pharmacy}/{action}', function (int $pharmacy, string $action) {
    $user = currentUser();
    if (!$user || $user->role !== 'admin') return redirect('/');

    $newStatus = $action === 'approve' ? 'approved' : 'rejected';
    DB::table('pharmacies')->where('id', $pharmacy)->update(['status' => $newStatus]);
    flash('success', "Pharmacy status updated to $newStatus.");
    return redirect('/admin');
});