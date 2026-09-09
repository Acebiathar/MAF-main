<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class AdminDashboardController extends Controller
{
    private function authorizeAdmin(): void
    {
        abort_unless(currentUser() && currentUser()->role === 'admin', 403);
    }

    public function page(Request $request, ?string $action = null, ?string $type = null)
    {
        if (!currentUser()) return redirect('/login');
        $this->authorizeAdmin();
        abort_unless(in_array($action, [null, 'dashboard', 'view', 'reports', 'activity', 'settings'], true), 404);
        if ($action === 'view') abort_unless(in_array($type, ['users', 'pharmacies', 'medicines', 'reservations'], true), 404);
        else abort_if($type !== null, 404);
        $stats = [
            'users' => DB::table('users')->count(), 'pharmacies' => DB::table('pharmacies')->count(),
            'medicines' => DB::table('medicines')->count(), 'reservations' => DB::table('reservations')->count(),
        ];
        $pending = DB::table('pharmacies')->where('status', 'pending')->orderBy('created_at')->get();
        $viewType = $action === 'view' ? $type : null;
        $viewList = match ($viewType) {
            'users' => DB::table('users')->orderByDesc('created_at')->get(),
            'pharmacies' => DB::table('pharmacies')->orderByDesc('created_at')->get(),
            'medicines' => DB::table('medicines')->orderBy('name')->get(),
            'reservations' => DB::table('reservations')->join('users', 'reservations.user_id', '=', 'users.id')->select('reservations.*', 'users.name as patient_name')->orderByDesc('reservations.created_at')->get(),
            default => null,
        };
        $recentUsers = DB::table('users')->select('id', 'name', 'role', 'created_at')->orderByDesc('created_at')->orderByDesc('id')->limit(5)->get();
        $recentActivity = $this->activity($action === 'activity' ? 40 : 5);
        $chartData = $this->chartData();
        $pendingPayments = DB::table('subscription_payments')->where('status', 'pending')->count();
        $approvedPharmacies = DB::table('pharmacies')->where('status', 'approved')->count();
        $mode = $action ?? 'dashboard';
        return renderView('admin_dashboard', compact('stats', 'pending', 'viewType', 'viewList', 'recentUsers', 'recentActivity', 'chartData', 'pendingPayments', 'approvedPharmacies', 'mode'));
    }

    private function activity(int $limit)
    {
        $events = collect();
        foreach (DB::table('users')->whereNotNull('created_at')->orderByDesc('created_at')->limit($limit)->get(['name', 'created_at']) as $row) {
            $events->push(['title' => 'User registered', 'detail' => $row->name, 'time' => $row->created_at, 'icon' => 'bi-person-plus', 'tone' => 'blue', 'url' => '/admin/view/users']);
        }
        foreach (DB::table('pharmacies')->whereNotNull('created_at')->orderByDesc('created_at')->limit($limit)->get(['name', 'created_at']) as $row) {
            $events->push(['title' => 'Pharmacy registered', 'detail' => $row->name, 'time' => $row->created_at, 'icon' => 'bi-shop', 'tone' => 'green', 'url' => '/admin/view/pharmacies']);
        }
        foreach (DB::table('pharmacy_medicine as pm')->join('medicines as m', 'pm.medicine_id', '=', 'm.id')->whereNotNull('pm.updated_at')->orderByDesc('pm.updated_at')->limit($limit)->get(['m.name', 'pm.updated_at']) as $row) {
            $events->push(['title' => 'Medicine stock updated', 'detail' => $row->name, 'time' => $row->updated_at, 'icon' => 'bi-capsule', 'tone' => 'purple', 'url' => '/admin/view/medicines']);
        }
        foreach (DB::table('reservations as r')->join('users as u', 'r.user_id', '=', 'u.id')->select('u.name', 'r.status')->selectRaw('COALESCE(r.updated_at, r.created_at) as event_time')->orderByDesc('event_time')->limit($limit)->get() as $row) {
            if (!$row->event_time) continue;
            $events->push(['title' => match ($row->status) {'confirmed' => 'Reservation approved', 'declined' => 'Reservation declined', default => 'Reservation placed'}, 'detail' => $row->name, 'time' => $row->event_time, 'icon' => 'bi-calendar-check', 'tone' => 'orange', 'url' => '/admin/view/reservations']);
        }
        return $events->sortByDesc('time')->take($limit)->values();
    }

    private function chartData(): array
    {
        $now = now();
        $yearStart = $now->copy()->startOfYear();
        $weekStart = $now->copy()->startOfWeek();
        $start = $weekStart->lessThan($yearStart) ? $weekStart : $yearStart;
        $daily = [];
        foreach (['reservations', 'users', 'pharmacies'] as $table) {
            $daily[$table] = DB::table($table)->whereBetween('created_at', [$start, $now])
                ->selectRaw('DATE(created_at) as day, COUNT(*) as total')->groupByRaw('DATE(created_at)')->get()->pluck('total', 'day');
        }
        $periods = [];
        foreach (['week' => [$weekStart, 7], 'month' => [$now->copy()->startOfMonth(), $now->daysInMonth], 'year' => [$yearStart, 12]] as $period => [$first, $count]) {
            $labels = []; $dates = []; $series = ['reservations' => [], 'users' => [], 'pharmacies' => []];
            for ($index = 0; $index < $count; $index++) {
                $date = $period === 'year' ? $first->copy()->addMonths($index) : $first->copy()->addDays($index);
                $labels[] = $date->format($period === 'week' ? 'D' : ($period === 'year' ? 'M' : 'j'));
                $dates[] = $date->format($period === 'year' ? 'F Y' : 'd M Y');
                foreach ($daily as $metric => $values) {
                    $series[$metric][] = $period === 'year'
                        ? (int) $values->filter(fn ($total, $day) => str_starts_with($day, $date->format('Y-m')))->sum()
                        : (int) $values->get($date->format('Y-m-d'), 0);
                }
            }
            $periods[$period] = ['labels' => $labels, 'dates' => $dates, 'series' => $series];
        }
        return $periods;
    }

    public function export()
    {
        $this->authorizeAdmin();
        $data = $this->chartData();
        return response()->streamDownload(function () use ($data) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Month', 'New users', 'New pharmacies', 'Reservations']);
            foreach ($data['year']['dates'] as $i => $month) fputcsv($file, [$month, $data['year']['series']['users'][$i], $data['year']['series']['pharmacies'][$i], $data['year']['series']['reservations'][$i]]);
            fclose($file);
        }, 'medfinder-report-'.now()->format('Y').'.csv', ['Content-Type' => 'text/csv']);
    }

    public function accountStatus(Request $request, int $user)
    {
        $this->authorizeAdmin();
        $adminId = currentUser()->id;
        $request->merge(['reason' => trim((string) $request->input('reason'))]);
        $data = $request->validate([
            'is_active' => 'required|boolean',
            'reason' => 'required|string|min:5|max:500',
            'confirmed' => 'accepted',
        ]);
        DB::transaction(function () use ($user, $adminId, $data) {
            $account = DB::table('users')->where('id', $user)->lockForUpdate()->first();
            abort_unless($account, 404);
            abort_if($account->role === 'admin' || $account->id === $adminId, 403, 'Administrator accounts are protected.');
            $active = (bool) $data['is_active'];
            if ((bool) $account->is_active === $active) return;
            DB::table('users')->where('id', $user)->update([
                'is_active' => $active,
                'session_version' => $account->session_version + 1,
                'remember_token' => null,
                'updated_at' => now(),
            ]);
            DB::table('account_status_events')->insert([
                'user_id' => $user, 'admin_id' => $adminId, 'is_active' => $active,
                'reason' => $data['reason'], 'created_at' => now(),
            ]);
        });
        flash('success', $data['is_active'] ? 'Account reactivated. The user can sign in again.' : 'Account deactivated. Sign-in and existing account sessions are blocked.');
        return redirect('/admin/view/users');
    }

    public function settings(Request $request)
    {
        $this->authorizeAdmin();
        $user = currentUser();
        $request->merge(['email' => strtolower(trim((string) $request->input('email')))]);
        $data = $request->validate(['name' => 'required|string|max:255', 'email' => ['required', 'email', 'max:255', Rule::unique('users')->ignore($user->id)], 'current_password' => 'required|string', 'password' => 'nullable|string|min:8|confirmed']);
        if (!Hash::check($data['current_password'], $user->password)) return back()->withErrors(['current_password' => 'Your current password is incorrect.'])->withInput($request->only('name', 'email'));
        $values = ['name' => $data['name'], 'email' => strtolower(trim($data['email'])), 'updated_at' => now()];
        if (!empty($data['password'])) $values['password'] = Hash::make($data['password']);
        DB::table('users')->where('id', $user->id)->update($values);
        flash('success', 'Account settings updated.');
        return back();
    }
}
