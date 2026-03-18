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
        // Doctor Schedules (Template for recurring working hours)
        Schema::create('doctor_schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('staff_id')->constrained('staff')->onDelete('cascade');
            $table->tinyInteger('day_of_week')->comment('0=Sunday, 6=Saturday');
            $table->time('start_time');
            $table->time('end_time');
            $table->time('break_start')->nullable();
            $table->time('break_end')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Time Slots (Specific instances of available time)
        Schema::create('time_slots', function (Blueprint $table) {
            $table->id();
            $table->foreignId('staff_id')->constrained('staff')->onDelete('cascade');
            $table->dateTime('start_time');
            $table->dateTime('end_time');
            $table->enum('status', ['available', 'booked', 'blocked'])->default('available');
            $table->foreignId('appointment_id')->nullable()->constrained()->onDelete('set null');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('time_slots');
        Schema::dropIfExists('doctor_schedules');
    }
};
