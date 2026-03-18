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
        // 1. Wards
        Schema::create('wards', function (Blueprint $table) {
            $table->id();
            $table->foreignId('clinic_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->string('department')->nullable();
            $table->integer('capacity')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // 2. Beds (More granular than just rooms)
        Schema::create('beds', function (Blueprint $table) {
            $table->id();
            $table->foreignId('room_id')->constrained()->onDelete('cascade');
            $table->string('bed_number');
            $table->enum('type', ['general', 'ventilator', 'icu', 'pediatric'])->default('general');
            $table->enum('status', ['available', 'occupied', 'maintenance'])->default('available');
            $table->decimal('daily_rate', 15, 2)->default(0);
            $table->timestamps();
        });

        // 3. Nursing Notes
        Schema::create('nursing_notes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_admission_id')->constrained()->onDelete('cascade');
            $table->foreignId('nurse_id')->constrained('staff')->onDelete('cascade');
            $table->text('observation');
            $table->text('action_taken')->nullable();
            $table->dateTime('recorded_at');
            $table->timestamps();
        });

        // 4. Intake/Output Records (Fluid tracking)
        Schema::create('intake_output_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_admission_id')->constrained()->onDelete('cascade');
            $table->enum('type', ['intake', 'output']);
            $table->string('route')->nullable(); // Oral, IV, Urine, Drain
            $table->integer('volume_ml');
            $table->dateTime('recorded_at');
            $table->foreignId('recorded_by')->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });

        // 5. Patient Transfers
        Schema::create('patient_transfers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_admission_id')->constrained()->onDelete('cascade');
            $table->foreignId('from_room_id')->nullable()->constrained('rooms');
            $table->foreignId('to_room_id')->constrained('rooms');
            $table->text('reason')->nullable();
            $table->dateTime('transferred_at');
            $table->foreignId('performed_by')->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });

        // 6. Discharge Summaries
        Schema::create('discharge_summaries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_admission_id')->constrained()->onDelete('cascade');
            $table->text('final_diagnosis');
            $table->text('clinical_summary');
            $table->text('treatment_given');
            $table->text('discharge_condition');
            $table->text('follow_up_instructions')->nullable();
            $table->dateTime('discharged_at');
            $table->foreignId('discharged_by')->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('discharge_summaries');
        Schema::dropIfExists('patient_transfers');
        Schema::dropIfExists('intake_output_records');
        Schema::dropIfExists('nursing_notes');
        Schema::dropIfExists('beds');
        Schema::dropIfExists('wards');
    }
};
