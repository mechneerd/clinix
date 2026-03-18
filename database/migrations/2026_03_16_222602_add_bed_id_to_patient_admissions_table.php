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
        Schema::table('patient_admissions', function (Blueprint $table) {
            $table->foreignId('bed_id')->nullable()->after('room_id')->constrained()->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('patient_admissions', function (Blueprint $table) {
            $table->dropForeign(['bed_id']);
            $table->dropColumn('bed_id');
        });
    }
};
