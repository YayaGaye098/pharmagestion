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
        if (!Schema::hasTable('sales')) {
            Schema::create('sales', function (Blueprint $table) {
                $table->id();
                $table->string('reference')->unique();
                $table->decimal('total_amount', 10, 2);
                $table->string('payment_method')->default('espèces'); // espèces, wave, orange_money, cmu
                $table->decimal('paid_amount', 10, 2)->default(0);
                $table->decimal('change_amount', 10, 2)->default(0);
                $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
                $table->string('patient_name')->nullable();
                $table->string('status')->default('paid'); // paid, cancelled
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('sale_items')) {
            Schema::create('sale_items', function (Blueprint $table) {
                $table->id();
                $table->foreignId('sale_id')->constrained()->cascadeOnDelete();
                $table->foreignId('medication_id')->constrained()->cascadeOnDelete();
                $table->integer('quantity');
                $table->decimal('unit_price', 10, 2);
                $table->decimal('subtotal', 10, 2);
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('cash_registers')) {
            Schema::create('cash_registers', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained()->cascadeOnDelete();
                $table->timestamp('opened_at');
                $table->timestamp('closed_at')->nullable();
                $table->decimal('opening_float', 10, 2)->default(50000);
                $table->decimal('calculated_cash', 10, 2)->default(0);
                $table->decimal('calculated_mobile', 10, 2)->default(0);
                $table->decimal('physical_counted', 10, 2)->nullable();
                $table->decimal('difference', 10, 2)->nullable();
                $table->text('justification')->nullable();
                $table->enum('status', ['open', 'closed'])->default('open');
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('sale_returns')) {
            Schema::create('sale_returns', function (Blueprint $table) {
                $table->id();
                $table->foreignId('sale_id')->constrained()->cascadeOnDelete();
                $table->foreignId('user_id')->constrained()->cascadeOnDelete();
                $table->text('reason');
                $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
                $table->foreignId('approved_by_user_id')->nullable()->constrained('users')->nullOnDelete();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sale_returns');
        Schema::dropIfExists('cash_registers');
        Schema::dropIfExists('sale_items');
        Schema::dropIfExists('sales');
    }
};
