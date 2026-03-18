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
        // Rooms table
        Schema::create('rooms', function (Blueprint $table) {
            $table->id();
            $table->foreignId('clinic_id')->constrained()->onDelete('cascade');
            $table->string('room_number');
            $table->string('type')->comment('General, Private, ICU, etc.');
            $table->string('floor')->nullable();
            $table->decimal('daily_rate', 10, 2)->default(0);
            $table->boolean('is_occupied')->default(false);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Patient Admissions
        Schema::create('patient_admissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained()->onDelete('cascade');
            $table->foreignId('room_id')->constrained()->onDelete('cascade');
            $table->dateTime('admitted_at');
            $table->dateTime('discharged_at')->nullable();
            $table->text('reason');
            $table->enum('status', ['admitted', 'discharged', 'transferred'])->default('admitted');
            $table->foreignId('admitted_by')->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('patient_admissions');
        Schema::dropIfExists('rooms');
    }
};
