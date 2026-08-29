<?php

use App\Http\Controllers\AlertController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\InventoryAuditController;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\MedicationController;
use App\Http\Controllers\PriceController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\StockController;
use App\Http\Controllers\StockEntryController;
use App\Http\Controllers\StockExitController;
use App\Http\Controllers\TraceabilityController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VendorDashboardController;
use App\Http\Controllers\VendorManagementController;
use Illuminate\Support\Facades\Route;

// Public Landing Page
Route::get('/', [LandingController::class, 'index'])->name('landing');

// Auth Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Authenticated Routes
Route::middleware('auth')->group(function () {
    // ----------------------------------------------------
    // Espace Vendeuse (Dashboard Dédié, Ventes & Rapports)
    // ----------------------------------------------------
    Route::get('/vendor/dashboard', [VendorDashboardController::class, 'index'])->name('vendor.dashboard');
    Route::get('/vendor/sales', [VendorDashboardController::class, 'sales'])->name('vendor.sales');
    Route::get('/vendor/medications', [VendorDashboardController::class, 'medications'])->name('vendor.medications');
    Route::get('/vendor/reports', [VendorDashboardController::class, 'reports'])->name('vendor.reports');

    // ----------------------------------------------------
    // Admin Supervision (Gestion des Vendeuses)
    // ----------------------------------------------------
    Route::get('/admin/vendors', [VendorManagementController::class, 'index'])->name('admin.vendors.index');
    Route::post('/admin/vendors', [VendorManagementController::class, 'store'])->name('admin.vendors.store');
    Route::post('/admin/vendors/{user}/toggle', [VendorManagementController::class, 'toggleStatus'])->name('admin.vendors.toggle');

    // ----------------------------------------------------
    // Espace Administrateur Global
    // ----------------------------------------------------
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Médicaments
    Route::get('/medications', [MedicationController::class, 'index'])->name('medications.index');
    Route::post('/medications', [MedicationController::class, 'store'])->name('medications.store');

    // Stock Détaillé
    Route::get('/stock', [StockController::class, 'index'])->name('stock.index');

    // Entrées de Stock
    Route::get('/entries', [StockEntryController::class, 'index'])->name('entries.index');
    Route::post('/entries', [StockEntryController::class, 'store'])->name('entries.store');

    // Sorties de Stock & Reçus PDF
    Route::get('/exits', [StockExitController::class, 'index'])->name('exits.index');
    Route::post('/exits', [StockExitController::class, 'store'])->name('exits.store');
    Route::get('/exits/{id}/pdf', [StockExitController::class, 'downloadReceipt'])->name('exits.pdf');

    // Inventaires & Fiche de comptage PDF
    Route::get('/inventories', [InventoryAuditController::class, 'index'])->name('inventories.index');
    Route::get('/inventories/pdf', [InventoryAuditController::class, 'downloadSheet'])->name('inventories.pdf');
    Route::post('/inventories/{medication}', [InventoryAuditController::class, 'updateStock'])->name('inventories.update');

    // Traçabilité
    Route::get('/traceability', [TraceabilityController::class, 'index'])->name('traceability.index');

    // Prix
    Route::get('/prices', [PriceController::class, 'index'])->name('prices.index');
    Route::post('/prices/{medication}', [PriceController::class, 'updatePrice'])->name('prices.update');

    // Rapports & Export PDF
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/pdf', [ReportController::class, 'downloadPdf'])->name('reports.pdf');

    // Alertes
    Route::get('/alerts', [AlertController::class, 'index'])->name('alerts.index');

    // Utilisateurs
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::post('/users', [UserController::class, 'store'])->name('users.store');

    // Paramètres
    Route::get('/settings', [SettingController::class, 'index'])->name('settings.index');
    Route::post('/settings', [SettingController::class, 'update'])->name('settings.update');
});
