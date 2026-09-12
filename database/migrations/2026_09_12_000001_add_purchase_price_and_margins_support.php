<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Enrichir la table medications
        Schema::table('medications', function (Blueprint $table) {
            if (!Schema::hasColumn('medications', 'packaging_unit')) {
                $table->string('packaging_unit', 50)->nullable()->after('form');
            }
            if (!Schema::hasColumn('medications', 'purchase_price')) {
                $table->decimal('purchase_price', 10, 2)->default(0)->after('unit_price');
            }
        });

        // Enrichir la table stock_movements
        Schema::table('stock_movements', function (Blueprint $table) {
            if (!Schema::hasColumn('stock_movements', 'reference_no')) {
                $table->string('reference_no', 100)->nullable()->after('medication_id');
            }
            if (!Schema::hasColumn('stock_movements', 'movement_date')) {
                $table->date('movement_date')->nullable()->after('reference_no');
            }
            if (!Schema::hasColumn('stock_movements', 'supplier')) {
                $table->string('supplier', 255)->nullable()->after('movement_date');
            }
            if (!Schema::hasColumn('stock_movements', 'packaging_unit')) {
                $table->string('packaging_unit', 50)->nullable()->after('supplier');
            }
            if (!Schema::hasColumn('stock_movements', 'purchase_price')) {
                $table->decimal('purchase_price', 10, 2)->nullable()->after('quantity');
            }
            if (!Schema::hasColumn('stock_movements', 'selling_price')) {
                $table->decimal('selling_price', 10, 2)->nullable()->after('purchase_price');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('stock_movements', function (Blueprint $table) {
            $table->dropColumn([
                'reference_no',
                'movement_date',
                'supplier',
                'packaging_unit',
                'purchase_price',
                'selling_price',
            ]);
        });

        Schema::table('medications', function (Blueprint $table) {
            $table->dropColumn([
                'packaging_unit',
                'purchase_price',
            ]);
        });
    }
};
