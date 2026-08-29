<?php

namespace Tests\Feature;

use App\Models\StockMovement;
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

    public function test_vendor_can_access_vendor_dashboard(): void
    {
        $this->seed();
        $vendor = User::where('email', 'aminata.sow@pharmacie.sn')->first();

        $response = $this->actingAs($vendor)->get('/vendor/dashboard');
        $response->assertStatus(200);
        $response->assertSee('Bonjour, Aminata Sow (Vendeuse)');
    }

    public function test_admin_can_access_vendor_management(): void
    {
        $this->seed();
        $admin = User::where('email', 'dr.diallo@pharmacie.sn')->first();

        $response = $this->actingAs($admin)->get('/admin/vendors');
        $response->assertStatus(200);
        $response->assertSee('Gestion des Vendeuses');
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
}
