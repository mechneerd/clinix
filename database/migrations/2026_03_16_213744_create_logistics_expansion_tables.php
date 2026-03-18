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
        // Stock Movements (Audit trail)
        Schema::create('stock_movements', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('stockable_id');
            $table->string('stockable_type')->comment('Medicine or LabConsumable');
            $table->enum('type', ['purchase', 'dispense', 'adjustment', 'return', 'transfer']);
            $table->decimal('quantity', 10, 2);
            $table->string('reference_id')->nullable()->comment('Invoice or Appt ID');
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        // Clinic Expenses
        Schema::create('clinic_expenses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('clinic_id')->constrained()->onDelete('cascade');
            $table->string('category')->comment('Rent, Salaries, Supplies, etc.');
            $table->decimal('amount', 12, 2);
            $table->string('description');
            $table->date('expense_date');
            $table->foreignId('recorded_by')->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('clinic_expenses');
        Schema::dropIfExists('stock_movements');
    }
};
