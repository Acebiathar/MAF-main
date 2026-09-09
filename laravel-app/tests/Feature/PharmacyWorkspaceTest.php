<?php

namespace Tests\Feature;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class PharmacyWorkspaceTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        config(['database.default' => 'sqlite', 'database.connections.sqlite.database' => ':memory:']);
        DB::purge('sqlite');
        // Isolated schema: historical project migrations contain duplicate columns.
        Schema::create('users', function (Blueprint $t) {
            $t->id(); $t->string('name'); $t->string('email')->unique(); $t->string('password'); $t->string('role'); $t->rememberToken(); $t->timestamps();
        });
        Schema::create('pharmacies', function (Blueprint $t) {
            $t->id(); $t->integer('owner_id'); $t->string('name'); $t->string('location'); $t->string('phone_number'); $t->string('license_number'); $t->string('status'); $t->timestamps();
        });
        Schema::create('medicines', function (Blueprint $t) {
            $t->id(); $t->string('name'); $t->string('category'); $t->timestamps();
        });
        Schema::create('pharmacy_medicine', function (Blueprint $t) {
            $t->id(); $t->integer('pharmacy_id'); $t->integer('medicine_id'); $t->decimal('price', 10, 2); $t->integer('quantity'); $t->string('stock_status'); $t->timestamps();
        });
        Schema::create('reservations', function (Blueprint $t) {
            $t->id(); $t->integer('pharmacy_id'); $t->integer('medicine_id'); $t->integer('user_id'); $t->string('status'); $t->text('note')->nullable(); $t->timestamps();
        });
        (require database_path('migrations/2026_09_08_000001_create_subscription_payments_table.php'))->up();
        (require database_path('migrations/2026_09_08_000002_create_search_history_table.php'))->up();
        (require database_path('migrations/2014_10_12_100000_create_password_reset_tokens_table.php'))->up();
        (require database_path('migrations/2026_09_09_000001_add_account_deactivation.php'))->up();
        foreach ([1 => 'pharmacist', 2 => 'pharmacist', 3 => 'patient', 4 => 'admin'] as $id => $role) {
            DB::table('users')->insert(['id' => $id, 'name' => 'User '.$id, 'email' => $id.'@example.com', 'password' => Hash::make('password123'), 'role' => $role]);
        }
        foreach ([1, 2] as $id) DB::table('pharmacies')->insert(['id' => $id, 'owner_id' => $id, 'name' => 'Pharmacy '.$id, 'location' => 'Kampala', 'phone_number' => '0700000000', 'license_number' => 'LIC'.$id, 'status' => 'approved']);
        DB::table('medicines')->insert(['id' => 1, 'name' => 'Paracetamol', 'category' => 'General']);
        DB::table('pharmacy_medicine')->insert(['id' => 1, 'pharmacy_id' => 1, 'medicine_id' => 1, 'quantity' => 1, 'price' => 500, 'stock_status' => 'low_stock']);
        DB::table('reservations')->insert(['id' => 1, 'pharmacy_id' => 1, 'medicine_id' => 1, 'user_id' => 3, 'status' => 'pending', 'created_at' => now()]);
    }

    public function test_sidebar_pages_render_and_notifications_are_inside_dropdown(): void
    {
        foreach (['', '/medicines', '/inventory', '/prices', '/requests', '/profile', '/settings', '/subscription'] as $path) {
            $response = $this->withSession(['user_id' => 1])->get('/pharmacist'.$path);
            $response->assertOk()->assertSee('Stock &amp; Inventory', false)->assertSee('Subscription');
        }
        $html = $this->get('/pharmacist')->getContent();
        $this->assertStringContainsString('dashboard-notification-menu', $html);
        $this->assertSame(1, substr_count($html, 'Review and approve patient requests.'));
        $this->assertLessThan(strpos($html, 'id="profileDropdown"'), strpos($html, 'Review and approve patient requests.'));
    }

    public function test_patient_can_register_log_out_and_log_back_into_patient_dashboard(): void
    {
        $credentials = ['name' => 'New Patient', 'email' => 'New.Patient@Example.com', 'password' => 'Patient-pass123', 'role' => 'patient'];
        $this->from('/register')->post('/register', $credentials)->assertRedirect('/requests');
        $user = DB::table('users')->where('email', 'new.patient@example.com')->first();
        $this->assertNotNull($user);
        $this->assertSame('patient', $user->role);
        $this->assertTrue(Hash::check($credentials['password'], $user->password));
        $this->get('/requests')->assertOk()->assertSee('Patient Dashboard');
        $this->get('/logout')->assertRedirect('/')->assertSessionMissing('user_id');
        $this->post('/login', ['email' => ' NEW.PATIENT@EXAMPLE.COM ', 'password' => $credentials['password']])
            ->assertRedirect('/requests')->assertSessionHas('user_id', $user->id);
        $this->get('/requests')->assertOk()->assertSee('New Patient')->assertSee('Patient Dashboard');
    }

    public function test_patient_search_history_is_saved_private_and_can_be_revisited_and_cleared(): void
    {
        DB::table('users')->insert(['id' => 5, 'name' => 'Other Patient', 'email' => 'other@example.com', 'password' => Hash::make('password123'), 'role' => 'patient']);
        $this->withSession(['user_id' => 5])->get('/?search=OtherMedicine')->assertOk();
        $this->withSession(['user_id' => 3])->get('/?search=paracetamol')->assertOk();
        $this->assertDatabaseHas('search_history', ['user_id' => 3, 'query' => 'paracetamol', 'result_count' => 1]);
        $this->get('/requests?section=history')->assertOk()->assertSee('Search History')->assertSee('paracetamol')
            ->assertSee('Search again')->assertSee('search=paracetamol')->assertDontSee('OtherMedicine');
        $this->get('/logout');
        $this->post('/login', ['email' => '3@example.com', 'password' => 'password123'])->assertRedirect('/requests');
        $this->get('/requests?section=history')->assertOk()->assertSee('paracetamol');
        $this->delete('/requests/search-history')->assertRedirect('/requests?section=history');
        $this->assertDatabaseMissing('search_history', ['user_id' => 3]);
        $this->assertDatabaseHas('search_history', ['user_id' => 5, 'query' => 'OtherMedicine']);
        $this->get('/requests?section=history')->assertSee('No searches yet.');
    }

    public function test_patient_sidebar_sections_render_without_javascript_and_history_excludes_guests_and_providers(): void
    {
        $this->get('/?search=paracetamol')->assertOk();
        $this->withSession(['user_id' => 1])->get('/?search=paracetamol')->assertOk();
        $this->assertDatabaseCount('search_history', 0);
        $this->delete('/requests/search-history')->assertForbidden();
        foreach (['home' => 'Total Requests', 'requests' => 'Your Reservations', 'reservations' => 'Your Reservations', 'notifications' => 'Request Status Alerts', 'history' => 'No searches yet.'] as $section => $heading) {
            $this->withSession(['user_id' => 3])->get('/requests?section='.$section)->assertOk()
                ->assertSee($heading)->assertSee('Search History')->assertSee('aria-current="page"', false)->assertDontSee('onclick="showSection', false);
        }
        $this->get('/?search=')->assertOk();
        $this->assertDatabaseCount('search_history', 0);
    }

    public function test_new_patient_activity_and_pharmacist_decisions_appear_in_dashboard(): void
    {
        $this->post('/register', ['name' => 'Activity Patient', 'email' => 'activity@example.com', 'password' => 'Patient-pass123', 'role' => 'patient'])->assertRedirect('/requests');
        $patient = DB::table('users')->where('email', 'activity@example.com')->first();
        $this->get('/?search=paracetamol')->assertOk();
        $this->get('/requests')->assertOk()->assertSee('Recent Searches')->assertSee('paracetamol')->assertSee('Searches: 1')->assertSee('patientSidebarMenu');
        $this->post('/reserve/1', ['note' => 'Pickup tomorrow'])->assertRedirect('/requests?section=reservations');
        $reservation = DB::table('reservations')->where('user_id', $patient->id)->first();
        $this->assertNotNull($reservation);
        $this->assertSame('pending', $reservation->status);
        $this->post('/reserve/1')->assertRedirect('/requests?section=reservations');
        $this->assertSame(1, DB::table('reservations')->where('user_id', $patient->id)->count());
        $this->get('/requests?section=reservations')->assertOk()->assertSee('Pickup tomorrow')->assertSee('Pharmacy 1')->assertSee('Kampala');
        $this->get('/requests')->assertOk()->assertSee('Recent Reservations')->assertSee('Paracetamol')->assertSee('Pharmacy 1');
        $this->withSession(['user_id' => 1])->post('/pharmacist/requests/'.$reservation->id.'/confirm')->assertRedirect();
        $this->withSession(['user_id' => $patient->id])->get('/requests')->assertOk()
            ->assertViewHas('reservations', fn ($rows) => $rows->count() === 1 && $rows->first()->status === 'confirmed')
            ->assertSee('Ready for Pickup');
        $this->get('/requests?section=reservations')->assertSee('Ready for Pick-up');
        $this->withSession(['user_id' => 3])->get('/requests?section=reservations')->assertDontSee('Pickup tomorrow');
    }

    public function test_unavailable_stock_and_unapproved_pharmacies_cannot_be_reserved(): void
    {
        DB::table('pharmacy_medicine')->where('id', 1)->update(['quantity' => 0]);
        $this->withSession(['user_id' => 3])->from('/?search=paracetamol')->post('/reserve/1')->assertSessionHasErrors('reservation');
        $this->get('/?search=paracetamol')->assertSee('This medicine is currently unavailable.');
        DB::table('pharmacy_medicine')->where('id', 1)->update(['quantity' => 10]);
        DB::table('pharmacies')->where('id', 1)->update(['status' => 'pending']);
        $this->post('/reserve/1')->assertSessionHasErrors('reservation');
        $this->post('/reserve/9999')->assertSessionHasErrors('reservation');
        $this->assertDatabaseCount('reservations', 1);
        $this->withSession(['user_id' => 1])->post('/reserve/1')->assertForbidden();
    }

    public function test_failed_registration_shows_errors_without_creating_a_patient_or_flashing_password(): void
    {
        $this->from('/register')->post('/register', ['name' => 'New Patient', 'email' => 'new@example.com', 'password' => 'short', 'role' => 'patient'])
            ->assertRedirect('/register')->assertSessionHasErrors('password')->assertSessionMissing('_old_input.password')->assertSessionMissing('user_id');
        $this->assertDatabaseMissing('users', ['email' => 'new@example.com']);
        $this->get('/register')->assertOk()->assertSee('The password field must be at least 8 characters.');
    }

    public function test_pharmacist_registration_matches_pharmacy_database_columns(): void
    {
        $this->post('/register', ['name' => 'New Pharmacist', 'email' => 'new-pharmacist@example.com', 'password' => 'Pharmacy-pass123',
            'role' => 'pharmacist', 'pharmacy_name' => 'New Pharmacy', 'license_number' => 'NEW-123', 'location' => 'Entebbe', 'phone' => '0700000001'])
            ->assertRedirect('/pharmacist');
        $user = DB::table('users')->where('email', 'new-pharmacist@example.com')->first();
        $this->assertNotNull($user);
        $this->assertDatabaseHas('pharmacies', ['owner_id' => $user->id, 'name' => 'New Pharmacy', 'location' => 'Entebbe', 'status' => 'pending']);
        $this->get('/pharmacist')->assertOk()->assertSee('New Pharmacy');
    }

    public function test_login_handles_existing_email_capitalization_and_rejects_wrong_password(): void
    {
        DB::table('users')->where('id', 3)->update(['email' => 'Existing.Patient@Example.com']);
        $this->from('/login')->post('/login', ['email' => 'existing.patient@example.com', 'password' => 'incorrect'])
            ->assertRedirect('/login')->assertSessionHasErrors('email')->assertSessionMissing('user_id')->assertSessionMissing('_old_input.password');
        $this->post('/login', ['email' => 'existing.patient@example.com', 'password' => 'password123'])
            ->assertRedirect('/requests')->assertSessionHas('user_id', 3);
    }

    public function test_post_logout_clears_sessions_for_every_role(): void
    {
        foreach ([1 => '/pharmacist', 3 => '/requests', 4 => '/admin'] as $id => $dashboard) {
            $this->withSession(['user_id' => $id, 'user_session_version' => 0, 'private_marker' => 'remove-me'])
                ->post('/logout')->assertRedirect('/')
                ->assertSessionMissing('user_id')->assertSessionMissing('user_session_version')->assertSessionMissing('private_marker');
            $this->get($dashboard)->assertRedirect('/login');
        }
        $this->post('/logout')->assertRedirect('/');
    }

    public function test_login_redirects_all_roles_and_logout_removes_the_session(): void
    {
        foreach ([1 => '/pharmacist', 3 => '/requests', 4 => '/admin'] as $id => $dashboard) {
            $this->post('/login', ['email' => $id.'@example.com', 'password' => 'password123'])->assertRedirect($dashboard)->assertSessionHas('user_id', $id);
            $this->get($dashboard)->assertOk();
            $this->get('/login')->assertRedirect($dashboard);
            $this->get('/logout')->assertRedirect('/')->assertSessionMissing('user_id');
        }
    }

    public function test_password_recovery_is_single_use_and_restores_role_specific_login(): void
    {
        \Illuminate\Support\Facades\Notification::fake();
        $user = \App\Models\User::find(3);
        $this->from('/forgot-password')->post('/forgot-password', ['email' => '3@example.com'])->assertRedirect('/forgot-password');
        $token = null;
        \Illuminate\Support\Facades\Notification::assertSentTo($user, \Illuminate\Auth\Notifications\ResetPassword::class, function ($notification) use (&$token) {
            $token = $notification->token;
            return true;
        });
        $this->get('/reset-password/'.$token.'?email=3%40example.com')->assertOk()->assertSee('Choose a new password');
        $data = ['email' => '3@example.com', 'token' => $token, 'password' => 'New-patient123', 'password_confirmation' => 'New-patient123'];
        $this->post('/reset-password', $data)->assertRedirect('/login');
        $this->assertTrue(Hash::check('New-patient123', DB::table('users')->where('id', 3)->value('password')));
        $this->assertDatabaseCount('password_reset_tokens', 0);
        $this->post('/reset-password', $data)->assertSessionHasErrors('email');
        $this->post('/login', ['email' => '3@example.com', 'password' => 'password123'])->assertSessionHasErrors('email');
        $this->post('/login', ['email' => '3@example.com', 'password' => 'New-patient123'])->assertRedirect('/requests');
    }

    public function test_expired_or_invalid_reset_tokens_do_not_change_passwords(): void
    {
        $user = \App\Models\User::find(3);
        $token = \Illuminate\Support\Facades\Password::createToken($user);
        DB::table('password_reset_tokens')->where('email', $user->email)->update(['created_at' => now()->subHours(2)]);
        $data = ['email' => $user->email, 'token' => $token, 'password' => 'New-patient123', 'password_confirmation' => 'New-patient123'];
        $this->post('/reset-password', $data)->assertSessionHasErrors('email');
        $data['token'] = 'not-a-valid-token';
        $this->post('/reset-password', $data)->assertSessionHasErrors('email');
        $this->assertTrue(Hash::check('password123', $user->fresh()->password));
    }

    public function test_failed_reset_email_does_not_leave_an_undelivered_token(): void
    {
        \Illuminate\Support\Facades\Notification::shouldReceive('send')->once()->andThrow(new \RuntimeException('Email unavailable'));
        $this->from('/forgot-password')->post('/forgot-password', ['email' => '3@example.com'])
            ->assertRedirect('/forgot-password')->assertSessionHasErrors('email');
        $this->assertDatabaseCount('password_reset_tokens', 0);
        $this->assertTrue(Hash::check('password123', DB::table('users')->where('id', 3)->value('password')));
    }

    public function test_reset_email_throttle_does_not_claim_another_email_was_sent(): void
    {
        \Illuminate\Support\Facades\Notification::fake();
        $this->from('/forgot-password')->post('/forgot-password', ['email' => '3@example.com'])->assertRedirect('/forgot-password');
        $this->get('/forgot-password')->assertOk();
        $this->post('/forgot-password', ['email' => '3@example.com'])->assertSessionHasErrors('email');
        $this->get('/forgot-password')->assertSee('Please wait one minute')->assertDontSee('a password reset link has been sent');
        \Illuminate\Support\Facades\Notification::assertCount(1);
    }

    public function test_login_limits_failed_attempts_and_registration_cannot_grant_admin_access(): void
    {
        for ($attempt = 0; $attempt < 5; $attempt++) {
            $this->post('/login', ['email' => '3@example.com', 'password' => 'wrong'])->assertSessionHasErrors('email');
        }
        $this->post('/login', ['email' => '3@example.com', 'password' => 'password123'])->assertSessionHasErrors('email');
        $this->get('/login')->assertSee('Too many sign-in attempts.');
        $this->post('/register', ['name' => 'Not Admin', 'email' => 'notadmin@example.com', 'password' => 'password123', 'role' => 'admin'])->assertSessionHasErrors('role');
        $this->assertDatabaseMissing('users', ['email' => 'notadmin@example.com']);
    }

    public function test_stock_add_update_validation_and_owned_removal(): void
    {
        $this->withSession(['user_id' => 1])->from('/pharmacist/medicines')->post('/pharmacist/add', ['medicine_name' => 'PARACETAMOL', 'quantity' => 20, 'price' => 600])->assertRedirect();
        $this->assertDatabaseCount('medicines', 1);
        $this->assertDatabaseCount('pharmacy_medicine', 1);
        $this->assertDatabaseHas('pharmacy_medicine', ['id' => 1, 'quantity' => 20, 'stock_status' => 'in_stock']);
        $this->put('/pharmacist/inventory/1', ['quantity' => -1, 'price' => 20])->assertSessionHasErrors('quantity');
        $this->withSession(['user_id' => 2])->delete('/pharmacist/inventory/1')->assertNotFound();
        $this->put('/pharmacist/inventory/1', ['quantity' => 4, 'price' => 20])->assertNotFound();
        $this->withSession(['user_id' => 1])->delete('/pharmacist/inventory/1')->assertRedirect();
        $this->assertDatabaseCount('pharmacy_medicine', 0);
        $this->assertDatabaseCount('reservations', 1);
    }

    public function test_approval_deducts_once_and_rejects_unavailable_stock(): void
    {
        $this->withSession(['user_id' => 2])->post('/pharmacist/requests/1/confirm')->assertNotFound();
        $this->withSession(['user_id' => 1])->post('/pharmacist/requests/1/confirm')->assertRedirect();
        $this->assertDatabaseHas('pharmacy_medicine', ['id' => 1, 'quantity' => 0, 'stock_status' => 'out_of_stock']);
        $this->assertDatabaseHas('reservations', ['id' => 1, 'status' => 'confirmed']);
        $this->post('/pharmacist/requests/1/confirm')->assertSessionHasErrors('reservation');
        DB::table('reservations')->where('id', 1)->update(['status' => 'pending']);
        $this->post('/pharmacist/requests/1/confirm')->assertSessionHasErrors('reservation');
        $this->assertDatabaseHas('reservations', ['id' => 1, 'status' => 'pending']);
        $this->post('/pharmacist/requests/1/invalid')->assertNotFound();
        $this->post('/pharmacist/requests/1/decline')->assertRedirect();
        $this->assertDatabaseHas('reservations', ['id' => 1, 'status' => 'declined']);
    }

    public function test_pharmacist_can_approve_from_dashboard_and_queue_with_pending_requests_first(): void
    {
        DB::table('reservations')->insert(['id' => 2, 'pharmacy_id' => 1, 'medicine_id' => 1, 'user_id' => 3, 'status' => 'confirmed', 'created_at' => now()->addMinute()]);
        foreach (['/pharmacist', '/pharmacist/requests'] as $page) {
            $this->withSession(['user_id' => 1])->get($page)->assertOk()
                ->assertSee('/pharmacist/requests/1/confirm')->assertSee('Approve reservation for User 3: Paracetamol')
                ->assertSee('/pharmacist/requests/1/decline')->assertDontSee('/pharmacist/requests/2/confirm')
                ->assertViewHas('reservations', fn ($rows) => $rows->first()->id === 1);
        }
        $this->withSession(['user_id' => 2])->get('/pharmacist')->assertOk()->assertDontSee('/pharmacist/requests/1/confirm');
        $this->withSession(['user_id' => 1])->from('/pharmacist')->post('/pharmacist/requests/1/confirm')->assertRedirect('/pharmacist');
        $this->get('/pharmacist')->assertSee('Approved for pickup')->assertDontSee('/pharmacist/requests/1/confirm');
        $this->assertDatabaseHas('reservations', ['id' => 1, 'status' => 'confirmed']);
        $this->assertDatabaseHas('pharmacy_medicine', ['id' => 1, 'quantity' => 0]);
        $this->withSession(['user_id' => 3])->get('/requests?section=reservations')->assertOk()->assertSee('Ready for Pick-up');
    }

    public function test_pharmacist_dashboard_explains_when_stock_is_needed_for_approval(): void
    {
        DB::table('pharmacy_medicine')->where('id', 1)->update(['quantity' => 0]);
        $this->withSession(['user_id' => 1])->get('/pharmacist')->assertOk()->assertSee('Restock this medicine to approve.');
        $this->get('/pharmacist/requests')->assertOk()->assertSee('Restock this medicine to approve.');
        $this->post('/pharmacist/requests/1/confirm')->assertSessionHasErrors('reservation');
        $this->assertDatabaseHas('reservations', ['id' => 1, 'status' => 'pending']);
        $this->post('/pharmacist/requests/1/decline')->assertRedirect();
        $this->assertDatabaseHas('reservations', ['id' => 1, 'status' => 'declined']);
    }

    public function test_new_medicine_can_be_added_and_marked_out_of_stock(): void
    {
        $this->withSession(['user_id' => 1])->post('/pharmacist/add', ['medicine_name' => 'Amoxicillin 500mg', 'quantity' => 30, 'price' => 1200])->assertRedirect();
        $medicine = DB::table('medicines')->where('name', 'Amoxicillin 500mg')->first();
        $this->assertNotNull($medicine);
        $stock = DB::table('pharmacy_medicine')->where('medicine_id', $medicine->id)->first();
        $this->assertSame(1, (int) $stock->pharmacy_id);
        $this->put('/pharmacist/inventory/'.$stock->id, ['quantity' => 0, 'price' => 1300])->assertRedirect();
        $this->assertDatabaseHas('pharmacy_medicine', ['id' => $stock->id, 'quantity' => 0, 'price' => 1300, 'stock_status' => 'out_of_stock']);
    }

    public function test_saved_medicine_is_searchable_with_current_stock_and_price(): void
    {
        $this->withSession(['user_id' => 1])->from('/pharmacist/medicines')->post('/pharmacist/add', [
            'medicine_name' => '  Amoxicillin   500mg  ', 'quantity' => 30, 'price' => 1200,
        ])->assertRedirect('/pharmacist/medicines');
        $medicine = DB::table('medicines')->where('name', 'Amoxicillin 500mg')->first();
        $this->assertNotNull($medicine);
        $stock = DB::table('pharmacy_medicine')->where('medicine_id', $medicine->id)->first();
        $this->assertDatabaseHas('pharmacy_medicine', ['id' => $stock->id, 'pharmacy_id' => 1, 'quantity' => 30, 'price' => 1200]);

        $this->withSession(['user_id' => 3])->get('/?'.http_build_query(['search' => 'AMOXICILLIN   500mg']))
            ->assertOk()->assertSee('data-medicine="Amoxicillin 500mg"', false)
            ->assertSee('Pharmacy 1')->assertSee('data-price="1,200 UGX"', false)
            ->assertSee('data-quantity="30"', false)->assertSee('/reserve/'.$stock->id);

        $this->withSession(['user_id' => 1])->put('/pharmacist/inventory/'.$stock->id, ['quantity' => 0, 'price' => 1500])->assertRedirect();
        $this->withSession(['user_id' => 3])->get('/?search=amoxicillin')->assertOk()
            ->assertSee('data-price="1,500 UGX"', false)->assertSee('data-quantity="0"', false)->assertSee('Out of Stock');
        $this->withSession(['user_id' => 1])->delete('/pharmacist/inventory/'.$stock->id)->assertRedirect();
        $this->withSession(['user_id' => 3])->get('/?search=amoxicillin')->assertOk()
            ->assertDontSee('data-medicine="Amoxicillin 500mg"', false)->assertSee('No medicines matching');
    }

    public function test_search_accepts_legacy_tags_and_multiple_terms_but_hides_unapproved_pharmacies(): void
    {
        $this->withSession(['user_id' => 1])->post('/pharmacist/add', ['medicine_name' => 'Ibuprofen', 'quantity' => 20, 'price' => 800])->assertRedirect();
        foreach ([['search' => 'paracetamol, ibuprofen'], ['search' => '', 'item_names' => ['PARACETAMOL', 'Ibuprofen']]] as $query) {
            $this->withSession(['user_id' => 3])->get('/?'.http_build_query($query))->assertOk()
                ->assertSee('data-medicine="Paracetamol"', false)->assertSee('data-medicine="Ibuprofen"', false);
        }
        DB::table('pharmacies')->where('id', 1)->update(['status' => 'pending']);
        $this->get('/?search=ibuprofen')->assertOk()->assertDontSee('data-medicine="Ibuprofen"', false);
        $this->get('/?search=')->assertOk()->assertDontSee('No medicines matching');
    }

    public function test_guests_patients_and_unapproved_pharmacies_are_restricted(): void
    {
        $this->get('/pharmacist/requests')->assertRedirect('/login');
        $this->withSession(['user_id' => 3])->get('/pharmacist/inventory')->assertForbidden();
        $this->post('/pharmacist/add', [])->assertForbidden();
        DB::table('pharmacies')->where('id', 1)->update(['status' => 'pending']);
        $this->withSession(['user_id' => 1])->delete('/pharmacist/inventory/1')->assertForbidden();
        $this->post('/pharmacist/requests/1/confirm')->assertForbidden();
    }

    public function test_profile_and_settings_are_saved_for_current_owner(): void
    {
        $this->withSession(['user_id' => 1])->post('/pharmacist/profile', ['name' => 'New Pharmacy', 'location' => 'Entebbe', 'phone_number' => '0700000001'])->assertRedirect();
        $this->assertDatabaseHas('pharmacies', ['id' => 1, 'name' => 'New Pharmacy']);
        $data = ['name' => 'New Name', 'email' => 'new@example.com', 'current_password' => 'wrong'];
        $this->post('/pharmacist/settings', $data)->assertSessionHasErrors('current_password');
        $data['current_password'] = 'password123';
        $this->post('/pharmacist/settings', $data)->assertRedirect();
        $this->assertDatabaseHas('users', ['id' => 1, 'email' => 'new@example.com']);
    }

    public function test_action_messages_display_once_as_auto_dismissing_toasts(): void
    {
        $this->withSession(['user_id' => 1])->from('/pharmacist/profile')->post('/pharmacist/profile', [
            'name' => 'Updated Pharmacy', 'location' => 'Kampala', 'phone_number' => '0700000001',
        ])->assertRedirect('/pharmacist/profile')->assertSessionHas('alerts');
        $this->get('/pharmacist/profile')->assertOk()->assertSee('Pharmacy profile updated.')
            ->assertSee('data-bs-delay="5000"', false)->assertSee('class="toast flash-toast"', false)->assertSessionMissing('alerts');
        $this->get('/pharmacist/profile')->assertOk()->assertDontSee('Pharmacy profile updated.');
    }

    public function test_admin_overview_uses_database_counts_and_calendar_chart_data(): void
    {
        $this->travelTo(\Illuminate\Support\Carbon::parse('2026-09-09 12:00:00'));
        DB::table('reservations')->where('id', 1)->update(['created_at' => now()]);
        DB::table('users')->where('id', 3)->update(['created_at' => now()->subDay()]);
        DB::table('pharmacies')->where('id', 1)->update(['created_at' => now()->subMonth()]);
        $response = $this->withSession(['user_id' => 4])->get('/admin')->assertOk()
            ->assertSee('System Overview')->assertSee('Recent Users')->assertSee('Recent Activity')
            ->assertSee('adminSidebarMenu')->assertSee('css/admin-dashboard.css');
        $this->assertSame(['users' => 4, 'pharmacies' => 2, 'medicines' => 1, 'reservations' => 1], $response->viewData('stats'));
        $chart = $response->viewData('chartData');
        $this->assertCount(7, $chart['week']['labels']);
        $this->assertCount(30, $chart['month']['labels']);
        $this->assertCount(12, $chart['year']['labels']);
        $this->assertSame([0, 0, 1, 0, 0, 0, 0], $chart['week']['series']['reservations']);
        $this->assertSame(1, $chart['week']['series']['users'][1]);
        $this->assertSame(0, array_sum($chart['month']['series']['pharmacies']));
        $this->assertSame(1, $chart['year']['series']['pharmacies'][7]);
        $response->assertSeeInOrder(['href="/admin/view/pharmacies"', 'href="/admin/subscriptions"', 'href="/admin/view/medicines"'], false);
        foreach (['reports', 'activity', 'settings', 'view/users', 'view/pharmacies', 'view/medicines', 'view/reservations', 'subscriptions'] as $path) {
            $this->get('/admin/'.$path)->assertOk();
        }
        $csv = $this->get('/admin/reports/export')->assertOk()->assertDownload('medfinder-report-2026.csv')->streamedContent();
        $this->assertStringContainsString('"September 2026",1,0,1', $csv);
        $this->travelBack();
    }

    public function test_admin_redesign_is_isolated_and_routes_require_admin(): void
    {
        foreach ([1 => '/pharmacist', 3 => '/requests'] as $user => $dashboard) {
            $this->withSession(['user_id' => $user])->get($dashboard)->assertOk()
                ->assertDontSee('css/admin-dashboard.css')->assertDontSee('js/admin-dashboard.js')->assertDontSee('id="adminSidebarMenu"', false);
            foreach (['', '/reports', '/settings', '/reports/export'] as $path) $this->get('/admin'.$path)->assertForbidden();
            $this->post('/admin/settings', [])->assertForbidden();
        }
    }

    public function test_admin_can_deactivate_and_reactivate_accounts_without_deleting_records(): void
    {
        foreach ([3 => '/requests', 1 => '/pharmacist'] as $id => $dashboard) {
            $this->withSession(['user_id' => 4, 'user_session_version' => 0])->post('/admin/users/'.$id.'/status', [
                'is_active' => 0, 'reason' => 'Account review requested', 'confirmed' => 1,
            ])->assertRedirect('/admin/view/users');
            $this->assertDatabaseHas('users', ['id' => $id, 'is_active' => false, 'session_version' => 1]);
            $this->assertDatabaseHas('account_status_events', ['user_id' => $id, 'admin_id' => 4, 'is_active' => false, 'reason' => 'Account review requested']);
            $this->get('/admin/view/users')->assertOk()->assertSee('Deactivated')->assertSee('Reactivate');
            $this->withSession(['user_id' => $id, 'user_session_version' => 0])->get($dashboard)->assertRedirect('/login')->assertSessionMissing('user_id');
            $this->post('/login', ['email' => $id.'@example.com', 'password' => 'password123'])->assertSessionHasErrors('email')->assertSessionMissing('user_id');
            $this->withSession(['user_id' => 4, 'user_session_version' => 0])->post('/admin/users/'.$id.'/status', [
                'is_active' => 1, 'reason' => 'Account review completed', 'confirmed' => 1,
            ])->assertRedirect('/admin/view/users');
            // Old sessions must not regain access after reactivation.
            $this->withSession(['user_id' => $id, 'user_session_version' => 0])->get('/login')->assertOk()->assertSessionMissing('user_id');
            $this->post('/login', ['email' => $id.'@example.com', 'password' => 'password123'])->assertRedirect($dashboard)->assertSessionHas('user_session_version', 2);
            $this->get($dashboard)->assertOk()->assertDontSee('Confirm deactivation');
        }
        $this->assertDatabaseCount('users', 4);
        $this->assertDatabaseCount('reservations', 1);
        $this->assertDatabaseCount('pharmacy_medicine', 1);
        $this->assertDatabaseCount('account_status_events', 4);
    }

    public function test_account_status_changes_require_admin_confirmation_and_reason(): void
    {
        $data = ['is_active' => 0, 'reason' => 'Account review requested', 'confirmed' => 1];
        $this->post('/admin/users/3/status', $data)->assertForbidden();
        foreach ([1, 3] as $id) $this->withSession(['user_id' => $id])->post('/admin/users/2/status', $data)->assertForbidden();
        $this->withSession(['user_id' => 4])->post('/admin/users/4/status', $data)->assertForbidden();
        $this->post('/admin/users/999/status', $data)->assertNotFound();
        $this->post('/admin/users/3/status', ['is_active' => 0])->assertSessionHasErrors(['reason', 'confirmed']);
        $this->assertDatabaseHas('users', ['id' => 3, 'is_active' => true]);
        $this->post('/admin/users/3/status', $data)->assertRedirect();
        $this->post('/admin/users/3/status', $data)->assertRedirect();
        $this->assertDatabaseCount('account_status_events', 1);
        $this->assertDatabaseHas('users', ['id' => 3, 'session_version' => 1]);
        $this->get('/admin/users/2/status')->assertStatus(405);
    }

    public function test_admin_settings_require_current_password_and_preserve_role(): void
    {
        $data = ['name' => 'Updated Admin', 'email' => ' ADMIN.NEW@example.com ', 'current_password' => 'wrong', 'role' => 'patient'];
        $this->withSession(['user_id' => 4])->from('/admin/settings')->post('/admin/settings', $data)->assertSessionHasErrors('current_password');
        $this->assertDatabaseMissing('users', ['name' => 'Updated Admin']);
        $data['current_password'] = 'password123';
        $this->post('/admin/settings', $data)->assertRedirect('/admin/settings')->assertSessionHasNoErrors();
        $this->assertDatabaseHas('users', ['id' => 4, 'name' => 'Updated Admin', 'email' => 'admin.new@example.com', 'role' => 'admin']);
        $this->assertDatabaseHas('users', ['id' => 3, 'role' => 'patient']);
    }

    public function test_subscription_requires_admin_verification_and_extends_renewals(): void
    {
        $this->withSession(['user_id' => 1])->from('/pharmacist/subscription')->post('/pharmacist/subscription', ['reference' => 'tx-123', 'phone' => '0700000000', 'amount' => 1])->assertRedirect();
        $this->assertDatabaseHas('subscription_payments', ['reference' => 'TX-123', 'amount' => 50000, 'status' => 'pending', 'expires_at' => null]);
        $this->get('/pharmacist/subscription')->assertOk()->assertSee('Awaiting verification');
        $this->post('/pharmacist/subscription', ['reference' => 'tx-456', 'phone' => '0700000000'])->assertSessionHasErrors('reference');
        $this->post('/admin/subscriptions/1/approve')->assertForbidden();
        $this->withSession(['user_id' => 4])->get('/admin/subscriptions')->assertOk()->assertSee('TX-123');
        $this->post('/admin/subscriptions/1/approve')->assertRedirect();
        $first = DB::table('subscription_payments')->where('id', 1)->first();
        $this->assertSame('verified', $first->status);
        $this->assertNotNull($first->expires_at);
        $this->post('/admin/subscriptions/1/approve')->assertSessionHasErrors('payment');
        $this->withSession(['user_id' => 1])->get('/pharmacist/subscription')->assertOk()->assertSee('Paid through');
        $this->post('/pharmacist/subscription', ['reference' => 'TX-123', 'phone' => '0700000000'])->assertSessionHasErrors('reference');
        $this->post('/pharmacist/subscription', ['reference' => 'TX-456', 'phone' => '0700000000'])->assertRedirect();
        $this->withSession(['user_id' => 4])->post('/admin/subscriptions/2/approve')->assertRedirect();
        $second = DB::table('subscription_payments')->where('id', 2)->first();
        $this->assertSame($first->expires_at, $second->starts_at);
        $this->assertGreaterThan($first->expires_at, $second->expires_at);
        $this->withSession(['user_id' => 2])->get('/pharmacist/subscription')->assertOk()->assertDontSee('TX-123');
    }
}
