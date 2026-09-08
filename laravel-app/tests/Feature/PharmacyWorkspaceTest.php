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
            $t->id(); $t->string('name'); $t->string('email')->unique(); $t->string('password'); $t->string('role'); $t->timestamps();
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
