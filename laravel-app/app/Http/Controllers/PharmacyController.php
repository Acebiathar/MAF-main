<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class PharmacyController extends Controller
{
    private function pharmacy(bool $approved = false): object
    {
        $user = currentUser();
        abort_unless($user && in_array($user->role, ['pharmacist', 'pharmacy'], true), 403);
        $pharmacy = DB::table('pharmacies')->where('owner_id', $user->id)->first();
        abort_unless($pharmacy, 403, 'Please complete your pharmacy registration.');
        abort_if($approved && $pharmacy->status !== 'approved', 403, 'Your pharmacy must be approved to manage stock and reservations.');
        return $pharmacy;
    }

    public function page(Request $request, string $section = 'dashboard')
    {
        if (!currentUser()) return redirect('/login');
        $pharmacy = $this->pharmacy();
        $inventory = DB::table('pharmacy_medicine as pm')->join('medicines as m', 'pm.medicine_id', '=', 'm.id')
            ->where('pm.pharmacy_id', $pharmacy->id)->select('pm.*', 'm.name as medicine_name')->orderBy('m.name')->get();
        $reservations = DB::table('reservations as r')->join('medicines as m', 'r.medicine_id', '=', 'm.id')
            ->join('users as u', 'r.user_id', '=', 'u.id')->where('r.pharmacy_id', $pharmacy->id)
            ->select('r.*', 'm.name as medicine_name', 'u.name as user_name', 'u.email as user_email')->orderByDesc('r.created_at')->get();
        $pendingCount = $reservations->where('status', 'pending')->count();
        $isActive = $pharmacy->status === 'approved';
        $all_medicines = DB::table('medicines')->orderBy('name')->get();
        $payments = $section === 'subscription' ? DB::table('subscription_payments')->where('pharmacy_id', $pharmacy->id)->orderByDesc('id')->get() : collect();
        return renderView($section === 'requests' ? 'pharmacist_requests' : 'pharmacy.workspace', compact(
            'section', 'pharmacy', 'inventory', 'reservations', 'pendingCount', 'isActive', 'all_medicines', 'payments'
        ));
    }

    public function saveStock(Request $request, ?int $item = null)
    {
        $pharmacy = $this->pharmacy(true);
        $data = $request->validate([
            'medicine_name' => [$item ? 'nullable' : 'required', 'string', 'max:255'],
            'price' => ['required', 'numeric', 'min:0', 'max:99999999.99'],
            'quantity' => ['required', 'integer', 'min:0', 'max:2147483647'],
        ]);
        DB::transaction(function () use ($pharmacy, $data, $item) {
            // Serialize stock changes for this pharmacy, including first-time additions.
            DB::table('pharmacies')->where('id', $pharmacy->id)->lockForUpdate()->first();
            $stock = DB::table('pharmacy_medicine')->where('pharmacy_id', $pharmacy->id);
            if ($item) {
                $stock->where('id', $item);
                abort_unless((clone $stock)->exists(), 404);
            } else {
                $name = trim(preg_replace('/\s+/u', ' ', $data['medicine_name']));
                if ($name === '') throw ValidationException::withMessages(['medicine_name' => 'Enter a medicine name.']);
                $medicine = DB::table('medicines')->whereRaw('LOWER(name) = ?', [mb_strtolower($name)])->first();
                $medicineId = $medicine->id ?? DB::table('medicines')->insertGetId([
                    'name' => $name, 'category' => 'General', 'created_at' => now(), 'updated_at' => now(),
                ]);
                $stock->where('medicine_id', $medicineId);
            }
            $values = ['price' => $data['price'], 'quantity' => $data['quantity'],
                'stock_status' => $this->stockStatus((int) $data['quantity']), 'updated_at' => now()];
            if ((clone $stock)->exists()) $stock->update($values);
            else DB::table('pharmacy_medicine')->insert($values + ['pharmacy_id' => $pharmacy->id, 'medicine_id' => $medicineId, 'created_at' => now()]);
        });
        flash('success', 'Medicine saved. Your listing is now available in patient search.');
        return back();
    }

    private function stockStatus(int $quantity): string
    {
        return $quantity === 0 ? 'out_of_stock' : ($quantity <= 10 ? 'low_stock' : 'in_stock');
    }

    public function removeStock(int $item)
    {
        $pharmacy = $this->pharmacy(true);
        abort_unless(DB::table('pharmacy_medicine')->where('pharmacy_id', $pharmacy->id)->where('id', $item)->delete(), 404);
        flash('success', 'Medicine removed from your inventory. Reservation history is preserved.');
        return back();
    }

    public function reservation(int $reservation, string $action)
    {
        $pharmacy = $this->pharmacy(true);
        abort_unless(in_array($action, ['confirm', 'decline'], true), 404);
        DB::transaction(function () use ($pharmacy, $reservation, $action) {
            DB::table('pharmacies')->where('id', $pharmacy->id)->lockForUpdate()->first();
            $query = DB::table('reservations')->where('pharmacy_id', $pharmacy->id)->where('id', $reservation);
            $row = (clone $query)->lockForUpdate()->first();
            abort_unless($row, 404);
            if ($row->status !== 'pending') throw ValidationException::withMessages(['reservation' => 'This reservation has already been processed.']);
            if ($action === 'confirm') {
                $stock = DB::table('pharmacy_medicine')->where('pharmacy_id', $pharmacy->id)->where('medicine_id', $row->medicine_id);
                $item = (clone $stock)->lockForUpdate()->first();
                if (!$item || $item->quantity < 1) throw ValidationException::withMessages(['reservation' => 'Restock this medicine before approving the reservation.']);
                $stock->where('id', $item->id)->update(['quantity' => $item->quantity - 1, 'stock_status' => $this->stockStatus($item->quantity - 1), 'updated_at' => now()]);
            }
            $query->update(['status' => $action === 'confirm' ? 'confirmed' : 'declined', 'updated_at' => now()]);
        });
        flash('success', 'Reservation updated.');
        return back();
    }

    public function profile(Request $request)
    {
        $pharmacy = $this->pharmacy();
        $data = $request->validate(['name' => 'required|string|max:255', 'location' => 'required|string|max:255', 'phone_number' => 'required|string|max:30']);
        DB::table('pharmacies')->where('id', $pharmacy->id)->update($data + ['updated_at' => now()]);
        flash('success', 'Pharmacy profile updated.');
        return back();
    }

    public function settings(Request $request)
    {
        $this->pharmacy();
        $user = currentUser();
        $data = $request->validate(['name' => 'required|string|max:255', 'email' => ['required', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'current_password' => 'required|string', 'password' => 'nullable|string|min:8|confirmed']);
        if (!Hash::check($data['current_password'], $user->password)) throw ValidationException::withMessages(['current_password' => 'Your current password is incorrect.']);
        $values = ['name' => $data['name'], 'email' => $data['email'], 'updated_at' => now()];
        if (!empty($data['password'])) $values['password'] = Hash::make($data['password']);
        DB::table('users')->where('id', $user->id)->update($values);
        flash('success', 'Account settings saved.');
        return back();
    }

    public function submitPayment(Request $request)
    {
        $pharmacy = $this->pharmacy();
        $request->merge(['reference' => strtoupper(trim((string) $request->input('reference')))]);
        $data = $request->validate(['reference' => ['required', 'string', 'max:100', 'regex:/^[A-Z0-9-]+$/', 'unique:subscription_payments,reference'], 'phone' => 'required|string|max:30']);
        DB::transaction(function () use ($pharmacy, $data) {
            DB::table('pharmacies')->where('id', $pharmacy->id)->lockForUpdate()->first();
            if (DB::table('subscription_payments')->where('pharmacy_id', $pharmacy->id)->where('status', 'pending')->exists()) {
                throw ValidationException::withMessages(['reference' => 'Your previous payment is still awaiting verification.']);
            }
            DB::table('subscription_payments')->insert($data + ['pharmacy_id' => $pharmacy->id, 'amount' => config('subscription.amount'),
                'months' => max(1, config('subscription.months')), 'status' => 'pending', 'created_at' => now(), 'updated_at' => now()]);
        });
        flash('success', 'Payment reference submitted. Your subscription will activate after admin verification.');
        return back();
    }

    public function payments()
    {
        abort_unless(currentUser() && currentUser()->role === 'admin', 403);
        $payments = DB::table('subscription_payments as sp')->join('pharmacies as p', 'sp.pharmacy_id', '=', 'p.id')
            ->select('sp.*', 'p.name as pharmacy_name')->orderByDesc('sp.id')->get();
        return renderView('pharmacy.payment_review', compact('payments'));
    }

    public function reviewPayment(int $payment, string $action)
    {
        abort_unless(currentUser() && currentUser()->role === 'admin', 403);
        abort_unless(in_array($action, ['approve', 'reject'], true), 404);
        DB::transaction(function () use ($payment, $action) {
            $row = DB::table('subscription_payments')->where('id', $payment)->first();
            abort_unless($row, 404);
            DB::table('pharmacies')->where('id', $row->pharmacy_id)->lockForUpdate()->first();
            $row = DB::table('subscription_payments')->where('id', $payment)->lockForUpdate()->first();
            if ($row->status !== 'pending') throw ValidationException::withMessages(['payment' => 'This payment has already been reviewed.']);
            $values = ['status' => $action === 'approve' ? 'verified' : 'rejected', 'reviewed_by' => currentUser()->id, 'reviewed_at' => now(), 'updated_at' => now()];
            if ($action === 'approve') {
                $expiry = DB::table('subscription_payments')->where('pharmacy_id', $row->pharmacy_id)->where('status', 'verified')->max('expires_at');
                $start = $expiry && \Illuminate\Support\Carbon::parse($expiry)->isFuture() ? \Illuminate\Support\Carbon::parse($expiry) : now();
                $values['starts_at'] = $start->copy();
                $values['expires_at'] = $start->copy()->addMonthsNoOverflow($row->months);
            }
            DB::table('subscription_payments')->where('id', $payment)->update($values);
        });
        flash('success', 'Payment review saved.');
        return back();
    }
}
