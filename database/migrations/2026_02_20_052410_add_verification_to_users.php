<?php
// database/migrations/2026_02_20_000000_add_verification_to_users.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // $table->string('phone')->nullable()->after('email');
            // $table->boolean('phone_verified')->default(false)->after('phone');
            // $table->timestamp('phone_verified_at')->nullable()->after('phone_verified');
            // $table->boolean('email_verified')->default(false)->after('email_verified_at');
            //$table->timestamp('email_verified_at')->nullable()->after('email_verified');
            $table->enum('registration_type', ['admin', 'patient'])->default('patient')->after('email_verified_at');
            $table->foreignId('current_clinic_id')->nullable()->after('registration_type')->constrained('clinics');
            $table->string('avatar')->nullable()->after('current_clinic_id');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'registration_type', 'current_clinic_id', 'avatar'
            ]);
        });
    }
};