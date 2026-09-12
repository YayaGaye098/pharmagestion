<?php

namespace Database\Seeders;

use App\Models\CashRegister;
use App\Models\Category;
use App\Models\Medication;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\StockMovement;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class PharmacySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Admin
        $admin = User::firstOrCreate(
            ['email' => 'dr.diallo@pharmacie.sn'],
            [
                'name' => 'Dr. Aissatou Diallo',
                'phone' => '+221 77 123 45 67',
                'role' => 'admin',
                'status' => 'active',
                'password' => Hash::make('password123'),
            ]
        );

        // 2. Vendeuse Aminata Sow
        $v1 = User::firstOrCreate(
            ['email' => 'aminata.sow@pharmacie.sn'],
            [
                'name' => 'Aminata Sow',
                'phone' => '+221 77 987 65 43',
                'role' => 'vendor',
                'status' => 'active',
                'password' => Hash::make('password123'),
            ]
        );

        // 3. Vendeur Moussa Diouf
        $v2 = User::firstOrCreate(
            ['email' => 'moussa.diouf@pharmacie.sn'],
            [
                'name' => 'Moussa Diouf',
                'phone' => '+221 78 444 33 22',
                'role' => 'vendor',
                'status' => 'active',
                'password' => Hash::make('password123'),
            ]
        );

        // 4. Stagiaire Fatou Ndiaye (Inactive)
        $v3 = User::firstOrCreate(
            ['email' => 'fatou.ndiaye@pharmacie.sn'],
            [
                'name' => 'Fatou Ndiaye',
                'phone' => '+221 76 111 22 33',
                'role' => 'vendor',
                'status' => 'inactive',
                'password' => Hash::make('password123'),
            ]
        );

        // Categories
        $antalgiques = Category::firstOrCreate(['name' => 'Antalgiques'], ['description' => 'Médicaments contre la douleur']);
        $antibiotiques = Category::firstOrCreate(['name' => 'Antibiotiques'], ['description' => 'Traitement des infections bactériennes']);
        $antipaludiques = Category::firstOrCreate(['name' => 'Antipaludiques'], ['description' => 'Traitements du paludisme']);
        $ains = Category::firstOrCreate(['name' => 'AINS'], ['description' => 'Anti-inflammatoires non stéroïdiens']);
        $vitamines = Category::firstOrCreate(['name' => 'Vitamines'], ['description' => 'Compléments et vitamines']);

        // Medications
        $m1 = Medication::firstOrCreate(
            ['code' => 'PAR-001'],
            [
                'name' => 'Paracétamol',
                'dosage' => '500mg',
                'form' => 'Comprimés',
                'packaging_unit' => 'B/100',
                'category_id' => $antalgiques->id,
                'stock_quantity' => 124,
                'min_threshold' => 20,
                'purchase_price' => 350,
                'unit_price' => 500,
                'expiration_date' => '2026-11-30',
                'status' => 'ok',
            ]
        );

        $m2 = Medication::firstOrCreate(
            ['code' => 'AMO-012'],
            [
                'name' => 'Amoxicilline',
                'dosage' => '1g',
                'form' => 'Gélules',
                'packaging_unit' => 'B/50',
                'category_id' => $antibiotiques->id,
                'stock_quantity' => 45,
                'min_threshold' => 15,
                'purchase_price' => 1100,
                'unit_price' => 1500,
                'expiration_date' => '2026-03-15',
                'status' => 'ok',
            ]
        );

        $m3 = Medication::firstOrCreate(
            ['code' => 'IBU-044'],
            [
                'name' => 'Ibuprofène',
                'dosage' => '200mg/5ml',
                'form' => 'Sirop',
                'packaging_unit' => 'FL/100',
                'category_id' => $ains->id,
                'stock_quantity' => 5,
                'min_threshold' => 10,
                'purchase_price' => 900,
                'unit_price' => 1200,
                'expiration_date' => '2026-08-01',
                'status' => 'faible',
            ]
        );

        $m4 = Medication::firstOrCreate(
            ['code' => 'VIT-010'],
            [
                'name' => 'Vitamine C',
                'dosage' => '1000mg',
                'form' => 'Effervescent',
                'packaging_unit' => 'T/30',
                'category_id' => $vitamines->id,
                'stock_quantity' => 88,
                'min_threshold' => 20,
                'purchase_price' => 1400,
                'unit_price' => 2000,
                'expiration_date' => '2026-12-31',
                'status' => 'ok',
            ]
        );

        // Médicaments du Bon de Commande Officiel District THIES (Poste de Santé Diakhao)
        $solutes = Category::firstOrCreate(['name' => 'Solutés & Perfusions'], ['description' => 'Solutés et poches de perfusion']);
        $injectables = Category::firstOrCreate(['name' => 'Injectables'], ['description' => 'Médicaments sous forme injectable']);

        $m5 = Medication::firstOrCreate(
            ['code' => '400340'],
            [
                'name' => 'Sodium Chlorure 0.9% Sol Perf',
                'dosage' => '0.9%',
                'form' => 'Flacon',
                'packaging_unit' => 'FL/500',
                'category_id' => $solutes->id,
                'stock_quantity' => 150,
                'min_threshold' => 25,
                'purchase_price' => 690,
                'unit_price' => 850,
                'expiration_date' => '2027-06-30',
                'status' => 'ok',
            ]
        );

        $m6 = Medication::firstOrCreate(
            ['code' => '120340'],
            [
                'name' => 'Paracétamol Injectable',
                'dosage' => '1g/100ml',
                'form' => 'Injectable',
                'packaging_unit' => 'FL/100',
                'category_id' => $injectables->id,
                'stock_quantity' => 100,
                'min_threshold' => 15,
                'purchase_price' => 920,
                'unit_price' => 1200,
                'expiration_date' => '2027-04-30',
                'status' => 'ok',
            ]
        );

        $m7 = Medication::firstOrCreate(
            ['code' => '010512'],
            [
                'name' => 'Amoxicilline Cp. Sécable',
                'dosage' => '1g',
                'form' => 'Comprimés',
                'packaging_unit' => 'B/100',
                'category_id' => $antibiotiques->id,
                'stock_quantity' => 15,
                'min_threshold' => 5,
                'purchase_price' => 7590,
                'unit_price' => 9000,
                'expiration_date' => '2026-12-31',
                'status' => 'ok',
            ]
        );

        $m8 = Medication::firstOrCreate(
            ['code' => '010611'],
            [
                'name' => 'Amoxicilline + Ac. Clavulanique',
                'dosage' => '500+62.5mg',
                'form' => 'Comprimés',
                'packaging_unit' => 'B/50',
                'category_id' => $antibiotiques->id,
                'stock_quantity' => 50,
                'min_threshold' => 10,
                'purchase_price' => 3910,
                'unit_price' => 4800,
                'expiration_date' => '2027-01-31',
                'status' => 'ok',
            ]
        );

        // Mouvements d'Entrées Récentes (Bordereau District THIES)
        StockMovement::firstOrCreate(
            ['reference_no' => 'TH08J2609CC00021', 'medication_id' => $m5->id],
            [
                'type' => 'entrée',
                'quantity' => 150,
                'movement_date' => '2026-09-08',
                'supplier' => 'District THIES',
                'packaging_unit' => 'FL/500',
                'purchase_price' => 690,
                'selling_price' => 850,
                'user_id' => $admin->id,
                'performed_by_name' => $admin->name,
                'notes' => 'Livraison District THIES - PS DIAKHAO (Bordereau N° TH08J2609CC00021)',
            ]
        );

        StockMovement::firstOrCreate(
            ['reference_no' => 'TH08J2609CC00021', 'medication_id' => $m6->id],
            [
                'type' => 'entrée',
                'quantity' => 100,
                'movement_date' => '2026-09-08',
                'supplier' => 'District THIES',
                'packaging_unit' => 'FL/100',
                'purchase_price' => 920,
                'selling_price' => 1200,
                'user_id' => $admin->id,
                'performed_by_name' => $admin->name,
                'notes' => 'Livraison District THIES - PS DIAKHAO (Bordereau N° TH08J2609CC00021)',
            ]
        );

        StockMovement::firstOrCreate(
            ['reference_no' => 'TH08J2609CC00021', 'medication_id' => $m7->id],
            [
                'type' => 'entrée',
                'quantity' => 15,
                'movement_date' => '2026-09-08',
                'supplier' => 'District THIES',
                'packaging_unit' => 'B/100',
                'purchase_price' => 7590,
                'selling_price' => 9000,
                'user_id' => $admin->id,
                'performed_by_name' => $admin->name,
                'notes' => 'Livraison District THIES - PS DIAKHAO (Bordereau N° TH08J2609CC00021)',
            ]
        );

        StockMovement::firstOrCreate(
            ['reference_no' => 'TH08J2609CC00021', 'medication_id' => $m8->id],
            [
                'type' => 'entrée',
                'quantity' => 50,
                'movement_date' => '2026-09-08',
                'supplier' => 'District THIES',
                'packaging_unit' => 'B/50',
                'purchase_price' => 3910,
                'selling_price' => 4800,
                'user_id' => $admin->id,
                'performed_by_name' => $admin->name,
                'notes' => 'Livraison District THIES - PS DIAKHAO (Bordereau N° TH08J2609CC00021)',
            ]
        );

        // Sales
        $s1 = Sale::firstOrCreate(
            ['reference' => 'V-1042'],
            [
                'total_amount' => 12500,
                'payment_method' => 'espèces',
                'paid_amount' => 15000,
                'change_amount' => 2500,
                'user_id' => $v1->id,
                'patient_name' => 'Client Comptoir',
                'status' => 'paid',
                'created_at' => now()->subMinutes(15),
            ]
        );

        $s2 = Sale::firstOrCreate(
            ['reference' => 'V-1041'],
            [
                'total_amount' => 4000,
                'payment_method' => 'wave',
                'paid_amount' => 4000,
                'change_amount' => 0,
                'user_id' => $v1->id,
                'patient_name' => 'Client Comptoir',
                'status' => 'paid',
                'created_at' => now()->subHours(1)->subMinutes(48),
            ]
        );

        $s3 = Sale::firstOrCreate(
            ['reference' => 'V-1040'],
            [
                'total_amount' => 35000,
                'payment_method' => 'espèces',
                'paid_amount' => 35000,
                'change_amount' => 0,
                'user_id' => $v1->id,
                'patient_name' => 'Client Comptoir',
                'status' => 'cancelled',
                'created_at' => now()->subHours(2)->subMinutes(30),
            ]
        );

        $s4 = Sale::firstOrCreate(
            ['reference' => 'V-1039'],
            [
                'total_amount' => 1500,
                'payment_method' => 'orange_money',
                'paid_amount' => 1500,
                'change_amount' => 0,
                'user_id' => $v1->id,
                'patient_name' => 'Client Comptoir',
                'status' => 'paid',
                'created_at' => now()->subHours(2)->subMinutes(45),
            ]
        );

        // Sale items for demo sales
        if ($s1->wasRecentlyCreated || $s1->items()->count() === 0) {
            SaleItem::firstOrCreate([
                'sale_id' => $s1->id,
                'medication_id' => $m1->id,
            ], [
                'quantity' => 5,
                'unit_price' => 500,
                'subtotal' => 2500,
            ]);
            SaleItem::firstOrCreate([
                'sale_id' => $s1->id,
                'medication_id' => $m4->id,
            ], [
                'quantity' => 5,
                'unit_price' => 2000,
                'subtotal' => 10000,
            ]);
        }

        // Cash Register session for Aminata Sow
        CashRegister::firstOrCreate(
            ['user_id' => $v1->id, 'status' => 'open'],
            [
                'opened_at' => now()->startOfDay()->addHours(8),
                'opening_float' => 50000,
                'calculated_cash' => 145500,
                'calculated_mobile' => 85000,
                'physical_counted' => 275000,
                'difference' => -5500,
                'justification' => 'Écart constaté lors du rendu de monnaie au comptoir.',
            ]
        );
    }
}
