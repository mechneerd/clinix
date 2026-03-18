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
        // Vitals table
        Schema::create('vitals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained()->onDelete('cascade');
            $table->foreignId('appointment_id')->nullable()->constrained()->onDelete('set null');
            $table->string('blood_pressure')->nullable();
            $table->decimal('temperature', 4, 1)->nullable();
            $table->integer('pulse')->nullable();
            $table->decimal('weight', 5, 2)->nullable();
            $table->decimal('height', 5, 2)->nullable();
            $table->decimal('bmi', 4, 1)->nullable();
            $table->integer('respiratory_rate')->nullable();
            $table->integer('oxygen_saturation')->nullable();
            $table->foreignId('recorded_by')->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });

        // Vaccinations table
        Schema::create('vaccinations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained()->onDelete('cascade');
            $table->string('vaccine_name');
            $table->string('batch_number')->nullable();
            $table->date('administered_at');
            $table->date('next_dose_at')->nullable();
            $table->foreignId('administered_by')->constrained('users')->onDelete('cascade');
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        // Referrals table
        Schema::create('referrals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained()->onDelete('cascade');
            $table->foreignId('referring_doctor_id')->constrained('staff')->onDelete('cascade');
            $table->string('referred_to_doctor')->nullable();
            $table->string('referred_to_clinic')->nullable();
            $table->text('reason')->nullable();
            $table->enum('status', ['pending', 'completed', 'cancelled'])->default('pending');
            $table->timestamps();
        });

        // Telemedicine Sessions table
        Schema::create('telemedicine_sessions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('appointment_id')->constrained()->onDelete('cascade');
            $table->string('platform')->default('internal');
            $table->string('session_url')->nullable();
            $table->integer('duration_minutes')->nullable();
            $table->string('recording_path')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('telemedicine_sessions');
        Schema::dropIfExists('referrals');
        Schema::dropIfExists('vaccinations');
        Schema::dropIfExists('vitals');
    }
};
