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
        // 1. Lab Profiles (Groups of tests)
        Schema::create('lab_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('clinic_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->string('code')->unique();
            $table->text('description')->nullable();
            $table->decimal('base_price', 15, 2)->default(0);
            $table->timestamps();
        });

        // 2. Lab Normal Ranges (Reference values)
        Schema::create('lab_normal_ranges', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lab_test_id')->constrained()->onDelete('cascade');
            $table->enum('gender', ['male', 'female', 'both'])->default('both');
            $table->integer('min_age_days')->default(0);
            $table->integer('max_age_days')->default(43800); // ~120 years
            $table->decimal('min_value', 15, 3)->nullable();
            $table->decimal('max_value', 15, 3)->nullable();
            $table->string('unit')->nullable();
            $table->text('interpretation_notes')->nullable();
            $table->timestamps();
        });

        // 3. Lab Samples
        Schema::create('lab_samples', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lab_order_id')->constrained()->onDelete('cascade');
            $table->string('sample_barcode')->unique();
            $table->string('sample_type'); // Blood, Urine, Swab, etc.
            $table->dateTime('collected_at');
            $table->foreignId('collected_by')->constrained('users')->onDelete('cascade');
            $table->dateTime('received_at')->nullable();
            $table->foreignId('received_by')->nullable()->constrained('users')->onDelete('set null');
            $table->enum('status', ['collected', 'received', 'processed', 'rejected', 'disposed'])->default('collected');
            $table->timestamps();
        });

        // 4. Lab Equipment
        Schema::create('lab_equipment', function (Blueprint $table) {
            $table->id();
            $table->foreignId('clinic_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->string('model_number')->nullable();
            $table->string('serial_number')->nullable();
            $table->date('purchase_date')->nullable();
            $table->date('last_calibration_date')->nullable();
            $table->date('next_calibration_due')->nullable();
            $table->enum('status', ['active', 'down', 'maintenance'])->default('active');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lab_equipment');
        Schema::dropIfExists('lab_samples');
        Schema::dropIfExists('lab_normal_ranges');
        Schema::dropIfExists('lab_profiles');
    }
};
