<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('user_profiles', function (Blueprint $table) {
            $table->string('license_number')->nullable()->after('date_of_birth');
            $table->string('specialty')->nullable()->after('license_number');
            $table->unsignedTinyInteger('years_of_experience')->nullable()->after('specialty');
        });
    }

    public function down(): void
    {
        Schema::table('user_profiles', function (Blueprint $table) {
            $table->dropColumn([
                'license_number',
                'specialty',
                'years_of_experience',
            ]);
        });
    }
};