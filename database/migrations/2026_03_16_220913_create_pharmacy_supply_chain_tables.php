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
        // 1. Suppliers
        Schema::create('suppliers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('clinic_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->string('contact_person')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->text('address')->nullable();
            $table->string('tax_number')->nullable();
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();
        });

        // 2. Manufacturers
        Schema::create('manufacturers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('details')->nullable();
            $table->string('country')->nullable();
            $table->timestamps();
        });

        // 3. Medicine Categories
        Schema::create('medicine_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->timestamps();
        });

        // 4. Medicine Brands
        Schema::create('medicine_brands', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->foreignId('manufacturer_id')->nullable()->constrained()->onDelete('set null');
            $table->timestamps();
        });

        // 5. Medicine Batches (Crucial for expiry)
        Schema::create('medicine_batches', function (Blueprint $table) {
            $table->id();
            $table->foreignId('medicine_id')->constrained()->onDelete('cascade');
            $table->string('batch_number');
            $table->date('expiry_date');
            $table->decimal('cost_price', 15, 2)->default(0);
            $table->decimal('selling_price', 15, 2)->default(0);
            $table->integer('initial_quantity');
            $table->integer('current_quantity');
            $table->foreignId('supplier_id')->nullable()->constrained()->onDelete('set null');
            $table->timestamps();
        });

        // 6. Purchase Orders
        Schema::create('purchase_orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('clinic_id')->constrained()->onDelete('cascade');
            $table->foreignId('supplier_id')->constrained()->onDelete('cascade');
            $table->string('po_number')->unique();
            $table->date('order_date');
            $table->date('expected_delivery_date')->nullable();
            $table->decimal('total_amount', 15, 2)->default(0);
            $table->enum('status', ['pending', 'ordered', 'received', 'cancelled'])->default('pending');
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        // 7. Purchase Order Items
        Schema::create('purchase_order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('purchase_order_id')->constrained()->onDelete('cascade');
            $table->foreignId('medicine_id')->constrained()->onDelete('cascade');
            $table->integer('quantity');
            $table->decimal('unit_price', 15, 2);
            $table->decimal('subtotal', 15, 2);
            $table->timestamps();
        });

        // 8. Stock Adjustments
        Schema::create('stock_adjustments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('medicine_id')->constrained()->onDelete('cascade');
            $table->integer('quantity_adjusted'); // Pos or Neg
            $table->enum('reason', ['damage', 'expiry', 'loss', 'correction', 'other']);
            $table->text('notes')->nullable();
            $table->foreignId('performed_by')->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stock_adjustments');
        Schema::dropIfExists('purchase_order_items');
        Schema::dropIfExists('purchase_orders');
        Schema::dropIfExists('medicine_batches');
        Schema::dropIfExists('medicine_brands');
        Schema::dropIfExists('medicine_categories');
        Schema::dropIfExists('manufacturers');
        Schema::dropIfExists('suppliers');
    }
};
