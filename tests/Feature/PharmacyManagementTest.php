<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Medication;
use App\Models\Sale;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PharmacyManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_landing_page_can_be_rendered(): void
    {
        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertSee('PharmaGestion');
    }

    public function test_login_screen_can_be_rendered(): void
    {
        $response = $this->get('/login');
        $response->assertStatus(200);
        $response->assertSee('Connectez-vous');
    }

    public function test_vendor_login_redirects_to_vendor_dashboard(): void
    {
        $this->seed();

        $response = $this->post('/login', [
            'email' => 'aminata.sow@pharmacie.sn',
            'password' => 'password123',
        ]);

        $response->assertRedirect('/vendor/dashboard');
    }

    public function test_inactive_vendor_cannot_login(): void
    {
        $this->seed();

        $response = $this->post('/login', [
            'email' => 'fatou.ndiaye@pharmacie.sn',
            'password' => 'password123',
        ]);

        $response->assertSessionHasErrors(['email']);
        $this->assertGuest();
    }

    public function test_vendor_can_access_vendor_dashboard(): void
    {
        $this->seed();
        $vendor = User::where('email', 'aminata.sow@pharmacie.sn')->first();

        $response = $this->actingAs($vendor)->get('/vendor/dashboard');
        $response->assertStatus(200);
        $response->assertSee('Aminata Sow');
        $response->assertSee('Ventes du jour');
        $response->assertSee('Guichet');
    }

    public function test_vendor_can_access_pos_guichet(): void
    {
        $this->seed();
        $vendor = User::where('email', 'aminata.sow@pharmacie.sn')->first();

        $response = $this->actingAs($vendor)->get('/sales/pos');
        $response->assertStatus(200);
        $response->assertSee('Guichet de Vente');
        $response->assertSee('Panier de Vente');
        $response->assertSee('Paracétamol');
    }

    public function test_vendor_can_complete_sale_at_pos(): void
    {
        $this->seed();
        $vendor = User::where('email', 'aminata.sow@pharmacie.sn')->first();
        $med = Medication::where('code', 'PAR-001')->first();
        $initialStock = $med->stock_quantity;

        $response = $this->actingAs($vendor)->postJson('/sales/pos', [
            'patient_name' => 'Fatou Diop',
            'payment_method' => 'espèces',
            'paid_amount' => 2000,
            'items' => [
                [
                    'medication_id' => $med->id,
                    'quantity' => 2,
                ]
            ]
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'total_amount' => 1000,
            'change_amount' => 1000,
        ]);

        // Vérification de la décrémentation du stock
        $this->assertEquals($initialStock - 2, $med->fresh()->stock_quantity);

        // Vérification de la création de la vente et des items
        $this->assertDatabaseHas('sales', [
            'user_id' => $vendor->id,
            'patient_name' => 'Fatou Diop',
            'total_amount' => 1000,
            'payment_method' => 'espèces',
        ]);

        $this->assertDatabaseHas('stock_movements', [
            'medication_id' => $med->id,
            'type' => 'sortie',
            'quantity' => 2,
            'user_id' => $vendor->id,
        ]);
    }

    public function test_admin_can_create_vendor_account(): void
    {
        $this->seed();
        $admin = User::where('email', 'dr.diallo@pharmacie.sn')->first();

        $response = $this->actingAs($admin)->post('/admin/vendors', [
            'name' => 'Ndeye Fall',
            'email' => 'ndeye.fall@pharmacie.sn',
            'phone' => '+221 77 555 44 33',
            'password' => 'secret123',
        ]);

        $response->assertRedirect('/admin/vendors');
        $this->assertDatabaseHas('users', [
            'email' => 'ndeye.fall@pharmacie.sn',
            'role' => 'vendor',
            'status' => 'active',
        ]);
    }

    public function test_vendor_cannot_access_admin_settings_or_inventory(): void
    {
        $this->seed();
        $vendor = User::where('email', 'aminata.sow@pharmacie.sn')->first();

        // Tentative d'accès à la gestion des utilisateurs / admins -> redirigé vers dashboard vendeuse
        $responseUsers = $this->actingAs($vendor)->get('/users');
        $responseUsers->assertRedirect('/vendor/dashboard');

        // Tentative d'accès aux paramètres
        $responseSettings = $this->actingAs($vendor)->get('/settings');
        $responseSettings->assertRedirect('/vendor/dashboard');

        // Tentative d'accès aux entrées fournisseurs
        $responseEntries = $this->actingAs($vendor)->get('/entries');
        $responseEntries->assertRedirect('/vendor/dashboard');
    }

    public function test_admin_cannot_access_pos_guichet(): void
    {
        $this->seed();
        $admin = User::where('email', 'dr.diallo@pharmacie.sn')->first();

        // L'admin tente d'accéder au guichet -> redirigé vers le dashboard admin
        $response = $this->actingAs($admin)->get('/sales/pos');
        $response->assertRedirect('/dashboard');
        $response->assertSessionHas('error');
    }

    public function test_admin_can_access_vendor_management(): void
    {
        $this->seed();
        $admin = User::where('email', 'dr.diallo@pharmacie.sn')->first();

        $response = $this->actingAs($admin)->get('/admin/vendors');
        $response->assertStatus(200);
        $response->assertSee('Comptes Vendeuses');
        $response->assertSee('Aminata Sow');
    }

    public function test_pdf_generation_routes(): void
    {
        $this->seed();
        $user = User::where('email', 'dr.diallo@pharmacie.sn')->first();

        // Test Stock Report PDF
        $response = $this->actingAs($user)->get('/reports/pdf');
        $response->assertStatus(200);
        $response->assertHeader('content-type', 'application/pdf');

        // Test Inventory Sheet PDF
        $response = $this->actingAs($user)->get('/inventories/pdf');
        $response->assertStatus(200);
        $response->assertHeader('content-type', 'application/pdf');
    }

    public function test_manager_can_create_new_medication_with_initial_stock_movement(): void
    {
        $this->seed();
        $admin = User::where('email', 'dr.diallo@pharmacie.sn')->first();

        $response = $this->actingAs($admin)->post('/medications', [
            'code' => 'CIP-500',
            'name' => 'Ciprofloxacine',
            'dosage' => '500mg',
            'form' => 'Comprimés',
            'new_category' => 'Antibiotiques',
            'stock_quantity' => 50,
            'min_threshold' => 10,
            'unit_price' => 1500,
            'expiration_date' => '2027-12-31',
        ]);

        $response->assertRedirect('/medications');
        $this->assertDatabaseHas('medications', [
            'code' => 'CIP-500',
            'name' => 'Ciprofloxacine',
            'stock_quantity' => 50,
            'unit_price' => 1500,
        ]);

        $this->assertDatabaseHas('stock_movements', [
            'type' => 'entrée',
            'quantity' => 50,
            'user_id' => $admin->id,
        ]);
    }

    public function test_manager_can_filter_reports_by_week_and_month(): void
    {
        $this->seed();
        $admin = User::where('email', 'dr.diallo@pharmacie.sn')->first();

        // Rapport filtré par semaine
        $responseWeek = $this->actingAs($admin)->get('/reports?period=week');
        $responseWeek->assertStatus(200);
        $responseWeek->assertSee('Cette Semaine');

        // Rapport filtré par mois
        $responseMonth = $this->actingAs($admin)->get('/reports?period=month');
        $responseMonth->assertStatus(200);
        $responseMonth->assertSee('Ce Mois-ci');

        // Export PDF avec filtre
        $responsePdf = $this->actingAs($admin)->get('/reports/pdf?period=week');
        $responsePdf->assertStatus(200);
        $responsePdf->assertHeader('content-type', 'application/pdf');
    }
}
